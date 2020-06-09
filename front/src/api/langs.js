import Request from '../utilis/request';
import config from  '../config';
export function getLangs(params) {
    return Request({
        url:config.config.base_url+'/api/langs',
        method:'get',
        params: params
    })
}

