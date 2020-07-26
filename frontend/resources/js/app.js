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

import Axios from 'axios'
import Store from "./store";
import App from './components/App';
import {router} from "./router";

Vue.prototype.$store = Store;
Vue.prototype.$http = Axios;

Vue.config.productionTip = false;

const token = localStorage.getItem('token');

if (token) {
    Vue.prototype.$http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app',
    render: h => h(App),
    beforeMount() {
        // set BaseURL for axios
        Vue.prototype.$http.defaults.baseURL = this.$el.attributes['endpoint'].value;
    }
});
