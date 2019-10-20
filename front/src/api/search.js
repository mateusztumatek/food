import Request from '../utilis/request';
import config from "../config";
export function search(term) {
    return Request({
        url: config.config.base_url+'/api/search',
        method: 'get',
        params: {term: term}
    })
}