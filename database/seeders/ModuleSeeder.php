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
                'name' => 'dashboard',
                'link' => '/dashboard',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-home-alt',
                "component" => "views/pages/Dashboard.vue",
                'meta' => [
                    "dataUrl" => "api/dashboard",
                ],
            ],
            [
                'name' => 'accesses',
                'link' => '',
                'permissions' => ['show'],
                'icon' => 'bx bx-lock',
                'meta' => [],
                'submenus' => [
                    [
                        'name' => 'users',
                        'link' => '/users',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-user-circle',
                        "component" => "views/pages/users/userList.vue",
                        'meta' => [
                            "dataUrl" => "api/users",
                        ],
                    ],
                    [
                        'name' => 'modules',
                        'link' => '/modules',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-radio-circle',
                        "component" => "views/pages/rbac/Module.vue",
                        'meta' => [
                            "dataUrl" => "api/modules",
                        ]
                    ],
                    [
                        'name' => 'roles',
                        'link' => '/roles',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-radio-circle',
                        "component" => "views/pages/rbac/Module.vue",
                        'meta' => [
                            "dataUrl" => "api/roles",
                        ]
                    ],
                    [
                        'name' => 'module_permissions',
                        'link' => '/module_permissions',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-radio-circle',
                        "component" => "views/pages/rbac/Module.vue",
                        'meta' => [
                            "dataUrl" => "api/module_permissions",
                        ]
                    ],
                    [
                        'name' => 'role_permissions',
                        'link' => '/role_permissions',
                        'permissions' => array_merge($resourcePermissions, []),
                        'icon' => 'bx bx-radio-circle',
                        "component" => "views/pages/rbac/Module.vue",
                        'meta' => [
                            "dataUrl" => "api/role_permissions",
                        ]
                    ],
                ]
            ],
            [
                'name' => 'customer_management',
                'link' => '',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-group',
                'submenus'=>[
                    [
                        'name' => 'bill_information',
                        'link' => '/bill_information',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/customerManagement/billInformation.vue",
                        'meta' => [
                            "dataUrl" => "api/bill_information",
                        ],
                    ],
                    [
                        'name' => 'customer_information',
                        'link' => '/customer_information',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/customerManagement/customerInformation.vue",
                        'meta' => [
                            "dataUrl" => "api/customer_information",
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Meter_management',
                'link' => '',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-tachometer',
                'submenus'=>[
                    [
                        'name' => 'add_meter',
                        'link' => '/add_meter',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/meterManagement/meterAdd.vue",
                        'meta' => [
                            "dataUrl" => "api/add_meter",
                        ],
                    ],
                    [
                        'name' => 'meter_reading',
                        'link' => '/meter_reading',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/meterManagement/meterReading.vue",
                        'meta' => [
                            "dataUrl" => "api/meter_reading",
                        ],
                    ],
                    [
                        'name' => 'meter_history',
                        'link' => '/meter_history',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                ]
            ],
            [
                'name' => 'bill_generation',
                'link' => '',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-receipt',
                'submenus'=>[
                    [
                        'name' => 'manual_bill_entry',
                        'link' => '/manual_bill_entry',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/billGeneration/manualBillEntry.vue",
                        'meta' => [
                            "dataUrl" => "api/manual_bill_entry",
                        ],
                    ],
                    [
                        'name' => 'bulk_bill_generation',
                        'link' => '/bulk_bill_generation',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/billGeneration/bulkBillGeneration.vue",
                        'meta' => [
                            "dataUrl" => "api/bulk_bill_generation",
                        ],
                    ],
                    [
                        'name' => 'invoice',
                        'link' => '/invoice',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/billGeneration/invoice.vue",
                        'meta' => [
                            "dataUrl" => "api/invoice",
                        ],
                    ],
                ]
            ],
            [
                'name' => 'payment_management',
                'link' => '',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-credit-card',
                'submenus'=>[
                    [
                        'name' => 'record_payment',
                        'link' => '/record_payment',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                    [
                        'name' => 'payment_history',
                        'link' => '/payment_history',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                    [
                        'name' => 'generate_receipt',
                        'link' => '/generate_receipt',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                ]
            ],
            [
                'name' => 'tariff_rate',
                'link' => '/tariff_rate',
                'permissions' => array_merge($resourcePermissions, []),
                "component" => "views/pages/tariffAndRate.vue",
                'meta' => [
                    "dataUrl" => "api/tariff_rate",
                ],
                'icon' => 'bx bx-calculator'
            ],
            [
                'name' => 'energy_bill',
                'link' => '/energy_bill',
                'permissions' => array_merge($resourcePermissions, []),
                "component" => "views/pages/energyBills/energyBill.vue",
                'meta' => [
                    "dataUrl" => "api/energy_bill",
                ],
                'icon' => 'bx bx-bolt-circle'
            ],
            [
                'name' => 'due_penalty',
                'link' => '',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-error',
                'submenus'=>[
                    [
                        'name' => 'overdue_bills',
                        'link' => '/overdue_bills',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/duePenaltyManagement/overdueBills.vue",
                        'meta' => [
                            "dataUrl" => "api/overdue_bills",
                        ],
                    ],
                    [
                        'name' => 'penalty_calculation',
                        'link' => '/penalty_calculation',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/duePenaltyManagement/penaltyCalculation.vue",
                        'meta' => [
                            "dataUrl" => "api/penalty_calculation",
                        ],
                    ],
                    [
                        'name' => 'customer_notifications',
                        'link' => '/customer_notifications',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/duePenaltyManagement/customerNotification.vue",
                        'meta' => [
                            "dataUrl" => "api/customer_notifications",
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Reports_analytics',
                'link' => '',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-bar-chart-alt-2',
                'submenus'=>[
                    [
                        'name' => 'billing_reports',
                        'link' => '/billing_reports',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/reportsAnalytics/billingReports.vue",
                        'meta' => [
                            "dataUrl" => "api/billing_reports",
                        ],
                    ],
                    [
                        'name' => 'collection_reports',
                        'link' => '/collection_reports',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/reportsAnalytics/collectionReport.vue",
                        'meta' => [
                            "dataUrl" => "api/collection_reports",
                        ],
                    ],
                    [
                        'name' => 'consumption_analysis',
                        'link' => '/consumption_analysis',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/reportsAnalytics/consumptionAnalysis.vue",
                        'meta' => [
                            "dataUrl" => "api/consumption_analysis",
                        ],
                    ],
                ]
            ],
            [
                'name' => 'settings',
                'link' => '',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-cog',
                'submenus'=>[
                    [
                        'name' => 'meter_type',
                        'link' => '/meter_type',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/setting/meterType.vue",
                        'meta' => [
                            "dataUrl" => "api/meter_type",
                        ],
                    ],
                    [
                        'name' => 'customer_area',
                        'link' => '/customer_area',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/setting/customerArea.vue",
                        'meta' => [
                            "dataUrl" => "api/customer_area",
                        ],
                    ],
                    [
                        'name' => 'solar_plant',
                        'link' => '/solar_plant',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/setting/solarPlant.vue",
                        'meta' => [
                            "dataUrl" => "api/solar_plant",
                        ],
                    ],
                    [
                        'name' => 'staff',
                        'link' => '/staff',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/setting/staff.vue",
                        'meta' => [
                            "dataUrl" => "api/staff",
                        ],
                    ],
                    [
                        'name' => 'generator',
                        'link' => '/generator',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/setting/generator.vue",
                        'meta' => [
                            "dataUrl" => "api/generator",
                        ],
                    ],
                    [
                        'name' => 'notification_settings',
                        'link' => '/notification_settings',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                    [
                        'name' => 'backup_restore',
                        'link' => '/backup_restore',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                ]
            ],
            [
                'name' => 'help_support',
                'link' => '',
                'permissions' => ['view', 'report', 'print'],
                'icon' => 'bx bx-help-circle',
                'submenus'=>[
                    [
                        'name' => 'user_manual',
                        'link' => '/user_manual',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                    [
                        'name' => 'contact_support',
                        'link' => '/contact_support',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                    [
                        'name' => 'generate_receipt',
                        'link' => '/generate_receipt',
                        'permissions' => array_merge($resourcePermissions, []),
                        "component" => "views/pages/Setting.vue",
                    ],
                ]
            ],
            [
                'name' => 'app_settings',
                'link' => '/app_settings',
                'permissions' => array_merge($resourcePermissions, []),
                "component" => "views/pages/Setting.vue",
                'icon' => 'bx bx-radio-circle',
                'meta' => [
                    "dataUrl" => "api/role_permissions",
                ]
            ],
            [
                'name' => 'profile',
                'link' => '/profile',
                'permissions' => array_merge($resourcePermissions, []),
                'icon' => 'bx bx-radio-circle',
                "component" => "views/pages/users/profile.vue",
                'is_visible' => 0
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
        $module->meta = isset($data['meta']) ? json_encode($data['meta']) : json_encode([]);
        $module->parent_id = $parent_id;
        $module->component = isset($data['component']) ? $data['component'] : '';
        $module->is_visible = isset($data['is_visible']) ? $data['is_visible'] : 1;
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