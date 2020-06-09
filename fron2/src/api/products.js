import Request from '../utilis/request';
import config from  '../config';
export function getUserProducts(id) {
    return Request({
        url:config.config.base_url+'/api/products?user_id='+id,
        method:'GET',
    })
}
export function storeProduct(data) {
    return Request({
        url:config.config.base_url+'/api/products',
        method: 'POST',
        data: data
    })
}