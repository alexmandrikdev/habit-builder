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

<style>
.gold {
    color: #ffd700;
}

.silver {
    color: #c0c0c0;
}
</style>
