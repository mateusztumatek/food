import router from '../../router';
import {updatePlace, storePlace, deletePlace} from '../../api/place';

const state = {
    places:[],
};
const actions = {
    updatePlace: ({commit}, data) => {
        return new Promise((resolve, reject) => {
            updatePlace(data.id, data).then(response => {
                resolve(response);
                commit('UPDATE_PLACE', response);
            }).catch(e => {
                reject(e);
            })
        })
    },
    storePlace: ({commit}, data) => {
        return new Promise((resolve, reject) => {
            storePlace(data).then(response => {
                resolve(response);
                commit('STORE_PLACE', response);
            }).catch(e => {
                reject(e);
            })
        })
    },
    deletePlace: ({commit}, data) => {
        return new Promise((resolve, reject) => {
            deletePlace(data.id).then(response => {
                commit('DELETE_PLACE', data.id);
                resolve(response);
            }).catch(e => {
                reject(e);
            })
        })
    }
}
const mutations = {
    UPDATE_PLACE: (state, place) => {
        const index = _.findIndex(state.places, ['id', place.id]);
        state.places[index] = place;
    },
    STORE_PLACE: (state, place) => {
        state.places.push(place)
    },
    SET_PLACES: (state, places) => {
        state.places = places;
    },
    DELETE_PLACE: (state, id) => {
        state.places.splice(_.findIndex(state.places, ['id', id]), 1);
    }
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
};
