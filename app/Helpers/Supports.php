<?php

use App\Models\Configure;
use Carbon\Carbon;
use App\Models\User;
use App\Models\RBAC\Permission;
use App\Models\Configuration;
use App\Models\ReportingsManagers;
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


if (!function_exists('getConfig')) {
    function getConfig($name)
    {
        $config = Configure::where('key', $name)->first();
        if ($config) {
            return $config->value;
        }
        return '';
    }
}
if (!function_exists('getStaffResignStatus')) {
    function getStaffResignStatus($resign_status)
    {

        $staff_resign_status = [
            [
                'name' => 'Resignation',
                'id' => 1
            ],
            [
                'name' => 'Discharge',
                'id' => 2
            ],
            [
                'name' => 'Dismissal',
                'id' => 3
            ],
            [
                'name' => 'Termination (Self)',
                'id' => 4
            ],
            [
                'name' => 'Termination (Authority)',
                'id' => 5
            ],
            [
                'name' => 'Retirement',
                'id' => 6
            ],
            [
                'name' => 'Death_reasons',
                'id' => 7
            ]
        ];
        if ($resign_status > count($staff_resign_status)) {
            return '';
        }
        return $staff_resign_status[$resign_status - 1]['name'] ?? '';
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
        $configs = DB::table('configures')->where(function ($query) use ($keys) {
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

if (!function_exists('userDomainId')) {
    function userDomainId($domainRequestName = 'domain', $guardName = 'admin')
    {
        $user = auth()->guard($guardName)->user();

        if ($user && $user->domain_code) {
            return $user->domain_code;
        }

        if (request()->input($domainRequestName)) {
            return request()->input($domainRequestName);
        }

        return null;
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

if (!function_exists('basicData')) {
    function basicData($columName = false, $particular = 'basic_id')
    {
        $basicComponent = DB::table('configuration_components')->where('component_particular', $particular)->first();
        if ($columName) {
            return $basicComponent->{$columName};
        }

        return $basicComponent;
    }
}
if (!function_exists('layerColumnFromLayerNumber')) {
    function layerColumnFromLayerNumber($layerNumber)
    {
        if ($layerNumber == 1) {
            return 'sector_id';
        }
        if ($layerNumber == 2) {
            return 'PBI_REGION';
        }
        if ($layerNumber == 3) {
            return 'PBI_ZONE';
        }
        if ($layerNumber == 4) {
            return 'PBI_AREA';
        }
        if ($layerNumber == 5) {
            return 'PBI_BRANCH';
        }
        return '';
    }
}
if (!function_exists('validationErrorFormatter')) {
    function validationErrorFormatter($errors)
    {
        $valErrors = [];
        foreach (collect($errors)->toArray() as $key => $error) {
            $valErrors[$key] = $error[0];
        }
        return $valErrors;
    }
}

// if (!function_exists('convertDateFormat')) {
//     function convertDateFormat($date)
//     {
//         if (!$date) return null;
//         $parts = explode('/', $date);
//         if (count($parts) !== 3) return null;
//         [$day, $month, $year] = $parts;
//         return "$year-$month-$day";
//     }
// }

if (!function_exists('convertDateFormat')) {
    function convertDateFormat($date)
    {
        if (!$date) return null;

        if (Carbon::hasFormat($date, 'Y-m-d')) {
            return $date;
        }

        $parts = explode('/', $date);
        if (count($parts) !== 3) return null;

        [$day, $month, $year] = $parts;
        return "$year-$month-$day";
    }
}

if (!function_exists('convertDisplayFormat')) {
    function convertDisplayFormat($date)
    {
        if (!$date) return null;
        return Carbon::parse($date)->format('d/m/Y');
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


if (!function_exists('getFilterNames')) {
    function getFilterNames($filters)
    {
        $filterQuery = DB::table('personnel_basic_info')
            ->leftJoin('sector', 'sector.id', '=', 'personnel_basic_info.sector_id')
            ->leftJoin('domai', 'domai.DOMAIN_CODE', '=', 'personnel_basic_info.PBI_DOMAIN')
            ->leftJoin('zon', 'zon.ZONE_CODE', '=', 'personnel_basic_info.PBI_ZONE')
            ->leftJoin('area', 'area.AREA_CODE', '=', 'personnel_basic_info.PBI_AREA')
            ->leftJoin('branch', 'branch.BRANCH_ID', '=', 'personnel_basic_info.PBI_BRANCH')
            ->leftJoin('designationtype', 'designationtype.DESG_ID', '=', 'personnel_basic_info.PBI_DESIGNATION')
            ->leftJoin('functional_designation', 'personnel_basic_info.functional_designation', '=', 'functional_designation.id')
            ->leftJoin('department_type', 'personnel_basic_info.PBI_DEPARTMENT', '=', 'department_type.DEPT_ID')
            ->leftJoin('project', 'personnel_basic_info.PBI_PROJECT', '=', 'project.PROJECT_ID')
            ->select([
                'sector.sector_name',
                'domai.DOMAIN_DESC as DOMAIN_NAME',
                'zon.ZONE_NAME',
                'area.AREA_NAME',
                'branch.BRANCH_NAME',
                'department_type.DEPT_DESC as department_name',
                'project.PROJECT_DESC',
                'designationtype.DESG_DESC',
                'functional_designation.functional_designation as func_designation_name'
            ]);

        foreach ($filters as $field => $value) {
            if ($value) {
                $filterQuery->where("personnel_basic_info.$field", $value);
            }
        }

        $filterData = $filterQuery->first();

        $filterNames = [];
        if (!empty($filters['sector_id'])) $filterNames['Sector'] = $filterData->sector_name ?? null;
        if (!empty($filters['PBI_DOMAIN'])) $filterNames['Region'] = $filterData->DOMAIN_NAME ?? null;
        if (!empty($filters['PBI_ZONE'])) $filterNames['Zone'] = $filterData->ZONE_NAME ?? null;
        if (!empty($filters['PBI_AREA'])) $filterNames['Area'] = $filterData->AREA_NAME ?? null;
        if (!empty($filters['PBI_BRANCH'])) $filterNames['Branch'] = $filterData->BRANCH_NAME ?? null;
        if (!empty($filters['PBI_DEPARTMENT'])) $filterNames['Unit/Cell'] = $filterData->department_name ?? null;
        if (!empty($filters['PBI_PROJECT'])) $filterNames['Porject/Department'] = $filterData->PROJECT_DESC ?? null;
        if (!empty($filters['PBI_DESIGNATION'])) $filterNames['Designation'] = $filterData->DESG_DESC ?? null;
        if (!empty($filters['functional_designation'])) $filterNames['Responsiblity'] = $filterData->func_designation_name ?? null;

        return $filterNames;
    }


    // if (!function_exists('staffUnderManagerIds')) {
    //     function staffUnderManagerIds(): ?array
    //     {
    //         $guards = ['admin_api', 'admin']; // check auth guard API first,
    //         $user = null;

    //         foreach ($guards as $guard) {
    //             if (auth($guard)->check()) {
    //                 $user = auth($guard)->user();
    //                 break;
    //             }
    //         }

    //         $pbiId = $user->pbi_id ?? null;
    //         if (!$pbiId) return null;

    //         return ReportingsManagers::where('manager_id', $pbiId)
    //             ->where('status', 1)
    //             ->pluck('PBI_ID')
    //             ->prepend($pbiId)
    //             ->unique()
    //             ->toArray();
    //     }
    // }

    if (!function_exists('staffUnderManagerIds')) {
        function staffUnderManagerIds(): ?array
        {
            $guards = ['admin_api', 'admin'];
            $user = null;

            foreach ($guards as $guard) {
                if (auth($guard)->check()) {
                    $user = auth($guard)->user();
                    break;
                }
            }

            $pbiId = $user->pbi_id ?? null;
            if (!$pbiId) return null;

            // Fetch employees under this manager either directly (manager_id) or via approval_manager
            $directReports = ReportingsManagers::where('manager_id', $pbiId)
                ->where('status', 1)
                ->pluck('PBI_ID');

            $approvalReports = ReportingsManagers::where('approval_manager', $pbiId)
                ->where('status', 1)
                ->pluck('PBI_ID');

            return $directReports
                ->merge($approvalReports)
                ->prepend($pbiId) // Add manager himself
                ->unique()
                ->toArray();
        }
    }

    if (!function_exists('numberToWords')) {
        function numberToWords($number)
        {
            $ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
            $tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
            $teens = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];

            $amount = (int)$number;
            $decimal = (int)round(($number - $amount) * 100);

            $words = [];

            if ($amount >= 10000000) {
                $crore = (int)($amount / 10000000);
                $amount %= 10000000;
                $words[] = numberToWords($crore) . " Crore";
            }

            if ($amount >= 100000) {
                $lakh = (int)($amount / 100000);
                $amount %= 100000;
                $words[] = numberToWords($lakh) . " Lakh";
            }

            if ($amount >= 1000) {
                $thousand = (int)($amount / 1000);
                $amount %= 1000;
                $words[] = numberToWords($thousand) . " Thousand";
            }

            if ($amount >= 100) {
                $hundred = (int)($amount / 100);
                $amount %= 100;
                $words[] = $ones[$hundred] . " Hundred";
            }

            if ($amount >= 20) {
                $ten = (int)($amount / 10);
                $amount %= 10;
                $words[] = $tens[$ten];
            } elseif ($amount >= 10) {
                $words[] = $teens[$amount - 10];
                $amount = 0;
            }

            if ($amount > 0) {
                $words[] = $ones[$amount];
            }

            $result = implode(" ", $words);

            if ($decimal > 0) {
                $result .= " and " . $decimal . "/100";
            }

            return $result ? $result . " " : "Zero";
        }
    }
}
