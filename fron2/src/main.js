import Vue from 'vue'
import App from './App.vue'
import router from './router'
import Ionic from '@ionic/vue';
import '@ionic/core/css/ionic.bundle.css';
import config from "../../front/src/config";
import store from './store'
Vue.use(Ionic);
Vue.config.productionTip = false;
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
    }
  }
})

new Vue({
  router,

  methods:{
    isMobile(){
      if(/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) return true
      else return false;
    },
    getSrc(src){
      return config.storage+''+src;
    }
  },

  store,
  render: h => h(App)
}).$mount('#app')
