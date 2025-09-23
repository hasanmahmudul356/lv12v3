export const routes = [
    {
        path: "/",
        name: "app",
        component: () => import('@/web/layouts/webLayouts.vue'),
        children: [
            {
                path: '/dashboard',
                name: 'dashboard',
                component: () => import('@/web/Dashboard.vue')
            },
            {
                path: '/profile',
                name: 'profile',
                component: () => import('@/web/Profile.vue')
            },
            {
                path: '/complains',
                name: 'complains',
                component: () => import('@/web/Complains.vue')
            },
            {
                path: '/complain-add',
                name: 'complains_add',
                component: () => import('@/web/ComplainAdd.vue')
            }
        ]
    }

];