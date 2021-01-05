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
    show_category_modal: false,
    show_board_modal: false,
    show_link_modal: false,
    active_board_id: 0,
    current_category_id: 0,
    boards: [],
    categories: [],
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
    board_save(state, board) {
      state.board = board;
    },
    board_remove(state) {},
    category_save(state, category) {},
    category_remove(state) {},
    link_save(state, category) {},
    link_remove(state) {},
    toggle_category_modal(state, payload) {
      state.show_category_modal = payload;
    },
    toggle_board_modal(state, payload) {
      state.show_board_modal = payload;
    },
    toggle_link_modal(state, payload) {
      state.show_link_modal = payload;
    },
    change_active_board_id(state, payload) {
      state.active_board_id = payload;
    },
    change_current_category_id(state, payload) {
      state.current_category_id = payload;
    },
    update_boards(state, payload) {
      state.boards = payload;
    },
    update_categories(state, payload) {
      state.categories = payload;
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
    board_save({commit}, payload) {
      return new Promise((resolve, reject) => {
        commit('board_save', payload);
        axios({url: payload.url, method: payload.method, data: payload})
          .then(resp => {
            commit('board_save', payload);
            resolve(resp);
          })
          .catch(err => {
            commit('board_save');
            reject(err);
          });
      });
    },
    board_remove({commit}, payload) {
      return new Promise((resolve, reject) => {
        commit('board_remove');
        axios({url: payload.url, method: payload.method, data: payload})
          .then(resp => {
            commit('board_remove');
            resolve(resp);
          })
          .catch(err => {
            commit('board_remove', payload);
            reject(err);
          });
      });
    },
    category_save({commit}, payload) {
      return new Promise((resolve, reject) => {
        commit('category_save', payload);
        axios({url: payload.url, method: payload.method, data: payload})
          .then(resp => {
            commit('category_save', payload);
            resolve(resp);
          })
          .catch(err => {
            commit('category_save');
            reject(err);
          });
      });
    },
    category_remove({commit}, payload) {
      return new Promise((resolve, reject) => {
        commit('category_remove');
        axios({url: payload.url, method: payload.method, data: payload})
          .then(resp => {
            commit('category_remove');
            resolve(resp);
          })
          .catch(err => {
            commit('category_remove', payload);
            reject(err);
          });
      });
    },
    link_save({commit}, payload) {
      return new Promise((resolve, reject) => {
        commit('link_save', payload);
        axios({url: payload.api_url, method: payload.method, data: payload})
          .then(resp => {
            commit('link_save', payload);
            resolve(resp);
          })
          .catch(err => {
            commit('link_save');
            reject(err);
          });
      });
    },
    link_remove({commit}, payload) {
      return new Promise((resolve, reject) => {
        commit('link_remove');
        axios({url: payload.url, method: payload.method, data: payload})
          .then(resp => {
            commit('link_remove');
            resolve(resp);
          })
          .catch(err => {
            commit('link_remove', payload);
            reject(err);
          });
      });
    },
  },
  getters: {
    isLoggedIn: state => !!state.token,
    authStatus: state => state.status,
    showCategoryModal: state => state.show_category_modal,
    showBoardModal: state => state.show_board_modal,
    showLinkModal: state => state.show_link_modal,
    activeBoardId: state => state.active_board_id,
    currentCategoryId: state => state.current_category_id,
    boards: state => state.boards,
    categories: state => state.categories,
  },
})
