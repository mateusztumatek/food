import Layout from '@/AppMain.vue';


const categories = {
    path: '/categories',
    name: 'Categories',
    component: Layout,
    children:[{
        path: '/categories',
        name: 'Categories',
        component: () => import('@/views/categories/index'),
        meta: {title: 'Kategorie', auth: true}
    }]
}

export default categories;

