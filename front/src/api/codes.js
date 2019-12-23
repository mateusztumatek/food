import Request from '../utilis/request';
import config from  '../config';
export function getCodes(params) {
    return Request({
        url: config.config.base_url + '/api/codes',
        method: 'get',
        params: params
    })
}
export function storeCode(data) {
    return Request({
        url:config.config.base_url+'/api/codes',
        method:'post',
        data: data
    })
}
export function updateCode(data, id) {
    return Request({
        url:config.config.base_url+'/api/codes/'+id,
        method:'put',
        data: data
    })
}

export function deleteCodes(data) {
    return Request({
        url:config.config.base_url+'/api/codes',
        method:'delete',
        data: data
    })
}

export function applyCode(data) {
    return Request({
        url: config.config.base_url+'/codes/apply',
        method:'post',
        data: data,
    })
}
export function sendToClient(data) {
    return Request({
        url: config.config.base_url+'/api/codes/send_client',
        method:'post',
        data: data,
    })
}
export function removeCartCode(data) {
    return Request({
        url: config.config.base_url+'/codes/remove',
        method:'post',
        data: data,
    })
}