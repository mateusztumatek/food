import Layout from '@/AppMain.vue';
import router from '@/router';

const sellout = {
    path: '/sellout',
    name: 'Sellout',
    component: Layout,
    children:[{
        path: '/sellout/create',
        name: 'Sellout',
        component: () => import('@/views/sellout/create'),
        meta: {title: 'Sprzedaż', auth: true}
    },
        {
            path: '/sellout/:id/manage',
            name: 'Manage sellout',
            component: () => import('@/views/sellout/admin/manage'),
            meta: {title: 'Zarządzaj sprzedażą', auth: true, attempting: true}
        },
        {
            path: '/sellout/:id',
            name: 'Manage sellout',
            component: () => import('@/views/sellout/show'),
            meta: {title: 'Sprzedaż'}
        },

    ]
}

export default sellout;

