import Request from '../utilis/request';
import config from  '../config';
export function getCategories(params) {
    return Request({
        url:config.config.base_url+'/api/categories',
        method:'get',
        params: params
    })
}

export function deleteMassive(data) {
    return Request({
        url:config.config.base_url+'/api/categories',
        method:'DELETE',
        data: data
    })
}
export function editCategory(id,data){
    return Request({
        url:config.config.base_url+'/api/categories/'+id,
        method:'PUT',
        data: data
    })
}

export function storeCategory(data){
    return Request({
        url:config.config.base_url+'/api/categories',
        method:'POST',
        data: data
    })
}