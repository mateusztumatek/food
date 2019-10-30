import store from '../../store';
import {getUserProducts, storeProduct} from "../../api/products";

const state = {
    products: [],
    new_product: {},
};
const actions = {
    getProducts: ({commit}) => {
        getUserProducts(store.getters.user.id).then(response => {

        })
    },
    saveProduct: ({commit, state}) => {
        return new Promise((resolve, reject) => {
            storeProduct(state.new_product).then(response => {
                commit('ADD_PRODUCT', response);
                resolve(response);
            }).catch(e => {
                reject(e);
            })
        })
    }
}
const mutations = {
    SET_NEW_PRODUCT_PROPERTY: (state, data) => {
        let obj = {};
        obj[data.prop] = data.value;
        state.new_product = {...state.new_product, ...obj};
    },
    ADD_PRODUCT: (state, data) => {state.products.push(data)}
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
