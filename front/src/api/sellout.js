import Request from '../utilis/request';
import config from "../config";
export function storeSellout(data) {
    return Request({
        url: config.config.base_url+'/api/sales',
        method: 'post',
        data: data
    })
}
export function getSellouts(params) {
    return Request({
        url: config.config.base_url+'/api/sales',
        method: 'get',
        params: params
    })
}
export function getSellout(id, params = null) {
    return Request({
        url: config.config.base_url+'/api/sales/'+id,
        method: 'get',
        params: params
    })
}
export function manageSellout(id) {
    return Request({
        url: config.config.base_url+'/api/sales/'+id+'/manage',
        method: 'GET',
    })
}

export function attemptSellout(id) {
    return Request({
        url: config.config.base_url+'/api/sales/'+id+'/attempt',
        method: 'GET',
    })
}