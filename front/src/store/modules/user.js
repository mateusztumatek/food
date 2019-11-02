import {getToken, setToken, removeToken} from "../../utilis/auth";
import {getUser, login, register, logout, resend, update} from "../../api/user";
import store from '../index';
import router from '../../router';
const state = {
    user: {}
};

const actions = {
    update: ({commit}, data) => {
      return new Promise((resolve, reject) => {
          update(data.data, data.id).then(response => {
              resolve(response);
              commit('setUser', response);
          }).catch(e => {
              reject(e);
          })
      })
    },
    getUser: ({ commit }) => {
        return new Promise((resolve, reject) => {
            getUser().then(response => {
                commit('setUser', response);
                store.dispatch('places/getPlaces', {user_id: response.id});
                resolve(response);
            }).catch(e => {
                removeToken();
                router.push('/login');
                reject(e);
            })
        })
    },
    logout: ({commit}) => {
      return new Promise((resolve, reject) => {
          logout().then(response => {
              commit('setUser', {});
              removeToken();
              router.push('/login');
              resolve();
          }).catch(e => {
              reject(e);
          })
      })
    },
    login: ({commit}, data) => {
        return new Promise((resolve, reject) => {
            login(data).then(response => {
                commit('setUser', response.user);
                store.dispatch('places/getPlaces', {user_id: response.user.id});
                setToken(response.access_token);
                resolve();
            }).catch(e => {
                reject(e);
            })
        })

    },
    register: ({commit}, data) => {
        return new Promise((resolve, reject) => {
            register(data).then(response => {
                console.log('REGISTER USER', response.user, response.token);
                commit('setUser', response.user);
                setToken(response.token);
                resolve(response);
            }).catch(e => {
                reject(e);
            })
        })
    }
};

const mutations = {
    setUser: (state, user) => {state.user = user},
    setField: (state, payload) => {state.user[payload.field] = payload.data},
};

const getters = {
    user: state => state.user
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}