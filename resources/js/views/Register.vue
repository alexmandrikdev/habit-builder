<template>
    <div>
        <b-form
            class="mx-auto"
            style="max-width: 400px"
            @submit.prevent="onSubmit"
        >
            <b-form-group label="Name:" label-for="name">
                <b-form-input
                    id="name"
                    v-model="formData.name"
                    required
                ></b-form-input>
            </b-form-group>

            <b-form-group label="Email:" label-for="email">
                <b-form-input
                    id="email"
                    v-model="formData.email"
                    type="email"
                    :state="validate('email')"
                    required
                ></b-form-input>

                <b-form-invalid-feedback v-if="registrationErrors.email">
                    {{ registrationErrors.email[0] }}
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Password:" label-for="password">
                <b-form-input
                    id="password"
                    v-model="formData.password"
                    type="password"
                    required
                ></b-form-input>
            </b-form-group>

            <b-form-group
                label="Password Confirmation:"
                label-for="password_confirmation"
            >
                <b-form-input
                    id="password_confirmation"
                    v-model="formData.password_confirmation"
                    type="password"
                    :state="validate('password')"
                    required
                ></b-form-input>

                <b-form-invalid-feedback v-if="registrationErrors.password">
                    {{ registrationErrors.password[0] }}
                </b-form-invalid-feedback>
            </b-form-group>

            <b-button type="submit" variant="primary">Submit</b-button>
        </b-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            formData: {
                email: '',
                name: '',
                password: '',
                password_confirmation: '',
            },
            registrationErrors: {},
        };
    },
    methods: {
        validate(input) {
            return this.registrationErrors.hasOwnProperty(input) ? false : null;
        },
        onSubmit() {
            axios.defaults.baseURL = '/';

            axios
                .post('/register', this.formData)
                .then(response => {
                    this.$store.commit('setIsAuthenticated', true);

                    this.$router.push('/');
                })
                .catch(error => {
                    this.registrationErrors = error.response.data.errors;
                });

            axios.defaults.baseURL = '/api/v1';
        },
    },
};
</script>

<style scoped></style>
