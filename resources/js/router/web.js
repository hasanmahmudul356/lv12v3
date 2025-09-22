export const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/web/Home.vue')
    },
    {
        path: '/about',
        name: 'about',
        component: () => import('@/web/Profile.vue')
    }
];