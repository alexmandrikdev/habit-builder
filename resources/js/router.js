import Vue from 'vue';
import Router from 'vue-router';
import Index from './views/Index.vue';
import Register from './views/Register.vue';
import Login from './views/Login.vue';
import store from './store';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Index,
            meta: {
                requiresAuth: true,
            },
        },
        { path: '/register', component: Register },
        { path: '/login', component: Login },
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.state.isAuthenticated) {
            next({
                path: '/login',
            });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
