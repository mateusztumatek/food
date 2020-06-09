import {getCart, addToCart} from "../../api/cart";

const state = {
  cart:{},
    visible: false,
};

const mutations = {
    SET_CART: (state, data) => {state.cart = data},
    RESET_CART: (state) => {state.cart = {}},
    SET_VISIBLE: (state, data) => {state.visible = data},
    SET_ITEM_QUANTITY: (state, data) => {
        state.cart.items[data.id].quantity = data.quantity}
};

const actions = {
    addToCart({commit, state}, data){
        addToCart(state.cart.sale_id, data).then(response => {
            commit('SET_CART', response);
            commit('SET_VISIBLE', true);
            commit('app/ADD_MESSAGE', 'Dodano przedmiot do koszyka', {root: true});
        }).catch(e => {commit('app/ADD_ERROR', {text: e.response.data.message}, {root: true})});
    },
    getSaleCart({ commit }, data) {
        getCart(data).then(response => {
            commit('SET_CART', response);
        }).catch(e => {commit('app/ADD_ERROR', {text: e.response.data.message}, {root: true})})
    },
};

const getters = {
    visible: (state) => {return state.visible},
    cart: (state) => {return state.cart}
}
export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
