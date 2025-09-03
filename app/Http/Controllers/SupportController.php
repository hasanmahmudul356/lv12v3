<?php

namespace App\Http\Controllers;

use App\Models\RBAC\Module;
use App\Models\RBAC\Permission;
use App\Models\RBAC\Role;
use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function profileUpdate(Request $request){
        $reqFor = $request->input('request');

        $user = User::where('id', auth()->user()->id)->first();
        if ($user){
            if ($reqFor && $reqFor == 'theme'){
                $user->theme = $request->input('theme');
                $user->save();

                return returnData(2000, $user, 'Successfully Theme Updated');
            }

            if ($reqFor && $reqFor == 'locale'){
                $user->locale = $request->input('locale');
                $user->save();

                return returnData(2000, $user, 'Successfully locale Updated');
            }

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->theme = $request->input('theme');
            $user->save();

            return returnData(2000, $user, 'Successfully Updated');
        }
        return returnData(5000, null, 'User Not Found');
    }
    public function appConfigurations(){
        $role_id = auth()->user()->role_id;
        $user_id = auth()->user()->id;

        $data['user'] = User::where('id', $user_id)->first();
//        $data['config'] = configs(['logo', 'app_name', 'loan_capability']);

        $permissions = Permission::whereHas('role_permissions', function ($query) use ($role_id) {
            $query->where('role_id', $role_id);
        })->get();

        $permittedModules = collect($permissions)->pluck('module_id');
        $data['permissions'] = collect($permissions)->pluck('name');
        $data['localization'] = [
            ['locale' => 'en', 'name' => 'English', 'flag' => publicImage('backend/flags/1x1/us.svg')],
            ['locale' => 'bn', 'name' => 'বাংলা', 'flag' => publicImage('backend/flags/1x1/bd.svg')]
        ];

        $data['menus'] = Module::where('parent_id', 0)
            ->whereIn('id', $permittedModules)
            ->with(['submenus' => function ($query) use ($permittedModules) {
                $query->with('submenus');
                $query->whereIn('id', $permittedModules);
            }])->get();

        return returnData(2000, $data);
    }

    public function getLocalization(){
        $languages = ['en', 'bn'];
        $locals = [];
        foreach ($languages as $locale){
            $path = resource_path("lang/{$locale}.json");
            if (file_exists($path)){
                $locals[$locale] = json_decode(file_get_contents($path), true);
            };
        }
        return response()->json(json_encode($locals));
    }
    public function getRoutes(){
        $route = [
            [
                "path" => "/",
                "name" => "app",
                "component" => "views/layouts/AppLayouts.vue",
                "children" =>

                    $routes = [
                        [
                            "path" => "",
                            "name" => "dashboard",
                            "component" => "views/pages/Dashboard.vue",
                        ],
                        [
                            "path" => "profile",
                            "name" => "profile",
                            "component" => "views/pages/users/profile.vue",
                            "meta" => [
                                "dataUrl" => "api/profile",
                                "title" => "Profile",
                                "listPage" => false,
                            ],
                        ],
                        [
                            "path" => "dashboard",
                            "name" => "dashboard-alt",
                            "component" => "views/pages/Dashboard.vue",
                        ],
                        [
                            "path" => "users",
                            "name" => "users",
                            "component" => "views/pages/users/userList.vue",
                            "meta" => [
                                "dataUrl" => "api/users",
                                "title" => "Users",
                            ],
                        ],
                        [
                            "path" => "modules",
                            "name" => "modules",
                            "component" => "views/pages/rbac/Module.vue",
                            "meta" => [
                                "dataUrl" => "api/modules",
                                "title" => "Module",
                            ],
                        ],
                        [
                            "path" => "roles",
                            "name" => "role",
                            "component" => "views/pages/rbac/Module.vue",
                            "meta" => [
                                "dataUrl" => "api/modules",
                                "title" => "Roles",
                            ],
                        ],
                        [
                            "path" => "module_permissions",
                            "name" => "module_permission",
                            "component" => "views/pages/rbac/Module.vue",
                            "meta" => [
                                "dataUrl" => "api/module_permissions",
                                "title" => "Module",
                            ],
                        ],
                        [
                            "path" => "role_permissions",
                            "name" => "role_permission",
                            "component" => "views/pages/rbac/Module.vue",
                            "meta" => [
                                "dataUrl" => "api/role_permissions",
                                "title" => "Module",
                            ],
                        ],
                    ]
            ],
        ];
        return response()->json($route);
    }

    public function getGeneralData(){
        $input = request()->all();
        $data = [];

        if (isset($input['permissions']) || in_array('permissions', $input)){
            $key = isset($input['permissions']['key']) ?  isset($input['permissions']['key']) : 'permissions';
            $data[$key] =  ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'status'];
        }
        if (isset($input['roles']) || in_array('roles', $input)){
            $key = isset($input['roles']['key']) ?  isset($input['roles']['key']) : 'roles';
            $data[$key] =  Role::where('status', 1)->get();
        }

        return returnData(2000, $data);
    }
}
