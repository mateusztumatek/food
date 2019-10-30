import Request from '../utilis/request';
import config from  '../config';
export function getUser(){
    return Request({
        url: config.config.base_url+'/api/user',
        method: 'get',
    })
}
export function exampleRequest() {
    return Request({
        url:config.config.base_url+'/api/examples',
        method:'get'
    })
}
export function login(data) {
    return Request({
        url: config.config.base_url+'/api/login',
        method: 'post',
        data: data
    })
}

export function logout() {
    return Request({
        url: config.config.base_url+'/api/logout',
        method: 'post',
    })
}

export function register(data) {
    return Request({
        url: config.config.base_url+'/api/register',
        method: 'post',
        data: data
    })
}

export function resend() {
    return Request({
        url: config.config.base_url+'/api/email/resend',
        method: 'POST',
    })
}

export function update(data, id) {
    return Request({
        url: config.config.base_url+'/api/users/'+id,
        method: 'PUT',
        data: data
    })
}