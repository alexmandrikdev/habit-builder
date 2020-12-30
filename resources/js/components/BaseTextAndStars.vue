<template>
    <div>
        {{ text }}
        <span style="white-space: nowrap">
            {{ silverStars }}
            <b-icon icon="star-fill" class="silver"></b-icon>
            {{ goldenStars }}
            <b-icon icon="star-fill" class="gold"></b-icon>
        </span>
    </div>
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            required: true,
        },
        silverStars: {
            type: [Number, String],
            required: true,
        },
        goldenStars: {
            type: [Number, String],
            required: true,
        },
    },
};
</script>

<style scoped></style>
