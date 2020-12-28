<template>
    <div>
        <template v-if="habit">
            <h1 class="mb-3 text-center">
                {{ habit.name }}
                {{ habit.days_sum_silver_stars }}
                <b-icon icon="star-fill" class="silver"></b-icon>
                {{ habit.days_sum_golden_stars }}
                <b-icon icon="star-fill" class="gold"></b-icon>
            </h1>

            <b-card
                v-for="day in weekDays"
                :key="day.day"
                class="my-2 text-center"
            >
                <b-card-title>Day {{ day.day }} </b-card-title>
                <b-card-text>
                    {{ day.silver_stars }}
                    <b-icon icon="star-fill" class="silver"></b-icon>
                    {{ day.golden_stars }}
                    <b-icon icon="star-fill" class="gold"></b-icon
                ></b-card-text>
            </b-card>

            <b-pagination
                v-model="week"
                class="mt-4"
                align="center"
                :total-rows="
                    [...habit.days].sort((a, b) => {
                        return a.day < b.day;
                    })[0].day
                "
                :per-page="7"
                @change="handleWeekChange"
            >
                <template #page="{ page }"> Week {{ page }} </template>
            </b-pagination>
        </template>
    </div>
</template>

<script>
export default {
    data() {
        return {
            habit: null,
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
    },
    created() {
        axios.get(`/habits/${this.$route.params.id}`).then(res => {
            // console.log(res.data);

            this.habit = res.data;
        });
    },
    methods: {
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
