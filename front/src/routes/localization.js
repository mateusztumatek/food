import Layout from '@/AppMain.vue';

const sellout = {
    path: '/localization',
    name: 'Localization',
    component: Layout,
    children:[{
        path: '/localization',
        name: 'Localization',
        component: () => import('@/views/localization/index'),
        meta: {title: 'Localization', auth: true}
    },
    ]
}

export default sellout;

