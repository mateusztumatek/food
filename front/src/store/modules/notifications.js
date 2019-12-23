import {getSessionKey} from "../../api/user";
import router from '../../router';
import {search} from "../../api/search";
import {getNotifications} from "../../api/notifications";

const state = {
    notifications:null,
    watching: false,
};

const actions = {
    watchSessionEvents({commit, state}){
        if(state.watching == false){
            Echo.channel('SessionChannel.'+getSessionKey())
                .listen('.notifications', (e) => {
                    console.log(e);
                });
            state.watching = true;
        }
    },
    getNotifications: ({state, commit}) => {
        getNotifications().then(response => {
            commit('SET_NOTIFICATIONS', response);
        }).catch(e => {
            commit('SET_NOTIFICATIONS', []);
        })
    }
}
const mutations = {
    SET_NOTIFICATIONS: (state, value) => {state.notifications = value}
};

export default {
    namespaced: true,
    actions,
    state,
    mutations,
};

