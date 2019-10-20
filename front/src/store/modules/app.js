import router from '../../router';

const state = {
    app:{
        loading: false,
    }
};

const mutations = {
    setLoading: (state, value) => {
        state.app.loading= value;
    },

};


export default {
    namespaced: true,
    state,
    mutations,
};
