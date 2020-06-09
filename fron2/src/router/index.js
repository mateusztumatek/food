import Vue from 'vue'
import Router from 'vue-router'
import Home from '../views/Home.vue'
import Layout from '../views/Layout';
import store from "../store";

Vue.use(Router);

const routes = [
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
    path: '/about',
    name: 'home',
    component: Layout,
    children: [
      {
        path: '/',
        component: () => import('@/views/About.vue'),
        meta:{auth:true}
      },
    ],
  },
]

const router = new Router({
  mode: 'history',
  routes
})

export default router
