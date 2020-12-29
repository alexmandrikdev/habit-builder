import Vue from 'vue';
import Vuex from 'vuex';

import router from './router';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        isAuthenticated: localStorage.getItem('isAuthenticated') === 'true',
    },
    mutations: {
        setIsAuthenticated(state, value) {
            localStorage.setItem('isAuthenticated', value);

            state.isAuthenticated = value;
        },
    },
    actions: {
        logout({ commit }) {
            axios.defaults.baseURL = '/';

            axios.post('/logout').then(() => {
                commit('setIsAuthenticated', false);
            });

            axios.defaults.baseURL = '/api/v1';

            router.push('/login');
        },
    },
});
