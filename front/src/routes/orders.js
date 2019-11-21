import Layout from '@/AppMain.vue';

const orders = {
    path: '/orders',
    name: 'Orders',
    component: Layout,
    children:[{
        path: '/orders/:hash',
        name: 'Order',
        component: () => import('@/views/orders/show'),
        meta: {title: 'Order'}
    },

    ]
};
export default orders;


