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
                ></b-form-input>
            </b-form-group>

            <b-form-group label="Password:" label-for="password">
                <b-form-input
                    id="password"
                    v-model="formData.password"
                    type="password"
                    required
                ></b-form-input>
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
                password: '',
            },
        };
    },
    methods: {
        onSubmit() {
            axios.post('/login', this.formData).then(response => {
                axios.get('/api/user').then(response => {
                    console.log(response);
                });
            });
        },
    },
};
</script>

<style scoped></style>
