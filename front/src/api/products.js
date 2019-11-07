import Request from '../utilis/request';
import config from  '../config';
export function getUserProducts(id, params) {
    return Request({
        url:config.config.base_url+'/api/products?user_id='+id,
        method:'GET',
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