<template>
    <b-modal
        id="finish-goal-modal"
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
                {{ currentState }} day{{ currentState > 1 ? 's' : '' }}.
            </h5>
            <h5 v-if="currentState > 0">
                You will get
                <template v-if="currentState < habit.goal.goal">
                    1
                    <b-icon icon="star-fill" class="silver"></b-icon>
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
            <b-button class="mx-auto my-0" variant="danger" @click="finishGoal"
                >Yes</b-button
            >
        </template>
    </b-modal>
</template>

<script>
import { mapState } from 'vuex';

export default {
    props: {
        fetchData: {
            type: Function,
            required: true,
        },
    },
    data() {
        return {
            currentState: null,
            currentStateInterval: null,
        };
    },
    computed: {
        ...mapState(['habit']),
    },
    beforeDestroy() {
        clearInterval(this.currentStateInterval);
    },
    methods: {
        finishGoal() {
            axios
                .put(`/goals/${this.habit.goal.id}`, {
                    habit_id: this.habit.id,
                })
                .then(res => {
                    this.fetchData();
                });
        },
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
    },
};
</script>

<style scoped></style>
