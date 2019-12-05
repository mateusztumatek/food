import {getSessionKey} from "../../api/user";
import router from '../../router';
import {search} from "../../api/search";

const state = {
    notifications: [],
    watching: false,
};

const actions = {
    watchSessionEvents({commit, state}){
        if(state.watching == false){
            Echo.channel('SessionChannel.'+getSessionKey())
                .listen('.discount_code', (e) => {
                    console.log(e);
                });
            state.watching = true;
        }
    }
}
const mutations = {


};

export default {
    namespaced: true,
    actions,
    state,
    mutations,
};

