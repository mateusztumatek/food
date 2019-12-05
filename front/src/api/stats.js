import Request from '../utilis/request';
import config from "../config";
export function getStats(params) {
    return Request({
        url: config.config.base_url+'/api/stats',
        method: 'get',
        params: params
    })
}
export function getStatsCharts(params) {
    return Request({
        url: config.config.base_url+'/api/stats/charts',
        method: 'get',
        params: params
    })
}
export function getProductsChart(params) {
    return Request({
        url: config.config.base_url+'/api/stats/products_chart',
        method: 'get',
        params: params
    })
}
export function storeStats(data) {
    return Request({
        url: config.config.base_url+'/api/stats',
        method: 'post',
        data: data
    })
}

export function getClients(params){
    return Request({
        url: config.config.base_url+'/api/stats/clients',
        method: 'get',
        params: params
    })
}