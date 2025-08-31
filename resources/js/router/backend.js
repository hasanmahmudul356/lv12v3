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
            {
                path: 'meter_type', name: 'meter_type',
                component: () => import('@/views/setting/meterType.vue'),
                meta: {dataUrl: 'api/meter_type', title: 'Meter Type'}
            },
            {
                path: 'customer_information', name: 'customer_information',
                component: () => import('@/views/pages/customer_management/customerInformation.vue'),
                meta: {dataUrl: 'api/customer_information', title: 'Customer Information'}
            },
            {
                path: 'bill_information', name: 'bill_information',
                component: () => import('@/views/pages/customer_management/billInformation.vue'),
                meta: {dataUrl: 'api/bill_information', title: 'Bill Information'}
            },
<<<<<<< HEAD

=======
>>>>>>> mehedi
            {
                path: 'meter_reading', name: 'meter_reading',
                component: () => import('@/views/pages/meterManagement/meterReading.vue'),
                meta: {dataUrl: 'api/meter_reading', title: 'Meter Reading'}
            },
            {
                path: 'bulk_bill_generation', name: 'bulk_bill_generation',
                component: () => import('@/views/pages/bill_generation/bulkBillGeneration.vue'),
                meta: {dataUrl: 'api/bulk_bill_generation', title: 'Bulk BIll Generation'}
            },
            {
                path: 'manual_bill_entry', name: 'manual_bill_entry',
                component: () => import('@/views/pages/bill_generation/manualBillEntry.vue'),
                meta: {dataUrl: 'api/manual_bill_entry', title: 'Manual BIll Entry'}
            },
        ]
    }
];

