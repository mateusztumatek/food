import Request from '../utilis/request';
import config from  '../config';
export function storeOrder(data){
    return Request({
        url:config.config.base_url+'/orders',
        method:'post',
        data: data
    })
}
export function getUserOrders(params){
    return Request({
        url:config.config.base_url+'/orders',
        method:'get',
        params: params
    })
}
export function getOrder(hash){
    return Request({
        url:config.config.base_url+'/orders/'+hash,
        method:'get',
    })
}

export function updateOrder(id, data){
    return Request({
        url:config.config.base_url+'/orders/'+id,
        method: 'PUT',
        data: data
    })
}
export function getSelloutOrders(sale_id, params) {
    return Request({
        url: config.config.base_url + '/orders/' + sale_id + '/sale',
        method: 'get',
        params: params
    })
}