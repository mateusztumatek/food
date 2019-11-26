import Layout from '@/AppMain.vue';

const qrs = {
    path: '/qr',
    name: 'Products',
    component: Layout,
    children:[{
        path: '/generate_qr',
        name: 'Generate QR Code',
        component: () => import('@/views/qr/generate.vue'),
        meta: {title: 'Products', auth: true}
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
    ]
};
export default qrs;


