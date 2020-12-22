require("./bootstrap");

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";

import Vue from "vue";

import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import VueRouter from "vue-router";

Vue.use(BootstrapVue);

Vue.use(VueRouter);

import Index from "./views/Index.vue";

const router = new VueRouter({
    mode: "history",
    routes: [{ path: "/", component: Index }]
});

new Vue({
    router,
    el: "#app"
});
