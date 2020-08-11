import Vue from 'vue';
import VueRouter from 'vue-router';
import {routes} from "./routes";
import store from "./store";
import {loadLanguageAsync, localeParamName} from './lang/i18n-setup';

Vue.use(VueRouter);

const vueRouter = new VueRouter({
    routes,
    mode: 'history',
});

// Set i18n locale
vueRouter.beforeEach((to, from, next) => {
    let lang = localStorage.getItem(localeParamName);
    if (!['en', 'ru'].includes(lang)) {
        lang = 'en';
    }
    loadLanguageAsync(lang).then(() => next());
});

// Check auth required
vueRouter.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            next();
            return;
        }

        next('/login');
    } else {
        next();
    }
});

export const router = vueRouter;
