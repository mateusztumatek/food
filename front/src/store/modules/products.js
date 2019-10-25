import store from '../../store';
import {getUserProducts} from "../../api/products";

const state = {
    products: []
};
const actions = {
    getProducts: ({commit}) => {
        console.log('fwafwafwa');
        getUserProducts(store.getters.user.id).then(response => {

        })
    }
}
const mutations = {
};
const getters = {
    products: (state) => {return state.products}
}
export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
