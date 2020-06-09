import Layout from '@/AppMain.vue';

const store = {
    path: '/place',
    name: 'Place',
    component: Layout,
    children:[{
        path: '/',
        name: 'Place',
        component: () => import('@/views/place/info'),
        meta: {title: 'Places', auth: true}
       },
        {
            path: '/place/create',
            name: 'Place create',
            component: () => import('@/views/place/create'),
            meta: {title: 'Create place', auth: true}
        },
        {
            path: '/place/edit/:id',
            name: 'Place edit',
            component: () => import('@/views/place/create'),
            meta: {title: 'Edit place', auth: true}
        },
        {
            path: '/place/:slug',
            name: 'Place index',
            component: () => import('@/views/place/index/index'),
        }
    ]
};
export default store;


