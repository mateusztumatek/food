import Layout from '@/AppMain.vue';

const stats = {
    path: '/stats',
    name: 'Place',
    component: Layout,
    children:[{
        path: '/stats',
        name: 'Stats',
        component: () => import('@/views/stats/index'),
        meta: {title: 'Stats', auth: true}
        }
    ]
};
export default stats;


