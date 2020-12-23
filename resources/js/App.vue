<template>
    <b-container>
        <navbar />

        <router-view></router-view>
    </b-container>
</template>

<script>
import Navbar from './components/Navbar.vue';
export default {
    components: {
        Navbar,
    },
};
</script>

<style scoped></style>
