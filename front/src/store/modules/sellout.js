import {getSellouts, manageSellout, attemptSellout} from "../../api/sellout";
import store from '../index';
import Vue from 'vue';
import router from '../../router';
const state = {
    sellouts:[],
    attempting: null,
    active: null
};
const actions = {
    getSellouts: ({commit, state}) => {
        var user = store.getters.user;
        var params = {user_id: user.id};
        getSellouts(params).then(response => {
          /*  response.forEach((item) => {
                Echo.channel('SaleChannel.'+item.id)
                    .listen('.change_status', (e) => {
                        const index = _.findIndex(state.sellouts, ['id', e.sale.id]);
                        state.sellouts[index].is_attempted = e.sale.is_attempted;
                    });
            })*/
            commit('SET_SELLOUTS', response);
        })
    },
    attempt: ({commit, state}, id) => {
            attemptSellout(id);
            var attempting = setInterval(() => {
                attemptSellout(id);
            }, 120000)
            commit('SET_ATTEMPT', attempting);
    },
}
const mutations = {
    DELETE_SELLOUT: (state, data) => {
        if(state.active.id == data.id) state.active = null;
        var index = state.sellouts.findIndex(x => x.id == data.id);
        if(index != -1) state.sellouts.splice(index, 1);
    },
    UPDATE_SELLOUT: (state, data) => {
        if(data.prop){
            const index = _.findIndex(state.sellouts, ['id', data.id]);
            state.sellouts[index][data.prop] = data.value;
        }else{
            const index = _.findIndex(state.sellouts, ['id', data.id]);
            Vue.set(state.sellouts, index, data);
        }

    },
  /*  SET_ACTIVE: (state, data) => {
        state.active = data;
        const index = _.findIndex(state.sellouts, ['id', data.id]);
    },*/
    STOP_ATTEMPT: (state) => {
        if(state.attempting){
            clearInterval(state.attempting);
            state.attempting = null;
/*
            state.active = null;
*/
        }
    },
    SET_ACTIVE_SELLOUT: (state, data) => {state.active = data},
    SET_ATTEMPT: (state, attempting) => {state.attempting = attempting},
    SET_SELLOUTS: (state, data) => {state.sellouts = data}
}
const getters = {
    sellouts: state => {return state.sellouts},
    sellout: state => {return state.active}
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}