import store from '../../store';
import {getUserProducts, storeProduct, editProduct} from "../../api/products";

const state = {
    products: [],
    new_product: {},
};
const actions = {
    getProducts: ({commit}) => {
        getUserProducts(store.getters.user.id).then(response => {
            commit('SET_PRODUCTS', response);
        }).catch(e => {
            commit('app/ADD_ERROR', {text: e.response.data.message}, {root: true});
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
    },
    editProduct: ({commit, state}) => {
        return new Promise((resolve, reject) => {
            editProduct(state.new_product.id, state.new_product).then(response => {
                commit('UPDATE_PRODUCT', response);
                resolve(response);
            }).catch(e => {
                commit('app/ADD_ERROR', {text: 'Błąd: '+e.response.message}, {root: true});
                reject(e);
            })
        })
    },
    deleteProduct: ({commit, state}) => {
        return new Promist((resolve, reject) => {

        })
    }
}
const mutations = {
    SET_NEW_PRODUCT_PROPERTY: (state, data) => {
        if(data.full){
            state.new_product = data.full;
        }else{
            let obj = {};
            obj[data.prop] = data.value;
            state.new_product = {...state.new_product, ...obj};
        }
    },
    ADD_PRODUCT: (state, data) => {state.products.push(data)},
    SET_PRODUCTS: (state, data) => {state.products = data},
    UPDATE_PRODUCT:(state, data) => {
        const index = _.findIndex(state.products, ['id', data.id]);
        state.products[index] = data;
    },
    DELETE_PRODUCT: (state, data) => {
        const index = _.findIndex(state.products, ['id', data.id]);
        state.products.splice(index, 1);
    }
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
