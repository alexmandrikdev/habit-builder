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
                <b-button v-b-toggle.stats variant="primary">
                    <b-icon icon="bar-chart" />
                </b-button>
            </h2>

            <b-collapse id="stats">
                <stats />
            </b-collapse>

            <goal-handler :fetch-data="fetchData" />

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
import GoalHandler from '../components/GoalHandler.vue';
import Stats from '../components/Stats.vue';

import { mapState } from 'vuex';

export default {
    components: { GoalHandler, Stats },
    data() {
        return {
            loading: false,
        };
    },
    computed: {
        ...mapState(['habit']),
        week() {
            return this.$route.query.week || 1;
        },
        weekDays() {
            return this.habit.days.filter(
                day =>
                    day.day > (this.week - 1) * 7 && day.day <= this.week * 7,
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
    destroyed() {
        this.$store.commit('setHabit', null);
    },
    methods: {
        fetchData() {
            this.loading = true;

            axios.get(`/habits/${this.$route.params.id}`).then(res => {
                this.$store.commit('setHabit', res.data);

                this.loading = false;
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
