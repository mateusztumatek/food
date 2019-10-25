import router from './router';
import store from './store';
import {getToken, removeToken} from './utilis/auth';
router.beforeEach(async(to, from, next) => {
    // start progress bar
    // set page title
    if(to.meta.title){
        document.title =to.meta.title;
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
                    next();
                });

            }else{
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
router.afterEach(() => {
    setTimeout(() => {
        store.commit('app/setLoading', false);
    }, 500)

})