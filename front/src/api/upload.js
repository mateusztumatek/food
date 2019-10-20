import Request from '../utilis/request';
import config from "../config";
export function upload(files, path) {
    return Request({
        url: config.config.base_url+'/api/upload/'+path,
        method: 'post',
        data: files
    })
}