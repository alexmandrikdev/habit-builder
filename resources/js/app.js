require('./bootstrap');

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import Vue from 'vue';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue';
import VueRouter from 'vue-router';
import router from './router';
import store from './store';
import App from './App.vue';

Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(VueRouter);

new Vue({
    router,
    store,
    render: h => h(App),
}).$mount('#app');
