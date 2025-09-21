<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\RBAC\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

if (!function_exists('input')) {
    function input($name)
    {
        if (request()->input($name)) {
            return request()->input($name);
        }
        return null;
    }
}
if (!function_exists('assets')) {
    function assets($path)
    {
        if (env('PUBLIC_PATH')) {
            return env('PUBLIC_PATH') . '/' . ltrim($path, '/');
        }
        return asset($path);
    }
}
if (!function_exists('can')) {
    function can($permission)
    {
        $permissions = Permission::whereHas('role_permissions', function ($query) {
            $query->where('role_id', auth()->user()->role_id);
        })->get()->pluck('name')->toArray();

        if (is_array($permission)) {
            foreach ($permission as $each_per) {
                if (in_array($each_per, $permissions)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            if (in_array($permission, $permissions)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('getData')) {
    function getData($id, $column, $table = 'users', $whereColumn = 'id')
    {
        $user = DB::table($table)->where($whereColumn, $id)->first();
        if ($user) {
            return $user->{$column};
        }
        return '';
    }
}
if (!function_exists('storageImage')) {
    function storageImage($path)
    {
        if (!$path) {
            return publicImage('images/male_stuff.png');
        }
        if (env('UPLOAD_PATH')) {
            return env('UPLOAD_PATH') . '/' . $path;
        }
        return env('UPLOAD_PATH') . '/' . $path;
    }
}
if (!function_exists('publicImage')) {
    function publicImage($path)
    {
        return env('PUBLIC_PATH') . '/' . $path;
    }
}
if (!function_exists('returnData')) {
    function returnData($status_code = 2000, $result = null, $message = null, $type = false)
    {
        return response()->json(array_merge(
            ['status' => $status_code],
            array_filter([
                'result'  => $result,
                'message' => $message,
                'type'    => $type
            ], function ($v) {
                return ($v !== null && $v !== false);
            })
        ));
    }
}


if (!function_exists('returnUnauthorized')) {
    function returnUnauthorized($status_code = 4001, $result = null, $message = 'Unauthorized! Contact system Admin.')
    {
        $data['status'] = $status_code;
        $data['message'] = $message;
        if ($result) {
            $data['result'] = $result;
        }

        return response()->json($data);
    }
}

if (!function_exists('permissions')) {
    function permissions()
    {
        $user_permissons = @unserialize(session()->get(''));
        if (is_array($user_permissons)) {
            return $user_permissons;
        }
        return [];
    }
}

if (!function_exists('randomString')) {
    function randomString($length = 25, $type = 'n')
    {
        $characters = $type == 'n' ? '123456789' : '123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('folder')) {
    function folder($path, $permission = 0777)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            return $path;
        } else {
            return $path;
        }
    }
}

if (!function_exists('appFile')) {
    function appFile($path)
    {
        if (file_exists(public_path() . $path)) {
            return $path;
        } else {
            return '/img/no-image.png';
        }
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($requestFile, $fileName = null, $folder = null)
    {
        try {
            if ($requestFile) {
                $filePath = $folder ? $folder : 'img/';
                $image = $requestFile;
                $format = explode('/', mime_content_type($requestFile))[1];
                $data['image'] = $fileName ? $fileName . ".$format" : time() . ".$format";
                $img = Image::make($image);
                $upload_path = folder(public_path($filePath));
                $image_url = $upload_path . $data['image'];
                $img->save($image_url);

                if ($img) {
                    return $filePath . $data['image'];
                }
                return null;
            }
        } catch (Exception $exception) {
            return null;
        }
    }
}

if (!function_exists('ddA')) {
    function ddA($arrayOrObject)
    {
        dd(collect($arrayOrObject)->toArray());
    }
}

if (!function_exists('exact_permission')) {
    function exact_permission($permission_name)
    {
        $explode = explode('_', $permission_name);
        return end($explode);
    }
}

if (!function_exists('configs')) {
    function configs($keys)
    {
        $configs = DB::table('settings')->where(function ($query) use ($keys) {
            if (is_array($keys)) {
                $query->whereIn('key', $keys);
            } else {
                $query->where('key', $keys);
            }
        })->get();

        $conData = [];

        foreach ($configs as $config) {
            $conData[$config->key] = $config->value;

            if ($config->type == 'file') {
                $conData[$config->key] = storageImage($config->value);
            }
            if ($config->type == 'encoded') {
                $conData[$config->key] = json_decode($config->value);
            }
            if ($config->type == 'youtube') {
                $conData[$config->key] = deviceWiseUrl($config->value);
            }
        }

        if (count($keys) == 1) {
            return collect($conData)->first();
        }
        return $conData;
    }
}
if (!function_exists('levels')) {
    function levels($keys)
    {

        $levels = DB::table('level_name_manages')->where(function ($query) use ($keys) {
            if (is_array($keys)) {
                $query->whereIn('key', $keys);
            } else {
                $query->where('key', $keys);
            }
        })->get();

        $conData = [];

        foreach ($levels as $level) {
            $conData[$level->key] = $level->value;
        }

        if (count($keys) == 1) {
            return collect($conData)->first();
        }
        return $conData;
    }
}

if (!function_exists('strLimit')) {
    function strLimit($string, $limit)
    {
        return mb_strimwidth(strip_tags($string), 0, $limit, '...');
    }
}


if (!function_exists('themeLayout')) {
    function themeLayout()
    {
        if (auth()->check()) {
            return auth()->user()->layout;
        }
        return 'vertical';
    }
}

if (!function_exists('isSuperUser')) {
    function isSuperUser()
    {
        if (can('user_add') && can('user_update')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}
if (!function_exists('userRole')) {
    function userRole($guard)
    {
        if (auth()->guard($guard)->check()) {
            return auth()->guard($guard)->user();
        }
    }
}
if (!function_exists('hasInput')) {
    function hasInput($name)
    {
        if (request()->input($name)) {
            return true;
        }

        return false;
    }
}
if (!function_exists('textToSlug')) {
    function textToSlug($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }
}

if (!function_exists('dbValue')) {
    function dbValue($pbiID, $columNAme = 'PBI_ID', $tableName = 'personnel_basic_info')
    {
        return DB::table($tableName)->where($columNAme, $pbiID)->first();
    }
}
if (!function_exists('getTable')) {
    function getTable($tableName, $tablePrefix = 'dbo')
    {
        return "$tablePrefix.$tableName";
    }
}

if (!function_exists('fullLibraryPath')) {
    function fullLibraryPath($path)
    {
        return config('library.LIBRARY_PATH') . '/' . $path;
    }
}
if (!function_exists('getDeviceData')) {
    function getDeviceData($pythonScriptPath, $scriptFile, $deviceIpAddress, $devicePort, $execuitableFullPath = 0)
    {
        $attendenceScriptPath = fullLibraryPath($pythonScriptPath);
        $pythonScript = "$attendenceScriptPath/$scriptFile";

        $pythonPath = $execuitableFullPath ? "$attendenceScriptPath/venv/bin/python3" : "python3";
        $pythonCheckScript = "$attendenceScriptPath/checkConnection.py";
        $command = "$pythonPath $pythonScript $deviceIpAddress $devicePort 2>&1";
        $checkConnection = shell_exec("$attendenceScriptPath/venv/bin/python3 $pythonCheckScript $deviceIpAddress $devicePort 2>&1");


        $connectionResult = json_decode($checkConnection);

        if (isset($connectionResult->status) && $connectionResult->status) {
            $output = shell_exec($command);

            $attendance_info = json_decode($output, true);
            return (object)[
                'status' => true,
                'data' => $attendance_info,
                'message' => 'Successfully retrieved attendance'
            ];
        }

        return (object)[
            'status' => false,
            'data' => [],
            'message' => $connectionResult->message
        ];
    }
}

if (!function_exists('getLocale')) {
    function getLocale($key = '', $extraText = '')
    {
        if ($key){
            return __($key)." $extraText";
        }

        return '';
    }
}

if (!function_exists('checkComponentFile')) {
    function checkComponentFile(array $menuItem)
    {
        $component = isset($menuItem['component']) ? $menuItem['component'] : '';
        $file = base_path("resources/js/{$component}");

        if (!empty($component) && !file_exists($file)) {
            return (object)[
                'status' => false,
                'component' => $file,
                'message' => "Missing component",
            ];
        }

        return (object)[
            'status' => true,
            'component' => $file,
            'message' => "Valid Component",
        ];
    }
}
///var/www/l12v3/resources/js/views/pages/Dashboard.vue
