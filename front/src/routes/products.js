import Layout from '@/AppMain.vue';

const products = {
    path: '/products',
    name: 'Products',
    component: Layout,
    children:[{
        path: '/',
        name: 'Products',
        component: () => import('@/views/products/index.vue'),
        meta: {title: 'Products', auth: true}
    },
    ]
};
export default products;


