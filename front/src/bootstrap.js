 import Echo from 'laravel-echo'
 import secrete from './secrete';
 import {getToken} from "./utilis/auth";
 import config from './config';
 window.Pusher = require('pusher-js');
 window.$ = window.jQuery = require('jquery');

 window.axios = require('axios');
 window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

 window.Echo = new Echo({
     broadcaster: 'pusher',
     key: secrete.PUSHER_API_KEY,
     cluster: secrete.PUSHER_APP_CLUSTER,
     authEndpoint: config.base_url+'/broadcasting/auth',
     auth: {
         headers: {
             'Authorization': 'Bearer ' + getToken(),
         }
     },
     forceTLS: true
 });
