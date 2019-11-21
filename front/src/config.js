const env = 'local';
const config = {
    base_url: (env == 'local')? 'http://192.168.1.4:9000' : 'http://foodapi.yaxint.nazwa.pl'
}

console.log('CONFIG', config);
const storage = config.base_url+'/storage/';
export default {
    storage, config
};