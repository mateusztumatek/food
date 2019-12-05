import Request from '../utilis/request';
import config from  '../config';
export function getCodes(params) {
    return Request({
        url:config.config.base_url+'/api/codes',
        method:'get',
        params: params
    })
}
