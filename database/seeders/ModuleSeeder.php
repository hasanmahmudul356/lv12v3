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
                'name' => 'app_settings',
                'link' => '/app_settings',
                'permissions' => array_merge($resourcePermissions, []),
<<<<<<< Updated upstream
=======
                "component" => "views/setting/appSetting.vue",
>>>>>>> Stashed changes
                'icon' => 'bx bx-radio-circle',
                "component" => "views/pages/Setting.vue",
                'meta' => [
                    "dataUrl" => "api/settings",
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
