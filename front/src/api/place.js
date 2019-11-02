import Request from '../utilis/request';
import config from  '../config';
export function storePlace(data) {
    return Request({
        url:config.config.base_url+'/api/places',
        method:'post',
        data: data
    })
}
export function getPlace(id) {
    return Request({
        url: config.config.base_url+'/api/places/'+id,
        method: 'get',
    })
}
export function getPlaces(params) {
    return Request({
        url: config.config.base_url+'/api/places',
        method: 'get',
        params: params
    })
}
export function updatePlace(id, data) {
    return Request({
        url:config.config.base_url+'/api/places/'+id,
        method:'put',
        data: data
    })
}
export function deletePlace(id){
    return Request({
        url:config.config.base_url+'/api/places/'+id,
        method: 'delete',
    })
}