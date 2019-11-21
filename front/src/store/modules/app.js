import router from '../../router';

const state = {
    app:{
        loading: false,
        errors: [],
        messages:[],
    }
};

const mutations = {
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
    messages: (state) => {return state.app.messages}
}
export default {
    namespaced: true,
    state,
    getters,
    mutations,
};
