<template>
    <div>
        <div v-if="habit" class="text-center">
            <h2 class="mb-3">
                {{ habit.name }}
                <span style="white-space: nowrap">
                    {{ habit.days_sum_silver_stars || 0 }}
                    <b-icon icon="star-fill" class="silver"></b-icon>
                    {{ habit.days_sum_golden_stars || 0 }}
                    <b-icon icon="star-fill" class="gold"></b-icon>
                </span>
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
                <b-button v-b-modal.stop-modal variant="danger">Stop</b-button>

                <b-modal
                    id="stop-modal"
                    title=""
                    header-bg-variant="danger"
                    header-text-variant="light"
                >
                    <template #modal-header class="text-center">
                        <h5 class="mx-auto my-0">Are you sure?</h5>
                    </template>
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
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            habit: null,
            newGoal: null,
        };
    },
    computed: {
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
    methods: {
        fetchData() {
            axios.get(`/habits/${this.$route.params.id}`).then(res => {
                // console.log(res.data);

                this.habit = res.data;
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
