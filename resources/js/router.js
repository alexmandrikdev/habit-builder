import Vue from 'vue';
import Router from 'vue-router';
import Index from './views/Index.vue';
import Register from './views/Register.vue';
import Login from './views/Login.vue';
import Habit from './views/Habit.vue';
import Stats from './views/Stats.vue';
import store from './store';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        { path: '/register', component: Register },
        { path: '/login', component: Login },
        {
            path: '/',
            component: Index,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/:id',
            component: Habit,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/:id/stats',
            component: Stats,
            meta: {
                requiresAuth: true,
            },
        },
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
