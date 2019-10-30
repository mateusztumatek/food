import router from '../../router';
import {search} from "../../api/search";

const state = {
    search:{
        isSearch: false,
    }

};

const mutations = {
    setSearch: (state, value) => {
        state.search.isSearch= value;
    },

};

export default {
    namespaced: true,
    state,
    mutations,
};
