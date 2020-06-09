import router from '../../router';

const state = {
    app:{
        loading: false,
        errors: []
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
    }
};


export default {
    namespaced: true,
    state,
    mutations,
};
