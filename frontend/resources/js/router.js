import Vue from 'vue';
import VueRouter from 'vue-router';
import {routes} from "./routes";
import store from "./store";

Vue.use(VueRouter);

const vueRouter = new VueRouter({
    routes,
    mode: 'history',
});

vueRouter.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {

        if (store.getters.isLoggedIn) {
            next()
            return
        }

        next('/login')

    } else {
        next()
    }

});

export const router = vueRouter;
