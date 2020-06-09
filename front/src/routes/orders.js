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
        {
            path: '/orders',
            name: 'Orders',
            component: () => import('@/views/orders/shop_orders'),
            meta: {title: 'Shop orders', auth: true}
        }
    ]
};
export default orders;


