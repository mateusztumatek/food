import Confirm from "./components/confirm";
import MediaPicker from "./components/media-picker.vue";
import vuetify from "./plugins/vuetify";
import {i18n} from "./plugins/i18n";
import store from './store';
import config from "./config";
export default {
    install: (Vue, options) => {
        Vue.prototype.$confirm = (title, message) => {
            return new Promise((resolve, reject) => {
                const dialog = new Vue({
                    vuetify,
                    methods: {
                        closeHandler(fn, arg) {
                            return function() {
                                fn(arg);
                                dialog.$destroy();
                                dialog.$el.remove();
                            };
                        }
                    },
                    render(h) {
                        return h(Confirm, {
                            props: {
                                title,
                                message,
                            },
                            on: {
                                confirm: this.closeHandler(resolve),
                                cancel: this.closeHandler(reject, new Error('canceled'))
                            }
                        });
                    }
                }).$mount();
                document.body.appendChild(dialog.$el);
            })
        };
        Vue.prototype.$mediaPicker = () => {
            return new Promise((resolve, reject) => {
                const mediaPicker = new Vue({
                    vuetify,
                    i18n,
                    store,
                    data(){
                        return{
                            base_url: null,
                        }
                    },
                    mounted(){
                        this.base_url = config.config.base_url;
                    },
                    methods: {
                        closeHandler(fn, arg) {
                            return function() {
                                setTimeout(() => {
                                    fn(arg);
                                    mediaPicker.$destroy();
                                    mediaPicker.$el.remove();
                                }, 200)
                            };
                        },
                        selectItem(data){
                            setTimeout(() => {
                                resolve(data);
                                mediaPicker.$destroy();
                                mediaPicker.$el.remove();
                            }, 200)
                        }
                    },
                    render(h){
                        return h(MediaPicker, {
                            on: {
                                closeMe: this.closeHandler(reject),
                                select: this.selectItem
                            }
                        })
                    }
                }).$mount();
                var app = document.getElementsByClassName('v-application--wrap')[0];
                app.appendChild(mediaPicker.$el);
            })
        }
    }
}
