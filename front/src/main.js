var _ = require('lodash');
require('./bootstrap');
import Vue from 'vue'
import App from './App.vue'
import InfiniteLoading from 'vue-infinite-loading';
import router from './router'
import config from './config';
import './permission'; // permission control
import store from './store';
import './utilis/request';
import './utilis/auth';
import vuetify from './plugins/vuetify';
import './registerServiceWorker'
import * as VueGoogleMaps from "vue2-google-maps";
import secrete from './secrete';
import prototypes from './prototypes';
import VueQRCodeComponent from 'vue-qr-generator'
import VueQrcodeReader from "vue-qrcode-reader";
Vue.use(VueQrcodeReader);

import VueApexCharts from 'vue-apexcharts'
import {i18n} from "./plugins/i18n";

Vue.component('apexchart', VueApexCharts)
Vue.component('qr-code', VueQRCodeComponent);
Vue.use(prototypes);
Vue.use(InfiniteLoading, { /* options */ });

Vue.use(VueGoogleMaps, {
  load: {
    key: secrete.google,
    libraries: "places" // necessary for places input
  }
});
Vue.config.productionTip = false
Vue.prototype.$eventBus = new Vue();
Vue.filter('currency', function (value) {
  return parseFloat(value).toFixed(2);
});
Vue.filter('truncate', function (text, stop, clamp) {
  if(!text) return '';
  return text.slice(0, stop) + (stop < text.length ? clamp || '...' : '')
})
Vue.mixin({
  data(){
    return{
      errors:[],
    }
  },
  methods:{
    getSrc(src){
      return this.$root.getSrc(src);
    },
    resetErrors(){
      setTimeout(() => {
        this.errors = [];
      }, 3000)
    },
    startLoading(){
      this.$store.commit('app/setLoading', true);
    },
    stopLoading(){
      setTimeout(() => {
        this.$store.commit('app/setLoading', false);
      }, 1000);
    },
    getStatusColor(status){
      if(status == 'new') return 'primary'
      if(status == 'completed') return 'green';
      if(status == 'canceled') return 'red';
    }
  }
})

new Vue({
  store,
  router,
  vuetify,
  i18n,
  data(){
    return{
      base_url: null
    }
  },
  mounted(){
    this.base_url = config.config.base_url;
  },
  methods:{
    isMobile(){
      if(/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) return true
      else return false;
    },
    getSrc(src){
      return config.storage+''+src;
    }
  },
  render: h => h(App)
}).$mount('#app')
