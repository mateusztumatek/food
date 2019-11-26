const env = 'local';
const config = {
    base_url: (env == 'local')? 'http://192.168.0.10:9000' : 'http://foodapi.yaxint.nazwa.pl'
}

const storage = config.base_url+'/storage/';
export default {
    storage, config
};