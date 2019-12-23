import Request from '../utilis/request';
import config from  '../config';

export function getNotifications(params){
    return Request({
        url:config.config.base_url+'/notifications',
        method:'get',
        params: params
    })
}
