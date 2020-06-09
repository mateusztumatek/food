import Request from '../utilis/request';
import config from  '../config';
export function getCart(sale_id) {
    return Request({
        url:config.config.base_url+'/cart/'+sale_id,
        method:'get',
        headers:{
            withCredentials: true
        }
    })
}
export function addToCart(sale_id, data) {
    return Request({
        url:config.config.base_url+'/cart/'+sale_id,
        method:'post',
        data: data,
    })
}
export function deleteItem(sale_id, data) {
    return Request({
        url: config.config.base_url+'/cart/'+sale_id+'/delete',
        method: 'POST',
        data: data
    })
}
export function updateItem(sale_id, data) {
    return Request({
        url: config.config.base_url+'/cart/'+sale_id+'/update',
        method: 'POST',
        data: data
    })
}