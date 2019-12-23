import Layout from '@/AppMain.vue';

const sellout = {
    path: '/codes',
    name: 'Kody zniżkowe',
    component: Layout,
    children:[{
        path: '/codes',
        name: 'Kody zniżkowe',
        component: () => import('@/views/codes/index.vue'),
        meta: {title: 'Kody zniżkowe'}
    },
        {
            path: '/codes/create',
            name: 'Kody zniżkowe',
            component: () => import('@/views/codes/edit-add.vue'),
            meta: {title: 'Kody zniżkowe'}
        },
        {
            path: '/codes/:id/edit',
            name: 'Edytuj Kod zniżkowy',
            component: () => import('@/views/codes/edit-add.vue'),
            meta: {title: 'Edytuj Kod zniżkowy'}
        },
    ]
}

export default sellout;

