import router from './router';
import store from './store';
import {getToken, removeToken} from './utilis/auth';
const attempt = (to) => {
    if (to.meta.attempting == true) {
        store.dispatch('sellout/attempt', to.params.id);
    }else{
        store.commit('sellout/STOP_ATTEMPT');
    }
}
router.beforeEach(async (to, from, next) => {
    if(!store.getters.app.langs){
        store.dispatch('app/getLangs');
    }
    console.log(store.getters.notifications);
    if(store.getters.notifications == null){
        store.dispatch('notifications/getNotifications');
    }
    store.dispatch('user/checkLocation');
    next();
})
router.beforeEach(async(to, from, next) => {
    // start progress bar
    // set page title
    if(to.meta.title){
        document.title =to.meta.title;
    }
    if(!store.getters.orders_sync){
        store.dispatch('order/getUserOrders');
    }
    if(to.meta.getCart){
        if(store.getters.cart == null || store.getters.cart.sale_id != to.params[to.meta.getCart]){
            store.dispatch('cart/getSaleCart', to.params[to.meta.getCart]);
        }
    }else{
        store.commit('cart/RESET_CART');
    }
    store.commit('app/setLoading', true);
    // determine whether the user has logged in
    const hasToken = getToken();
    if (hasToken) {
        if (to.path === '/login') {
            // if is logged in, redirect to the home page
            next({ path: '/' });
        } else {
            if(!store.getters.user.id){
                store.dispatch('user/getUser').then(response => {
                    if(!store.getters.user.email_verified_at){
                        if(to.path !== '/verify'){
                            next('/verify');
                        }
                    }
                    if(to.path == '/verify') next('/');
                    attempt(to);
                    next();
                });
            }else{
                attempt(to);
                next();
            }
        }
    } else {
        if (to.meta.auth == true) {
            // in the free login whitelist, go directly
            next({path: '/login'});
        } else {
            next();
        }
    }
});

router.afterEach((to) => {
    setTimeout(() => {
        store.commit('app/setLoading', false);
    }, 500)
    if(to.meta.header_visible){
        store.commit('navigation/SET_HEADER', {title: ''});
    }else store.commit('navigation/RESET_HEADER');

})