<template>
    <b-navbar toggleable="lg" type="light" variant="light">
        <b-navbar-brand to="/">Habit Builder</b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav class="ml-auto">
                <template v-if="!isAuthenticated">
                    <b-nav-item to="/register">Register</b-nav-item>
                    <b-nav-item to="/login">Login</b-nav-item>
                </template>
                <b-nav-item v-else @click="logout">Logout</b-nav-item>
            </b-navbar-nav>
        </b-collapse>
    </b-navbar>
</template>

<script>
export default {
    computed: {
        isAuthenticated() {
            return this.$store.state.isAuthenticated;
        },
    },
    methods: {
        logout() {
            axios.post('/logout').then(() => {
                this.$store.commit('setIsAuthenticated', false);

                this.$router.push('/login');
            });
        },
    },
};
</script>

<style scoped></style>
