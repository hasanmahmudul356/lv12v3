export const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/views/layouts/AppLayouts.vue'),
        children: [
            {
                path: '',
                name: 'dashboard',
                component: () => import('@/views/pages/Dashboard.vue')
            },
        ]
    }
];