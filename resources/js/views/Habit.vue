<template>
    <div>
        <div v-if="loading" class="text-center mt-5">
            <b-spinner label="Loading..." class="mx-auto"></b-spinner>
        </div>

        <div v-else class="text-center">
            <h2 class="mb-3">
                <base-text-and-stars
                    :text="habit.name"
                    :silver-stars="habit.days_sum_silver_stars || 0"
                    :golden-stars="habit.days_sum_golden_stars || 0"
                />
            </h2>

            <div v-if="!habit.goal" class="mt-5">
                <h4>Set Your Goal</h4>
                <b-form @submit.prevent="createGoal">
                    <b-form-group>
                        <b-form-input
                            v-model="newGoal"
                            class="text-center"
                            required
                            placeholder="Day"
                        ></b-form-input>
                    </b-form-group>
                    <b-button type="submit" variant="primary">Set</b-button>
                </b-form>
            </div>

            <template v-else>
                <b-card class="my-4">
                    <h3>
                        Goal: {{ habit.goal.goal }} day{{
                            habit.goal.goal != 1 ? 's' : ''
                        }}
                    </h3>

                    <timer class="my-2" :goal-date="goalDate"></timer>

                    <b-button v-b-modal.stop-modal variant="danger"
                        >Stop</b-button
                    >
                </b-card>

                <b-modal
                    id="stop-modal"
                    centered
                    title=""
                    header-bg-variant="danger"
                    header-text-variant="light"
                    @show="onModalShow"
                    @hide="onModalHide"
                >
                    <template #modal-header class="text-center">
                        <h5 class="mx-auto my-0">Are you sure?</h5>
                    </template>

                    <div class="text-center">
                        <h5>
                            Your goal is: {{ habit.goal.goal }} day{{
                                habit.goal.goal != 1 ? 's' : ''
                            }}.
                        </h5>
                        <h5>
                            Your current state is:
                            {{ currentState }} day{{
                                currentState > 1 ? 's' : ''
                            }}.
                        </h5>
                        <h5 v-if="currentState > 0">
                            You will get
                            <template v-if="currentState < habit.goal.goal">
                                1
                                <b-icon
                                    icon="star-fill"
                                    class="silver"
                                ></b-icon>
                                for Day {{ currentState }}.
                            </template>
                            <template v-else>
                                1
                                <b-icon icon="star-fill" class="gold"></b-icon>
                                for Day {{ currentState }}.</template
                            >
                        </h5>
                    </div>

                    <template #modal-footer class="text-center">
                        <b-button
                            class="mx-auto my-0"
                            variant="danger"
                            @click="finishGoal"
                            >Yes</b-button
                        >
                    </template>
                </b-modal>
            </template>

            <template v-if="habit.days.length">
                <b-card v-for="day in weekDays" :key="day.day" class="my-2">
                    <b-card-title>Day {{ day.day }} </b-card-title>
                    <b-card-text>
                        {{ day.silver_stars }}
                        <b-icon icon="star-fill" class="silver"></b-icon>
                        {{ day.golden_stars }}
                        <b-icon icon="star-fill" class="gold"></b-icon
                    ></b-card-text>
                </b-card>

                <h5 class="mt-4 mb-2">Weeks</h5>
                <b-pagination
                    v-model="week"
                    align="center"
                    :total-rows="maxDay"
                    :per-page="7"
                    @change="handleWeekChange"
                >
                </b-pagination>
            </template>
        </div>
    </div>
</template>

<script>
import Timer from '../components/Timer.vue';

export default {
    components: { Timer },
    data() {
        return {
            loading: false,
            habit: null,
            newGoal: null,
            currentState: null,
            currentStateInterval: null,
        };
    },
    computed: {
        goalDate() {
            const goalDate = new Date(this.habit.goal.created_at);

            const day = goalDate.getDate();

            goalDate.setDate(day + parseInt(this.habit.goal.goal));

            return goalDate;
        },
        week() {
            return this.$route.query.week || 1;
        },
        weekDays() {
            return this.habit.days.filter(
                day =>
                    day.day >= (this.week - 1) * 7 && day.day <= this.week * 7,
            );
        },
        maxDay() {
            if (!this.habit.days.length) {
                return 0;
            }

            return [...this.habit.days].sort((a, b) => {
                return a.day < b.day;
            })[0].day;
        },
    },
    created() {
        this.fetchData();
    },
    beforeDestroy() {
        clearInterval(this.currentStateInterval);
    },
    methods: {
        onModalShow() {
            this.calculateCurrentState();

            this.currentStateInterval = setInterval(
                () => this.calculateCurrentState(),
                1000,
            );
        },
        onModalHide() {
            clearInterval(this.currentStateInterval);
        },
        calculateCurrentState() {
            const diffTime =
                Date.now() - Date.parse(this.habit.goal.created_at);

            this.currentState = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        },
        fetchData() {
            this.loading = true;

            axios.get(`/habits/${this.$route.params.id}`).then(res => {
                this.habit = res.data;

                this.loading = false;
            });
        },
        createGoal() {
            axios
                .post('/goals', {
                    goal: this.newGoal,
                    habit_id: this.habit.id,
                })
                .then(res => {
                    this.newGoal = null;

                    this.habit.goal = res.data;
                });
        },
        finishGoal() {
            axios
                .put(`/goals/${this.habit.goal.id}`, {
                    habit_id: this.habit.id,
                })
                .then(res => {
                    this.fetchData();
                });
        },
        handleWeekChange(week) {
            this.$router.push({
                path: `/${this.$route.params.id}`,
                query: { week },
            });
        },
    },
};
</script>

<style scoped></style>
