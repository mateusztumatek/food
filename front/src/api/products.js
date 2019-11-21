import Request from '../utilis/request';
import config from  '../config';
export function getUserProducts(id, params) {
    var params = {user_id: id, ...params};
    return Request({
        url:config.config.base_url+'/api/products',
        method:'GET',
        params: params
    })
}
export function getProduct(id, params) {
    return Request({
        url: config.config.base_url+'/api/products/'+id,
        method: 'GET',
        params: params
    })
}
export function storeProduct(data) {
    return Request({
        url:config.config.base_url+'/api/products',
        method: 'POST',
        data: data
    })
}
export function editProduct(id, data) {
    return Request({
        url:config.config.base_url+'/api/products/'+id,
        method: 'PUT',
        data: data
    })
}