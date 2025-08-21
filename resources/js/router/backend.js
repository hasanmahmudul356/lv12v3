export const backend = [
    {
        path: '/', name: 'app',
        component: () => import('@/views/layouts/AppLayouts.vue'),
        children: [
            {
                path: '', name: 'dashboard',
                component: () => import('@/views/pages/Dashboard.vue')
            },
            {
                path: 'profile', name: 'profile',
                component: () => import('@/views/pages/users/profile.vue'),
                meta: {dataUrl: 'api/profile', title: 'Profile', listPage: false}
            },
            {
                path: 'dashboard', name: 'dashboard-alt',
                component: () => import('@/views/pages/Dashboard.vue')
            },
            {
                path: 'users', name: 'users',
                component: () => import('@/views/pages/users/userList.vue'),
                meta: {dataUrl: 'api/users', title: 'Users'}
            },
            {
                path: 'modules', name: 'modules',
                component: () => import('@/views/pages/rbac/Module.vue'),
                meta: {dataUrl: 'api/modules', title: 'Module'}
            },
            {
                path: 'roles', name: 'role',
                component: () => import('@/views/pages/rbac/Module.vue'),
                meta: {dataUrl: 'api/modules', title: 'Module'}
            },
            {
                path: 'module_permissions', name: 'module_permission',
                component: () => import('@/views/pages/rbac/Module.vue'),
                meta: {dataUrl: 'api/module_permissions', title: 'Module'}
            },
            {
                path: 'role_permissions', name: 'role_permission',
                component: () => import('@/views/pages/rbac/Module.vue'),
                meta: {dataUrl: 'api/role_permissions', title: 'Module'}
            },
        ]
    }
];

