<template>
    <h3 :class="[remainingTimeIsNegative ? 'text-success' : 'text-danger']">
        {{ remainingTime }}
    </h3>
</template>

<script>
export default {
    props: {
        goalDate: {
            type: Date,
            required: true,
        },
    },
    data() {
        return {
            interval: null,
            remainingTime: null,
            remainingTimeIsNegative: false,
        };
    },
    created() {
        this.calculateRemainingTime();

        this.interval = setInterval(() => {
            this.calculateRemainingTime();
        }, 1000);
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    methods: {
        calculateRemainingTime() {
            let diffTime = this.goalDate - Date.now();

            this.remainingTimeIsNegative = diffTime / (1000 * 60 * 60 * 24) < 0;

            diffTime = Math.abs(diffTime);

            const diffDay = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            let diffHours = Math.floor(diffTime / (1000 * 60 * 60));
            let diffMinutes = Math.floor(diffTime / (1000 * 60));
            let diffSeconds = Math.floor(diffTime / 1000) - diffMinutes * 60;

            diffMinutes -= diffHours * 60;
            diffHours -= diffDay * 24;

            if (diffHours < 10) {
                diffHours = '0' + diffHours;
            }

            if (diffMinutes < 10) {
                diffMinutes = '0' + diffMinutes;
            }

            if (diffSeconds < 10) {
                diffSeconds = '0' + diffSeconds;
            }

            this.remainingTime = '';

            if (diffDay !== 0) {
                this.remainingTime += `${diffDay} day${
                    Math.abs(diffDay) !== 1 ? 's' : ''
                } `;
            }

            this.remainingTime += `${diffHours}:${diffMinutes}:${diffSeconds}`;
        },
    },
};
</script>

<style scoped></style>
