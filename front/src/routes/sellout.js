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
            path: '/sellout/:id/settings',
            name: 'Manage sellout',
            component: () => import('@/views/sellout/create'),
            meta: {title: 'Edytuj sprzedaż', auth: true, attempting: true}
        },
        {
            path: '/sellout/:id',
            name: 'Show Sellout',
            component: () => import('@/views/sellout/show'),
            meta: {title: 'Sprzedaż', header_visible: true, getCart: 'id', fullWidth: true}
        },
        {
            path: '/sellout/:sale/category/:category',
            name: 'Show Sellout category',
            component: () => import('@/views/sellout/category'),
            meta: {title: 'Kategoria', header_visible: true, getCart: 'sale' , fullWidth: true}
        },
        {
            path: '/sellout/:sale/process',
            name: 'Process sellout cart',
            component: () => import('@/views/sellout/process'),
            meta: {title: 'Zamów', header_visible: true, getCart: 'sale'}
        },


    ]
}

export default sellout;

