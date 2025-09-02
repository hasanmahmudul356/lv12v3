<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Meter;
use App\Models\MeterType;
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

        $data['menus'] = Module::where('parent_id', 0)
            ->whereIn('id', $permittedModules)
            ->with(['submenus' => function ($query) use ($permittedModules) {
                $query->with('submenus');
                $query->whereIn('id', $permittedModules);
            }])->get();

        return returnData(2000, $data);
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
        if (isset($input['meter_type']) || in_array('meter_type', $input)){
            $key = isset($input['meter_type']['key']) ?  isset($input['meter_type']['key']) : 'meter_type';
            $data[$key] =  MeterType::where('status', 1)->get();
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

            $data[$key] = Customer::with('meterType')
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

        return returnData(2000, $data);
    }
}
