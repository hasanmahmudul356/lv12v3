<?php

namespace App\Http\Controllers;

use App\Models\area;
use App\Models\Customer;
use App\Models\Meter;
use App\Models\MeterType;
use App\Models\RBAC\Module;
use App\Models\RBAC\Permission;
use App\Models\RBAC\Role;
use App\Models\Staff;
use App\Models\User;
use function Carbon\this;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SupportController extends Controller
{
    public function appConfigurations()
    {
        $role_id = auth()->user()->role_id;
        $user_id = auth()->user()->id;

        $data['user'] = User::where('id', $user_id)->first();
//        $data['config'] = configs(['logo', 'app_name', 'loan_capability']);

        $permissions = Permission::whereHas('role_permissions', function ($query) use ($role_id) {
            $query->where('role_id', $role_id);
        })->get();

        $permittedModules = collect($permissions)->pluck('module_id');
        $data['permissions'] = collect($permissions)->pluck('name');

        $locals = [];
        $files = glob(resource_path('lang/*.json'));
        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $locals[] = ['locale' => $name, 'name' => $name];
        }
        $data['localization'] = $locals;

        $data['menus'] = Module::where('parent_id', 0)->where('is_visible', 1)
            ->whereIn('id', $permittedModules)
            ->with(['submenus' => function ($query) use ($permittedModules) {
                $query->with('submenus')->where('is_visible', 1);
                $query->whereIn('id', $permittedModules);
                $query->with(['submenus' => function ($query) use ($permittedModules) {
                    $query->with('submenus')->where('is_visible', 1);
                    $query->whereIn('id', $permittedModules);
                }]);
            }])->get();

        return returnData(2000, $data);
    }

    public function loadJson(){
        $jsonData = [
            'locale' => $this->getLocalization(true),
            'routes' => $this->getRoutes(true),
        ];
        return response()->json(json_encode($jsonData));
    }

    public function getLocalization($inSide = false)
    {
        $locals = [];
        $files = glob(resource_path('lang/*.json'));

        foreach ($files as $file) {
            $locale = pathinfo($file, PATHINFO_FILENAME);
            $locals[$locale] = json_decode(file_get_contents($file), true) ?? [];
        }

        if ($inSide){
            return $locals;
        }

        return response()->json(json_encode($locals));
    }
    public function getRoutes($inSide = false)
    {
        $select = "id, name, link as path, component, meta, parent_id";
        $routes = [
            [
                "path" => "/",
                "name" => "app",
                "component" => "views/layouts/AppLayouts.vue",
                "children" => Module::selectRaw($select)->where('parent_id', 0)
                    ->with(['children' => function ($query) use ($select) {
                        $query->selectRaw($select);
                        $query->with(['children' => function ($query2) use ($select) {
                            $query2->selectRaw($select);
                        }]);
                    }])->get()
            ],
        ];

        if ($inSide){
            return $routes;
        }

        return response()->json(json_encode($routes));
    }

    public function getGeneralData()
    {
        $input = request()->all();
        $data = [];

        if (isset($input['permissions']) || in_array('permissions', $input)) {
            $key = isset($input['permissions']['key']) ? isset($input['permissions']['key']) : 'permissions';
            $data[$key] = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'status'];
        }
        if (isset($input['roles']) || in_array('roles', $input)) {
            $key = isset($input['roles']['key']) ? isset($input['roles']['key']) : 'roles';
            $data[$key] = Role::where('status', 1)->get();
        }
        if (isset($input['meter_type']) || in_array('meter_type', $input)){
            $key = isset($input['meter_type']['key']) ?  isset($input['meter_type']['key']) : 'meter_type';
            $data[$key] =  MeterType::where('status', 1)->get();
        }
        if (isset($input['officer']) || in_array('officer', $input)){
            $key = isset($input['officer']['key']) ?  isset($input['officer']['key']) : 'officer';
            $data[$key] =  Staff::where('status', 1)->get();
        }
//        if (isset($input['customer'],$array) || in_array('customer', $input)){
//            $key = isset($input['customer']['key']) ?  isset($input['customer']['key']) : 'customer';
//            $data[$key] =  Customer::where('status', 1)
//                ->where(function ($q) use ($array){
//                    $type=isset($array['customer']['meter_type']) ? $array['customer']['meter_type'] : 0;
//                    if ($type){
//                        $q->where('meter_type',$type);
//                    }
//                })
//                ->get();
//        }

//        $array = $array ?? [];
//
//        if (isset($input['customer']) || in_array('customer', $input)) {
//            $key = isset($input['customer']['key']) ? $input['customer']['key'] : 'customer';
//
//            $data[$key] = Customer::with('meterType')
//                ->where('status', 1)
//                ->when(isset($array['customer']['meter_type']), function ($q) use ($array) {
//                    $q->where('meter_type_id', $array['customer']['meter_type']);
//                })
//                ->get();
//        }

        $array = $array ?? [];

        if (isset($input['customer']) || in_array('customer', $input)) {
            $key = isset($input['customer']['key']) ? $input['customer']['key'] : 'customer';

            $data[$key] = Customer::with('meterType','customer_area')
                ->where('status', 1)
                ->get();
        }



        if (isset($input['users']) || in_array('users', $input)){
            $key = isset($input['users']['key']) ?  isset($input['users']['key']) : 'users';
            $data[$key] =  User::where('status', 1)->get();
        }

        if (isset($input['meter_num']) || in_array('meter_num', $input)){
            $key = isset($input['meter_num']['key']) ?  isset($input['meter_num']['key']) : 'meter_num';
            $data[$key] =  Meter::where('status', 1)->get();
        }

        if (isset($input['customer_area']) || in_array('customer_area', $input)){
            $key = isset($input['customer_area']['key']) ?  isset($input['customer_area']['key']) : 'customer_area';
            $data[$key] =  area::where('status', 1)->get();
        }

        if (isset($input['components']) || in_array('components', $input)) {
            $files = File::allFiles(resource_path('js/views'));
            $components = [];

            $basePath = resource_path('js');
            foreach ($files as $file) {
                $relativePath = str_replace($basePath . '/', '', $file->getPathname());
                $components[] = $relativePath;
            }
            $data['components'] = $components;
        }

        if (isset($input['icons']) || in_array('icons', $input)) {
            $data['icons'] = [
                'bx bx-home-alt',
                'bx bx-lock',
                'bx bx-user-circle',
                'bx bx-radio-circle',
                'bx bx-group',
                'bx bx-tachometer',
                'bx bx-receipt',
                'bx bx-credit-card',
                'bx bx-calculator',
                'bx bx-bolt-circle',
                'bx bx-error',
                'bx bx-bar-chart-alt-2',
                'bx bx-cog',
                'bx bx-help-circle',
            ];
        }

        return returnData(2000, $data);
    }
}
