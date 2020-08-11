import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export const authTokenName = 'authToken';

export default new Vuex.Store({
    state: {
        status: '',
        token: localStorage.getItem(authTokenName) || '',
        user: {},
    },
    mutations: {
        auth_request(state) {
            state.status = 'loading';
        },
        auth_success(state, token, user) {
            state.status = 'success';
            state.token = token;
            state.user = user;
        },
        auth_error(state) {
            state.status = 'error';
        },
        logout(state) {
            state.status = '';
            state.token = '';
        },
    },
    actions: {
        login({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('auth_request');
                axios({url: '/user/login', data: user, method: 'POST'})
                    .then(resp => {
                        const token = resp.data.api_key;
                        const user = resp.data;
                        localStorage.setItem(authTokenName, token);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                        commit('auth_success', token, user);
                        resolve(resp);
                    })
                    .catch(err => {
                        commit('auth_error');
                        localStorage.removeItem(authTokenName);
                        reject(err);
                    });
            })
        },
        register({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('auth_request');
                axios({url: '/user/signup', data: user, method: 'POST'})
                    .then(resp => {
                        const token = resp.data.api_key;
                        const user = resp.data;
                        localStorage.setItem(authTokenName, token);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                        commit('auth_success', token, user);
                        resolve(resp);
                    })
                    .catch(err => {
                        commit('auth_error', err);
                        localStorage.removeItem(authTokenName);
                        reject(err);
                    });
            })
        },
        logout({commit}) {
            return new Promise((resolve, reject) => {
                commit('logout');
                localStorage.removeItem(authTokenName);
                delete axios.defaults.headers.common['Authorization'];
                resolve();
            })
        },
    },
    getters: {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
    },
})
