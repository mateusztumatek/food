import router from '../../router';
import {is_mobile} from "../../utilis/helper";

const state = {
    sidebar: {
        opened: false,
        withoutAnimation: false,
    },
    device: 'desktop',
    mobile: is_mobile(),
    title: 'App'
};

const mutations = {
    TOGGLE_SIDEBAR: state => {
        state.sidebar.opened = !state.sidebar.opened;
    },
    CLOSE_SIDEBAR: (state, withoutAnimation) => {
        state.sidebar.opened = false;
    },
    SET_TITLE: (state, title) => {
        state.title = title;
    },
};

const actions = {
    toggleSideBar({ commit }) {
        commit('TOGGLE_SIDEBAR');
    },
    closeSideBar({ commit }, { withoutAnimation }) {
        commit('CLOSE_SIDEBAR', withoutAnimation);
    },
    setTitle({commit}, title){
        commit('SET_TITLE', title);
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
