require('./bootstrap');

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import Vue from 'vue';
import { BootstrapVue } from 'bootstrap-vue';
import VueRouter from 'vue-router';
import router from './router';
import App from './App.vue';

Vue.use(BootstrapVue);
Vue.use(VueRouter);

new Vue({
    router,
    render: h => h(App),
}).$mount('#app');
