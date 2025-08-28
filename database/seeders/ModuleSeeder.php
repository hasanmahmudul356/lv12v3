<?php

namespace Database\Seeders;

use App\Models\RBAC\Permission;
use App\Models\RBAC\RoleModules;
use App\Models\RBAC\RolePermission;
use Illuminate\Database\Seeder;
use App\Models\RBAC\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::truncate();
        Permission::truncate();
        RolePermission::truncate();
        RoleModules::truncate();

        $resourcePermissions = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'status'];

        $modules = [
            [
                'display_name' => 'Dashboard',
                'name' => 'dashboard',
                'link' => '/dashboard',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-home-alt',
            ],
            [
                'display_name' => 'RBAC Accesses',
                'name' => 'accesses',
                'link' => '#',
                'permissions' => ['show'],
                'icon' => 'bx bx-lock',
                'submenus' => [
                    [
                        'display_name' => 'Users',
                        'name' => 'users',
                        'link' => '/users',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-user-circle',
                        'submenus' => []
                    ],
                    [
                        'display_name' => 'Modules',
                        'name' => 'modules',
                        'link' => '/modules',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-radio-circle',
                    ],
                    [
                        'display_name' => 'Roles',
                        'name' => 'roles',
                        'link' => '/roles',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-radio-circle',
                    ],
                    [
                        'display_name' => 'Module Permissions',
                        'name' => 'module_permissions',
                        'link' => '/module_permissions',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-radio-circle',
                    ],
                    [
                        'display_name' => 'Role Permissions',
                        'name' => 'role_permissions',
                        'link' => '/role_permissions',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-radio-circle',
                    ],
                ]
            ],
            [
                'display_name' => 'Customer Management',
                'name' => 'customer_management',
                'link' => '#',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'fa-user-shield',
                'submenus'=>[
                    [
                        'display_name' => 'Bill Information',
                        'name' => 'bill_information',
                        'link' => '/bill_information',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Customer Information',
                        'name' => 'customer_information',
                        'link' => '/customer_information',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                ]
            ],
            [
                'display_name' => 'Meter Management',
                'name' => 'Meter_management',
                'link' => '#',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'fa-tachometer-alt',
                'submenus'=>[
                    [
                        'display_name' => 'Add Meter',
                        'name' => 'add_meter',
                        'link' => '/add_meter',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Meter Reading',
                        'name' => 'meter_reading',
                        'link' => '/meter_reading',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Meter History',
                        'name' => 'meter_history',
                        'link' => '/meter_history',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                ]
            ],
            [
                'display_name' => 'Bill Generation',
                'name' => 'bill_generation',
                'link' => '#',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'fa-file-invoice-dollar',
                'submenus'=>[
                    [
                        'display_name' => 'Bill Entry',
                        'name' => 'bill_entry',
                        'link' => '/bill_entry',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Bulk Bill Generation',
                        'name' => 'bulk_bill_generation',
                        'link' => '/bulk_bill_generation',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                ]

            ],
            [
                'display_name' => 'Payment Management',
                'name' => 'payment_management',
                'link' => '#',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'fa-money-check-alt',
                'submenus'=>[
                    [
                        'display_name' => 'Record Payment',
                        'name' => 'record_payment',
                        'link' => '/record_payment',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Payment History',
                        'name' => 'payment_history',
                        'link' => '/payment_history',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Generate Receipt',
                        'name' => 'generate_receipt',
                        'link' => '/generate_receipt',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                ]
            ],
            [
                'display_name' => 'Tariff & Rate',
                'name' => 'tariff_rate',
                'link' => '/tariff_rate',
                'permissions' => array_merge($resourcePermissions, []),
                'icon' => 'fa-calculator'
            ],
            [
                'display_name' => 'Due & Penalty',
                'name' => 'due_penalty',
                'link' => '#',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'fa-exclamation-circle',
                'submenus'=>[
                    [
                        'display_name' => 'Overdue Bills',
                        'name' => 'overdue_bills',
                        'link' => '/overdue_bills',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Penalty Calculation',
                        'name' => 'penalty_calculation',
                        'link' => '/penalty_calculation',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Customer Notifications',
                        'name' => 'customer_notifications',
                        'link' => '/customer_notifications',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                ]
            ],
            [
                'display_name' => 'Reports & Analytics',
                'name' => 'Reports_analytics',
                'link' => '#',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'fa-file-invoice',
                'submenus'=>[
                    [
                        'display_name' => 'Billing Reports',
                        'name' => 'billing_reports',
                        'link' => '/billing_reports',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Collection Reports',
                        'name' => 'collection_reports',
                        'link' => '/collection_reports',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Consumption Analysis',
                        'name' => 'consumption_analysis',
                        'link' => '/consumption_analysis',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                ]
            ],
            [
                'display_name' => 'Settings',
                'name' => 'settings',
                'link' => '#',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'fa-tools',
                'submenus'=>[
                    [
                        'display_name' => 'Meter Type',
                        'name' => 'meter_type',
                        'link' => '/meter_type',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Customer Area',
                        'name' => 'customer_area',
                        'link' => '/customer_area',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Solar Plant',
                        'name' => 'solar_plant',
                        'link' => '/solar_plant',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Generator',
                        'name' => 'generator',
                        'link' => '/generator',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Notification Settings',
                        'name' => 'notification_settings',
                        'link' => '/notification_settings',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Backup & Restore',
                        'name' => 'backup_restore',
                        'link' => '/backup_restore',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                ]
            ],
            [
                'display_name' => 'Help & Support',
                'name' => 'help_support',
                'link' => '#',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'fa-question-circle',
                'submenus'=>[
                    [
                        'display_name' => 'User Manual',
                        'name' => 'user_manual',
                        'link' => '/user_manual',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Contact Support',
                        'name' => 'contact_support',
                        'link' => '/contact_support',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                    [
                        'display_name' => 'Generate Receipt',
                        'name' => 'generate_receipt',
                        'link' => '/generate_receipt',
                        'permissions' => array_merge($resourcePermissions, []),
                    ],
                ]
            ],

        ];

        foreach ($modules as $data) {
            $submenus = isset($data['submenus']) && count($data['submenus']) > 0 ? $data['submenus'] : [];
            $permissions = $data['permissions'];
            unset($data['submenus']);
            unset($data['permissions']);

            $parentModule = $this->insertModule($data, 0);
            $this->insertRoleModule($parentModule->id, 1);


            foreach ($permissions as $permission) {
                $new_permission = $this->insertPermission($parentModule->name, $permission, $parentModule->display_name, $parentModule->id);
                $this->insertRolePermission($new_permission->id, 1);
            }

            if (count($submenus) > 0) {
                foreach ($submenus as $submenu) {
                    $subSubMenus = isset($submenu['submenus']) && count($submenu['submenus']) > 0 ? $submenu['submenus'] : [];

                    $permissions = $submenu['permissions'];
                    unset($submenu['permissions']);
                    unset($submenu['submenus']);

                    $module = $this->insertModule($submenu, $parentModule->id, 1);
                    $subParent = $module;

                    $this->insertRoleModule($module->id, 1);

                    foreach ($permissions as $permission) {
                        $new_permission = $this->insertPermission($module->name, $permission, $module->display_name, $module->id);
                        $this->insertRolePermission($new_permission->id, 1);
                    }

                    if (count($subSubMenus) > 0) {
                        foreach ($subSubMenus as $subSubMenu) {
                            $permissions = $subSubMenu['permissions'];
                            unset($subSubMenu['permissions']);
                            unset($subSubMenu['submenus']);

                            $module = $this->insertModule($subSubMenu, $subParent->id);
                            $this->insertRoleModule($module->id, 1);


                            foreach ($permissions as $permission) {
                                $new_permission = $this->insertPermission($module->name, $permission, $module->display_name, $module->id);
                                $this->insertRolePermission($new_permission->id, 1);
                            }
                        }
                    }
                }
            }
        }
    }

    public function insertModule($data, $parent_id = 0)
    {
        $module = new Module();
        $module->fill($data);
        $module->parent_id = $parent_id;
        $module->save();

        return $module;
    }

    public function insertRoleModule($module_id, $role_id = 1)
    {
        $role_module = new RoleModules();
        $role_module->role_id = $role_id;
        $role_module->module_id = $module_id;
        $role_module->save();

        return $role_module;
    }

    public function insertPermission($name, $permission, $display_name, $module_id)
    {
        $new_name = $name . '.' . $permission;
        $new_permission = new Permission();
        $new_permission->module_id = $module_id;
        $new_permission->name = $new_name;
        $new_permission->display_name = $display_name . ' ' . str_replace('_', ' ', $permission);
        $new_permission->save();

        return $new_permission;
    }

    public function insertRolePermission($permission_id, $role_id = 1)
    {
        $role_module = new RolePermission();
        $role_module->role_id = $role_id;
        $role_module->permission_id = $permission_id;
        $role_module->save();

        return $role_module;
    }
}
