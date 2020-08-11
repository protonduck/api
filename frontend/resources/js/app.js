/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue.
 */

try {

    window.$ = window.jQuery = require('jquery');
    window.Popper = require('popper.js').default;

    require('bootstrap');

} catch (e) {

}

window.Vue = require('vue');

import Axios from 'axios';
import Store, {authTokenName} from './store';
import App from './components/App';
import Vuelidate from 'vuelidate';
import VueI18n from 'vue-i18n';
import {router} from './router';
import {i18n} from './lang/i18n-setup';

Vue.use(VueI18n);
Vue.use(Vuelidate);

Vue.prototype.$store = Store;
Vue.prototype.$http = Axios;

Vue.config.productionTip = false;

// Authorization
const authToken = localStorage.getItem(authTokenName);
if (authToken) {
    Vue.prototype.$http.defaults.headers.common['Authorization'] = 'Bearer ' + authToken;
}

const app = new Vue({
    i18n,
    router,
    render: h => h(App),
    beforeMount() {
        // set BaseURL for axios
        Vue.prototype.$http.defaults.baseURL = this.$el.attributes['endpoint'].value;
    },
});

// Mount only if div#app exist
if (document.getElementById('app')) {
    app.$mount('#app');
}
