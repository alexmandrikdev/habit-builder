<template>
    <div>
        <div v-if="loading" class="text-center mt-5">
            <b-spinner label="Loading..." class="mx-auto"></b-spinner>
        </div>

        <div v-else class="text-center">
            <b-button v-b-toggle.create-habit-form class="mb-3 mt-1"
                >Add New Habit</b-button
            >

            <b-collapse id="create-habit-form" class="mb-3">
                <b-form @submit.prevent="createHabit">
                    <b-form-group>
                        <b-form-input
                            v-model="newHabit"
                            class="text-center"
                            required
                            placeholder="New Habit"
                        ></b-form-input>
                    </b-form-group>
                    <b-button type="submit" variant="primary">Add</b-button>
                </b-form>
            </b-collapse>

            <div v-if="habits.length">
                <b-card
                    v-for="habit in habits"
                    :key="habit.id"
                    class="my-2 text-center"
                >
                    <b-card-title>
                        <base-text-and-stars
                            :text="habit.name"
                            :silver-stars="habit.days_sum_silver_stars || 0"
                            :golden-stars="habit.days_sum_golden_stars || 0"
                        />
                    </b-card-title>

                    <timer
                        v-if="habit.goal"
                        :goal-date="calculateGoalDate(habit.goal)"
                    />

                    <b-button
                        :to="{
                            path: `/${habit.id}?week=${Math.ceil(
                                habit.days_max_day / 7,
                            ) || 1}`,
                        }"
                        >Open</b-button
                    >
                </b-card>
            </div>

            <b-pagination
                v-model="currentPage"
                class="mt-4"
                align="center"
                :total-rows="total"
                :per-page="perPage"
                @change="handlePageChange"
            ></b-pagination>
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
            habits: [],
            newHabit: null,
            perPage: null,
            total: null,
            currentPage: 1,
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        calculateGoalDate(goal) {
            const goalDate = new Date(goal.created_at);

            const day = goalDate.getDate();

            goalDate.setDate(day + parseInt(goal.goal));

            return goalDate;
        },
        createHabit() {
            axios
                .post('/habits', {
                    name: this.newHabit,
                })
                .then(res => {
                    this.newHabit = null;

                    this.fetchData();
                });
        },
        handlePageChange(page) {
            this.$router.push({ path: '/', query: { page } });

            this.fetchData();
        },
        fetchData() {
            this.habits = [];

            this.loading = true;

            axios
                .get(`/habits?page=${this.$route.query.page || 1}`)
                .then(res => {
                    // console.log(res.data);

                    this.habits = res.data.data;
                    this.perPage = res.data.per_page;
                    this.total = res.data.total;
                    this.currentPage = res.data.current_page;

                    this.loading = false;
                })
                .catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                        console.log(error.response.status);
                        console.log(error.response.headers);
                    } else {
                        console.log('Error', error.message);
                    }
                });
        },
    },
};
</script>

<style></style>
