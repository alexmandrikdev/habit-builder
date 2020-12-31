<template>
    <div>
        <b-form
            class="mx-auto"
            style="max-width: 400px"
            @submit.prevent="onSubmit"
        >
            <b-form-group label="Email:" label-for="email">
                <b-form-input
                    id="email"
                    v-model="formData.email"
                    type="email"
                    required
                    :state="loginError === null ? null : false"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                label="Password:"
                label-for="password"
                :state="loginError === null ? null : false"
            >
                <b-form-input
                    id="password"
                    v-model="formData.password"
                    type="password"
                    required
                    :state="loginError === null ? null : false"
                ></b-form-input>

                <b-form-invalid-feedback>
                    {{ loginError }}
                </b-form-invalid-feedback>
            </b-form-group>

            <div class="text-center">
                <b-button type="submit" variant="primary">Login</b-button>
            </div>
        </b-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            formData: {
                email: '',
                password: '',
            },
            loginError: null,
        };
    },
    methods: {
        onSubmit() {
            axios.defaults.baseURL = '/';

            axios
                .post('/login', this.formData)
                .then(response => {
                    this.$store.commit('setIsAuthenticated', true);

                    this.$router.push('/');
                })
                .catch(error => {
                    this.loginError = error.response.data.errors.email[0];
                });

            axios.defaults.baseURL = '/api/v1';
        },
    },
};
</script>

<style scoped></style>
