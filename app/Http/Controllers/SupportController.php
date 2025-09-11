<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\ActivityLog;
use App\Models\AppNotification;
use App\Models\RBAC\Module;
use App\Models\RBAC\Permission;
use App\Models\RBAC\Role;
use App\Models\User;
use function Carbon\this;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SupportController extends Controller
{
    use Helper;

    public function appConfigurations()
    {
        $role_id = auth()->user()->role_id;
        $user_id = auth()->user()->id;

        $data['user'] = User::where('id', $user_id)->first();
        $data['configs'] = configs(['logo', 'app_name','app_logo', 'notify_per_minuit']);

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

    public function addLocalization(Request $request){
        $item = $request->input('item');
        if ($item){
            try{
                $string = str_replace('_', ' ', $item);
                $file = resource_path('lang/en.json');
                $json = file_get_contents($file);
                $data = json_decode($json, true);
                $data[$item] = ucwords($string);
                $newJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($file, $newJson);
                return true;
            }catch (\Exception $exception){
                return false;
            }

        }
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
    public function appNotification($isInSide = false, $limit = 5, $skip = 0){
        $limit = request()->input('limit') ? request()->input('limit') : $limit;
        $skip = request()->input('skip') ? request()->input('skip') : $skip;

        $notificationData = [
            'total' => DB::table('app_notifications')->where('status', 0)->count(),
            'data' => AppNotification::where('status', 0)->limit($limit)->skip($skip)->get(),
            'limit' => $limit,
            'skip' => $skip,
        ];

        if ($isInSide){
            return $notificationData;
        }

        return returnData(2000, $notificationData);
    }

    public function appDashboard(){
        $dashboard = [];
        $notifications = $this->appNotification(true);

        return returnData(2000, [
            'dashboard' => $dashboard,
            'notifications' => $notifications,
        ]);
    }
    public function userActivities(){
        $data = ActivityLog::orderBy('id', 'DESC')->with('user:id,name')
            ->paginate(input('per_page'));

        return returnData(2000, $data);
    }
}
