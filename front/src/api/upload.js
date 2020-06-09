import Request from '../utilis/request';
import config from "../config";
export function upload(files, path) {
    return Request({
        url: config.config.base_url+'/api/upload/'+path,
        method: 'post',
        data: files
    })
}
export function updateFile(data) {
    return Request({
        url: config.config.base_url+'/api/upload/update',
        method: 'post',
        data: data
    })
}
export function removeFile(data) {
    return Request({
        url: config.config.base_url+'/api/upload/remove',
        method: 'post',
        data: data
    })
}
export function getUserUploads(params){
    return Request({
        url: config.config.base_url+'/api/upload/getuploads',
        method: 'get',
        params: params
    })
}