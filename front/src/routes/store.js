import Layout from '@/AppMain.vue';

const store = {
    path: '/place',
    name: 'Place',
    component: Layout,
    children:[{
        path: '/',
        name: 'Place',
        component: () => import('@/views/place/info'),
        meta: {title: 'Edit user', auth: true}
       },
        {
            path: '/place/create',
            name: 'Place create',
            component: () => import('@/views/place/create'),
            meta: {title: 'Create place', auth: true}
        },
    ]
};
export default store;


