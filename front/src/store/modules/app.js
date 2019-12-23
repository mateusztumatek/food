import router from '../../router';
import {getLangs} from "../../api/langs";
import {i18n} from "../../plugins/i18n";

const state = {
    app:{
        loading: false,
        errors: [],
        messages:[],
        langs:null
    }
};

const actions = {
    getLangs: ({commit, state}) => {
        getLangs().then(response => {
           commit('SET_LANGS', response);
            for (var i in state.app.langs){
                i18n.setLocaleMessage(i, state.app.langs[i]);
            }
        })
    }
}
const mutations = {
    SET_LANGS: (state, value) => {
      state.app.langs = value;
    },
    setLoading: (state, value) => {
        state.app.loading= value;
    },
    ADD_ERROR: (state, value) => {
        state.app.errors.push(value);
    },
    CLOSE_ERROR: (state, error) => {
        state.app.errors.splice(_.findIndex(state.app.errors, ['text', error.text]), 1);
    },
    ADD_MESSAGE: (state, message) => {state.app.messages.unshift({text: message})},
    RESET_MESSAGES: (state) => {state.app.messages = []}
};

const getters = {
    messages: (state) => {return state.app.messages},
    langs: (state) => {return state.app.langs},
}
export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};
