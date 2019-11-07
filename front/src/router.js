import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import Layout from '@/AppMain.vue';
import store from './store';
import LoginLayout from '@/AppLogin.vue';
Vue.use(Router);

import StoreRoutes from '@/routes/store.js';
import ProductRoutes from '@/routes/products.js'
import CategoryRoutes from '@/routes/categories';
import SelloutRoutes from '@/routes/sellout';
import Localization from '@/routes/localization';
export const my_routes =
  [
    {
      path: '/',
      name: 'home',
      component: Layout,
      children: [
        {
          path: '/',
          component: () => import('@/views/Home.vue'),
          meta:{auth:true}
        },
      ],
    },
    {
      path: '/login',
      redirect: '',
      component: LoginLayout,
      children:[
        {
          path:'',
          name: 'login',
          component:() => import('@/views/login/login.vue'),
          meta: { title: 'Login', icon: 'login', noCache: false, guest: true },
        }
      ]
    },
    {
      path: '/register',
      redirect: '',
      component: LoginLayout,
      children:[
        {
          path:'',
          name: 'register',
          component:() => import('@/views/login/register.vue'),
          meta: { title: 'Register', icon: 'register', noCache: false, guest: true },
        }
      ]
    },
    {
      path: '/verify',
      redirect: '',
      component: Layout,
      children:[
        {
          path:'',
          name: 'verify',
          component:() => import('@/views/login/verify.vue'),
          meta: {title: 'Verify'}
        }
      ],
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "about" */ './views/About.vue')
    },
    {
      path: '/user',
      name: 'user',
      component: Layout,
      children:[{
        path: 'edit',
        name: 'edit',
        component: () => import('@/views/account/edit'),
        meta: {title: 'Edit user', auth: true}
      }
      ]
    },
    {
      path: '/wishlist',
      name: 'wishlist',
      component: Layout,
      children:[{
        path: '',
        component: () => import('@/views/account/wishlist'),
        meta: {title: 'Lista życzeń', auth: true}
      }
      ]
    },
    {
      path: '/qr',
      name: 'qr',
      component: Layout,
      children:[
        {
          path: '/',
          component: () => import('@/views/qr/index'),
          meta: {title: 'Skanuj kod QR'}
        }
      ]
    },
    StoreRoutes,
    ProductRoutes,
    CategoryRoutes,
    SelloutRoutes,
    Localization,
    {
      path: '*',
      name: '404',
      redirect: '404',
      component: LoginLayout,
      children:[
        {
          path:'/404',
          component:() => import('@/views/errors/404.vue'),
        }
      ]
    },

  ];
const createRouter = () => new Router({
   mode: 'history',
  scrollBehavior: () => ({ y: 0 }),
  routes: my_routes,
});

const router = createRouter();
router.beforeEach((from, to, next) => {
  var title = from.name;
  if(to.meta.title){
    title = from.meta.title
  }
  store.dispatch('navigation/setTitle', title);
  next();
})
export default router;