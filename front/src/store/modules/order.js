import router from '../../router';
import {getUserOrders, storeOrder, getOrder, updateOrder} from '../../api/order';
import {processPercentageTimeleft} from "../../utilis/helper";
import Vue from 'vue';
import store from '../index';
const state = {
    userOrders:[],
    activeOrder: null,
    order_sync: false
};

var getOrderPercentage = (order) => {
    if(order){
        var data = processPercentageTimeleft(order.payment_date, order.time);
        if(data){
            data.id = order.id;
            store.commit('order/SET_TIMELEFT', data, {root: true});
            setInterval(() => {
                var data = processPercentageTimeleft(order.payment_date, order.time);
                data.id = order.id;
                store.commit('order/SET_TIMELEFT', data, {root: true});
            }, 30000)
        }
    }
};
const mutations = {
    SET_TIMELEFT: (state, data) => {
        state.userOrders.forEach((item, index) => {
            if(data.id == item.id){
                if(!item.timeleft || data.percentage != item.timeleft.percentage){
                    Vue.set(state.userOrders[index], 'timeleft', data);
                }
            }
        })
        if(state.activeOrder && state.activeOrder.id == data.id){
            if(!state.activeOrder.timeleft || data.percentage != state.activeOrder.timeleft.percentage){
                Vue.set(state.activeOrder, 'timeleft', data);
            }
        }
    },
    UPDATE_ORDER: (state, data) => {
        if(state.userOrders.length > 0){
            state.userOrders.forEach((item, index) => {
                if(item.id == data.id){
                    Vue.set(state.userOrders, index, data)
                    getOrderPercentage(state.userOrders[index]);
                };
            })
        }
        if(state.activeOrder && state.activeOrder.id == data.id){
            state.activeOrder = data;
            getOrderPercentage(state.activeOrder);
        }
    }
};

const actions = {
    markPaid({commit, state}, data){
        store.dispatch('order/updateOrder', data);
    },
    getOrder({commit, state}, data){
        getOrder(data).then(response => {
            state.activeOrder = response;
            if(state.activeOrder.paid){
                getOrderPercentage(state.activeOrder);
            }
        }).catch(e => {
            store.commit('app/ADD_ERROR', {text: 'Nie udało się pobrać twoich zamówienia'}, {root: true});
        })
    },
    getUserOrders({state, commit}, params){
        return new Promise((resolve, reject) => {
            getUserOrders().then(response => {
                if(typeof response == 'object'){
                    state.userOrders = Object.values(response);
                }else{
                    state.userOrders = response;
                }
                state.order_sync = true;
                console.log('ORDERS', state.userOrders);
                for(var i in state.userOrders){
                    if(i.paid){
                        getOrderPercentage(i);
                    }
                    Echo.channel('OrderChannel.'+i.id)
                        .listen('.order_updated', (e) => {
                            getOrder(e.order.hash).then(response => {
                                commit('UPDATE_ORDER', response);
                            })
                        });
                }

                resolve(response);
            }).catch(e => {
                reject(e);
                store.commit('app/ADD_ERROR', {text: 'Nie udało się pobrać twoich zamówień'}, {root: true});

            })
        })
    },
    updateOrder({state, commit}, data){
        return new Promise((resolve, reject) => {
            updateOrder(data.id, data).then(response => {
                commit('UPDATE_ORDER', response);
                resolve(response);
            }).catch(e => {reject(e)})
        })
    },
    makeOrder({commit}, order){
        return new Promise((resolve, reject) => {
            storeOrder(order).then(response => {
                resolve(response);
            }).catch(e => {
                store.commit('app/ADD_ERROR', {text: 'Nie udało się utworzyć zamówienia'}, {root: true});
                reject(e);
            })
        })
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
