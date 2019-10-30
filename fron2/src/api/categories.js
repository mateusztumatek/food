import Request from '../utilis/request';
import config from  '../config';
export function getCategories(params) {
    return Request({
        url:config.config.base_url+'/api/categories',
        method:'get',
        params: params
    })
}
