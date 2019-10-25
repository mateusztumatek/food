import Confirm from "./components/confirm";

export default {
    install: (Vue, options) => {
        Vue.prototype.$confirm = (title, message) => {
            return new Promise((resolve, reject) => {
                const dialog = new Vue({
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
        }
    }
}
