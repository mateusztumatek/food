import store from '../index';
import {storeStats} from "../../api/stats";
import {setSessionKey} from "../../utilis/session";
import {getSessionKey} from "../../api/user";
import router from '../../router';
const state = {
    stats:[],
};
const actions = {
    cacheStat({commit, state}, data){
        state.stats.push(data);
    },
    saveStats({commit, state}){
        if(state.stats.length > 0){
            storeStats(state.stats).then(response => {
                state.stats = [];
            }).catch(e => {state.stats = []});
        }
    },
    getSessionKey({commit, state}){
       getSessionKey().then(response => {
           if(response['session-key']){
               setSessionKey(response['session-key']);
               if(!state.watching) store.dispatch('notifications/watchSessionEvents');
           }
       })
    },

}
const mutations = {

}
const getters = {
    stats: state => state.stats,
}
setInterval(() => {
    store.dispatch('stats/saveStats');
},30000);
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}

