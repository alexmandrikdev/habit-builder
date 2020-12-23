<template>
    <b-container>
        <div>
            <b-navbar toggleable="lg" type="light" variant="light">
                <b-navbar-brand to="/">Habit Builder</b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav class="ml-auto">
                        <b-nav-item to="/register">Register</b-nav-item>
                        <b-nav-item to="/login">Login</b-nav-item>
                        <b-nav-item @click="logout">Logout</b-nav-item>
                    </b-navbar-nav>
                </b-collapse>
            </b-navbar>
        </div>

        <router-view></router-view>
    </b-container>
</template>

<script>
export default {
    methods: {
        logout() {
            axios.post('/logout');
        },
    },
};
</script>

<style scoped></style>
