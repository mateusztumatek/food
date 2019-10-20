import Vue from 'vue'
import App from './App.vue'
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
Vue.use(VueGoogleMaps, {
  load: {
    key: secrete.google,
    libraries: "places" // necessary for places input
  }
});
Vue.config.productionTip = false
Vue.prototype.$eventBus = new Vue();

Vue.mixin({
  data(){
    return{
      errors:[],
    }
  },
  methods:{
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
    }
  }
})
new Vue({
  store,
  router,
  vuetify,
  mounted(){
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
