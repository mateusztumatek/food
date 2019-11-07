import {getSellouts, manageSellout, attemptSellout} from "../../api/sellout";
import store from '../index';
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
    setActive: ({commit}, id) => {
        return new Promise((resolve, reject) => {
            manageSellout(id).then(response => {
                commit('SET_ACTIVE', response);
                resolve(response);
            }).catch(e => {
                commit('app/ADD_ERROR', {text: e.response.data.message}, {root: true});
                router.push('/');
                reject(e);
            })
        })
    },
    attempt: ({commit, state}, id) => {
        store.dispatch('sellout/setActive', id).then(response => {
            attemptSellout(state.active.id);
            var attempting = setInterval(() => {
                attemptSellout(state.active.id);
            }, 120000)
            commit('SET_ATTEMPT', attempting);
        });
    },
}
const mutations = {
    UPDATE_SELLOUT: (state, data) => {
        const index = _.findIndex(state.sellouts, ['id', data.id]);
        state[index][data.prop] = data.value;
    },
    SET_ACTIVE: (state, data) => {
        state.active = data;
        const index = _.findIndex(state.sellouts, ['id', data.id]);
    },
    STOP_ATTEMPT: (state) => {
        if(state.attempting){
            clearInterval(state.attempting);
            state.attempting = null;
            state.active = null;
        }
    },
    SET_ATTEMPT: (state, attempting) => {state.attempting = attempting},
    SET_SELLOUTS: (state, data) => {state.sellouts = data}
}
const getters = {
    sellouts: state => {return state.sellouts}
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}