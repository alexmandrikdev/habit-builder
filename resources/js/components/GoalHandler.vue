<template>
    <div>
        <div v-if="!habit.goal" class="my-4">
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

                <b-button v-b-modal.finish-goal-modal variant="danger"
                    >Finish</b-button
                >
            </b-card>

            <finish-goal-modal :fetch-data="fetchData" />
        </template>
    </div>
</template>

<script>
import Timer from '../components/Timer.vue';
import FinishGoalModal from './FinishGoalModal.vue';

import { mapState } from 'vuex';

export default {
    components: { Timer, FinishGoalModal },
    props: {
        fetchData: {
            type: Function,
            required: true,
        },
    },
    data() {
        return {
            newGoal: null,
        };
    },
    computed: {
        ...mapState(['habit']),
        goalDate() {
            const goalDate = new Date(this.habit.goal.created_at);

            const day = goalDate.getDate();

            goalDate.setDate(day + parseInt(this.habit.goal.goal));

            return goalDate;
        },
    },
    methods: {
        createGoal() {
            axios
                .post('/goals', {
                    goal: this.newGoal,
                    habit_id: this.habit.id,
                })
                .then(res => {
                    this.newGoal = null;

                    this.$store.commit('setHabitGoal', res.data);
                });
        },
    },
};
</script>

<style scoped></style>
