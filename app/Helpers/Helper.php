<?php

namespace App\Helpers;

use App\Models\Manager;
use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Admin;
use App\Models\Module;
use App\Models\Approval;
use App\Models\StaffNote;
use App\Models\Permission;
use App\Models\RoleModules;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\Pim\BasicInfoModel;
use Illuminate\Support\Facades\DB;
use App\Models\Reqruitment\JobPost;
use Illuminate\Support\Facades\Auth;
use App\Models\Setup\DesignationLayer;
use App\Models\Reqruitment\JobCriteria;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SingleApp\ConfigurationController;
use Log;
use PHPUnit\Exception;

trait Helper
{
    public $permission = [];
    public $model = '';
    public $modelClass = '';
    public $childModel = '';
    public $perPage = 20;
    public $permissionMessage = 'Sorry, You do not have permission to perform this action..!!';
    public $exceptionMessage = 'Whoops, looks like something went wrong.';
    public $permissionMessageType = 'error';
    public $statusCode = array('-100', '-110', '-111', '-112', '-113', '-114', '-115', '-116', '-120');

    public function __construct()
    {
        $perPage = input('perPage');
        if ($perPage && $perPage > 0) {
            $this->perPage = $perPage;
        }
    }

    public function status()
    {
        try {
            $column = request()->input('column') ? request()->input('column') : 'status';
            $data = $this->model->where($this->model->getKeyName(), input('id'))->first();

            if (!$data) {
                return returnData(2000, null, 'Data Not found');
            }

            if (request()->input('change_status')) {
                $data->{$column} = request()->input('change_status');
                $data->save();

                return returnData(2000, 'success', "Status Changed");
            }

            if ($data->{$column} == 1) {
                $data->{$column} = 0;
                $data->save();

                return returnData(2000, 'warning', "Successfully InActivated");
            } else {
                $data->{$column} = 1;
                $data->save();

                return returnData(2000, 'success', "Successfully Activated");
            }
        } catch (\Exception $exception) {
            return returnData(5000, $exception->getMessage(), 'Not Updated');
        }
    }

    public function notPermitted()
    {
        $data = [];
        $data['status'] = 5001;
        $data['message'] = $this->permissionMessage;
        $data['type'] = $this->permissionMessageType;
        return response()->json($data);
    }

    public function insertFile($fileName, $module_type = 2)
    {
        $orgName = request()->file($fileName)->getClientOriginalName();
        $extension = request()->file($fileName)->getClientOriginalExtension();
        $size = request()->file($fileName)->getSize();
        $timestamp = now()->format('Ymd_His');
        $newFileName = $timestamp . '_' . str_replace(' ', '_', $orgName);
        $path = request()->file('file')->storeAs('uploads', str_replace(' ', '_', $newFileName), 'public');

        $sizeInMb = round(($size / 1000) / 1000, 2);

        return [
            'path' => $path,
            'name' => $newFileName,
            'extension' => $extension,
            'size' => $sizeInMb,
        ];
    }

    public function permittedMenus()
    {
        $user = auth()->guard('admin')->user();
        $role_id = $user->role_id;
        $data['user'] = DB::table('admins')
            ->selectRaw("admins.*,personnel_basic_info.image,personnel_basic_info.PBI_SEX, salary_sheet_approval, responsibility, leave_request, transfer_request, resignation_request, appointment_letter, joining_letter, promotion_letter, demotion_letter, increment_letter, confirmation_letter,reporting_managers.approval_manager as approval_manager_id")
            ->leftJoin('personnel_basic_info', 'personnel_basic_info.PBI_ID', '=', 'admins.pbi_id')
            ->leftJoin('managers', 'admins.id', '=', 'managers.user_id')
            ->leftJoin('reporting_managers', 'admins.pbi_id', '=', 'reporting_managers.approval_manager')
            ->where('admins.id', $user->id)->first();

        $permissions = Permission::whereHas('role_permissions', function ($query) use ($role_id) {
            $query->where('role_id', $role_id);
        })->get();

        $permittedModules = collect($permissions)->pluck('module_id');
        $data['permissions'] = collect($permissions)->pluck('name')->toArray();
        $configs = configs(['app_name', 'logo', 'id_card_org']);
        $levels = levels(['level_1', 'level_2', 'level_3', 'level_4', 'level_5', 'level_6', 'level_7']);

        $orgLayer = DB::table('level_name_manages')->select('priority', 'value')->get()->keyBy('priority');

        $data['menus'] = Module::where('status', 1)->where('parent_id', 0)
            ->whereIn('id', $permittedModules)
            ->with(['submenus' => function ($query) use ($permittedModules) {
                $query->whereIn('id', $permittedModules);
                $query->orderBy('sort_index', 'ASC');
                $query->orderBy('parent_id', 'ASC');
                $query->orderBy('is_subparent', 'DESC');

                $query->with(['submenus' => function ($query) use ($permittedModules) {
                    $query->whereIn('id', $permittedModules);
                    $query->orderBy('sort_index', 'ASC');
                    $query->orderBy('parent_id', 'ASC');
                    $query->orderBy('is_subparent', 'DESC');
                }]);
            }])
            ->orderBy('sort_index', 'ASC')
            ->orderBy('parent_id', 'ASC')
            ->orderBy('is_subparent', 'DESC')
            ->orderBy('id', 'ASC')
            ->get()->toArray();

        $data['layer'] = $levels;
        $data['orgLayer'] = $orgLayer;
        $data['mfp'] = DB::table('configures')->where('key', 'microfinance_sector')->select('value')->first();

        $data = array_merge($data, $configs);

        return $data;
    }

    /**
     * $layer_id id of target level
     * $layer_type 1 = Sector, 2 = Domain, 3 = Zone, 4 = Area, 5 = Branch, 6 = Department
     * $responsible_type 1 = Head of level
     * $pbiSelectString Select fields for Level Head person
     */
    public function baseOfficeHeadPerson($layer_id, $layer_type, $responsible_type = 1, $pbiSelectString = " pbi.pbi_id pbi_id, pbi.pbi_name")
    {
        $approvalHead = [];
        $selectString = '';
        if ($layer_type == 7) {
            $approvalHead = DB::table(DB::raw('department dt'))->join(DB::raw('base_office_staff bos'), function ($join) {
                $join->on('dt.DEPT_ID', 'bos.layer_id')->where('bos.layer_type', 6);
            })
                ->join(DB::raw('personnel_basic_info pbi'), 'pbi.pbi_id', '=', 'bos.pbi_id');
            $selectString = "bos.layer_id layer_id, dt.dept_desc level_name, 'Department' level_label,";
        }
        if ($layer_type == 6) {
            $approvalHead = DB::table(DB::raw('project pr'))->join(DB::raw('base_office_staff bos'), function ($join) {
                $join->on('pr.PROJECT_ID', 'bos.layer_id')->where('bos.layer_type', 6);
            })
                ->join(DB::raw('personnel_basic_info pbi'), 'pbi.pbi_id', '=', 'bos.pbi_id');
            $selectString = "bos.layer_id layer_id, pr.PROJECT_DESC level_name, 'Project' level_label,";
        } else if ($layer_type == 5) {
            $approvalHead = DB::table(DB::raw('branch br'))->join(DB::raw('base_office_staff bos'), function ($join) {
                $join->on('br.branch_id', 'bos.layer_id')->where('bos.layer_type', 5);
            })
                ->join(DB::raw('personnel_basic_info pbi'), 'pbi.pbi_id', '=', 'bos.pbi_id');
            $selectString = "bos.layer_id layer_id, br.branch_name level_name, 'Branch' level_label, ";
        } else if ($layer_type == 4) {
            $approvalHead = DB::table(DB::raw('area ar'))->join(DB::raw('base_office_staff bos'), function ($join) {
                $join->on('ar.area_code', 'bos.layer_id')->where('bos.layer_type', 4);
            })
                ->join(DB::raw('personnel_basic_info pbi'), 'pbi.pbi_id', '=', 'bos.pbi_id');
            $selectString = "bos.layer_id layer_id, ar.area_name level_name, 'Area' level_label, ";
        } else if ($layer_type == 3) {
            $approvalHead = DB::table(DB::raw('zon zn'))->join(DB::raw('base_office_staff bos'), function ($join) {
                $join->on('zn.zone_code', 'bos.layer_id')->where('bos.layer_type', 3);
            })
                ->join(DB::raw('personnel_basic_info pbi'), 'pbi.pbi_id', '=', 'bos.pbi_id');
            $selectString = "bos.layer_id layer_id, zn.zone_name level_name, 'Zone' level_label, ";
        } else if ($layer_type == 2) {
            $approvalHead = DB::table(DB::raw('domai dm'))->join(DB::raw('base_office_staff bos'), function ($join) {
                $join->on('dm.domain_code', 'bos.layer_id')->where('bos.layer_type', 2);
            })
                ->join(DB::raw('personnel_basic_info pbi'), 'pbi.pbi_id', '=', 'bos.pbi_id');
            $selectString = "bos.layer_id layer_id, dm.domain_desc level_name, 'Region' level_label, ";
        } else if ($layer_type == 1) {
            $approvalHead = DB::table(DB::raw('sector sec'))->join(DB::raw('base_office_staff bos'), function ($join) {
                $join->on('sec.id', 'bos.layer_id')->where('bos.layer_type', 1);
            })
                ->join(DB::raw('personnel_basic_info pbi'), 'pbi.pbi_id', '=', 'bos.pbi_id');
            $selectString = "bos.layer_id layer_id, sec.sector_name level_name, 'Sector' level_label, ";
        }

        return $approvalHead->where('bos.layer_id', $layer_id)->where('bos.type', $responsible_type)->selectRaw($selectString . $pbiSelectString)->get();
    }

    public function baseOfficeUpwards($currentOfficeId, $currentOfficeLevel)
    {
        $nextBaseOffice = null;
        if ($currentOfficeLevel == 6) {
            $nextBaseOffice = DB::table(DB::raw('department dt'))->join(DB::raw('branch br'), function ($join) use ($currentOfficeId) {
                $join->on('br.branch_id', 'dt.branch_id')->where('dt.DEPT_ID', $currentOfficeId);
            });
            $selectString = "br.branch_id id, br.branch_name name, 'Branch' label, 5 type";
        } else if ($currentOfficeLevel == 5) {
            $nextBaseOffice = DB::table(DB::raw('branch br'))->join(DB::raw('area ar'), function ($join) use ($currentOfficeId) {
                $join->on('br.area_id', 'ar.area_code')->where('br.branch_id', $currentOfficeId);
            });
            $selectString = "ar.area_code id, ar.area_name name, 'Area' label, 4 type";
        } else if ($currentOfficeLevel == 4) {
            $nextBaseOffice = DB::table(DB::raw('area ar'))->join(DB::raw('zon zn'), function ($join) use ($currentOfficeId) {
                $join->on('ar.zone_id', 'zn.zone_code')->where('ar.area_code', $currentOfficeId);
            });
            $selectString = "zn.zone_code id, zn.zone_name name, 'Zone' label, 3 type";
        } else if ($currentOfficeLevel == 3) {
            $nextBaseOffice = DB::table(DB::raw('zon zn'))->join(DB::raw('domai dm'), function ($join) use ($currentOfficeId) {
                $join->on('zn.domain_id', 'dm.domain_code')->where('zn.zone_code', $currentOfficeId);
            });
            $selectString = "dm.domain_code id, dm.domain_desc name, 'Region' label, 2 type";
        } else if ($currentOfficeLevel == 2) {
            $nextBaseOffice = DB::table(DB::raw('domai dm'))->join(DB::raw('sector sec'), function ($join) use ($currentOfficeId) {
                $join->on('dm.sector_id', 'sec.id')->where('dm.domain_code', $currentOfficeId);
            });
            $selectString = "sec.id id, sec.sector_name name, 'Sector' label, 1 type";
        }

        return $nextBaseOffice->selectRaw($selectString)->first();
    }

    public function applicantValidation($applicantData)
    {
        $jobCriteria = JobCriteria::where('job_post_id', $applicantData->jobId)->first();
        $jobIDs = JobPost::where('id', $applicantData->jobId)->first();

        if (!$jobIDs) {
            return (object)[
                'status' => 0,
                'message' => 'There are no job found with this job id'
            ];
        }

        $districtIdArray = [];
        $educationIdArray = [];
        $subjectArray = [];

        if ($jobCriteria) {
            $ageCondition = ($jobCriteria->age == 1);
            $districtCondition = ($jobCriteria->district == 1);
            $educationCondition = ($jobCriteria->education == 1);

            if ($jobCriteria->district_id) {
                $districtIds = json_decode($jobCriteria->district_id, true);
                $districtIdArray = array_column($districtIds, 'district_id');
            }

            if ($jobCriteria->education_id) {
                $educationIds = json_decode($jobCriteria->education_id, true);
                $educationIdArray = array_column($educationIds, 'education_id');
            }

            if ($jobCriteria->subject_id) {
                $subjectIds = json_decode($jobCriteria->subject_id, true);
                $subjectArray = array_column($subjectIds, 'subject_id');
            }
        }

        $dateOfBirth = Carbon::createFromFormat('Y-m-d', $applicantData->date_of_birth);
        $age = $dateOfBirth->age;

        if ($jobCriteria) {
            if (($jobIDs->gender == 1 && $applicantData->gender != 1) || ($jobIDs->gender == 2 && $applicantData->gender != 2)) {
                return (object)[
                    'status' => 0,
                    'message' => 'You are not eligible for this job due to Gender restrictions.'
                ];
            }

            if ($ageCondition && !($age >= $jobCriteria->min_age && $age <= $jobCriteria->max_age)) {
                return (object)[
                    'status' => 0,
                    'message' => 'You are not eligible for this job due to age restrictions.'
                ];
            }
        }
        if (count($subjectArray) > 0) {
            if ($educationCondition && !in_array($applicantData->subject_id, $subjectArray)) {
                return (object)[
                    'status' => 0,
                    'message' => 'You are not eligible for this job due to Subject restrictions.'
                ];
            }
        }
        if (count($districtIdArray) > 0) {
            if ($districtCondition && !in_array($applicantData->district_id, $districtIdArray)) {
                return (object)[
                    'status' => 0,
                    'message' => 'You are not eligible for this job due to district restrictions.'
                ];
            }
        }

        if (count($educationIdArray) > 0) {
            if ($educationCondition && !in_array($applicantData->edu_id, $educationIdArray)) {
                return (object)[
                    'status' => 0,
                    'message' => 'You are not eligible for this job due to education requirements.'
                ];
            }
        }

        return (object)[
            'status' => 1,
            'message' => 'Validation Passed'
        ];
    }

    public function getTableValue($tableName, $columnName, $whereFilter = [])
    {
        $checkExist = DB::table($tableName)->where($whereFilter)->first();
        if ($checkExist) {
            return $checkExist->{$columnName};
        }
        DB::table($tableName)->insert([$whereFilter]);

        return $this->getTableValue($tableName, $columnName, $whereFilter);
    }

    //    public function grossCpmponents($components = [], $basic = 0, $basic_id = false)
    //    {
    //        $gross = 0;
    //        $retComponents = [];
    //        foreach ($components as $component) {
    //            if ($basic_id == $component->component_id) {
    //                $component->value = $basic;
    //            }
    //            if ($component->type == 2) {
    //                $amount = (($component->value * $basic) / 100);
    //                $gross = $gross + $amount;
    //                $component->{'amount'} = $amount;
    //            } else {
    //                $gross = $gross + $component->value;
    //                $component->{'amount'} = $component->value;
    //            }
    //
    //            $retComponents[] = $component;
    //        }
    //
    //        return (object)[
    //            'gross' => round($gross),
    //            'components' => $retComponents,
    //        ];
    //    }

    public function grossCpmponents($components = [], $basic = 0, $basic_id = false, $salary_type = false)
    {
        $gross = 0;
        $retComponents = [];
        $houseRentId = configs(['house_rent_id']);
        $house = collect($components)->firstWhere('component_id', $houseRentId);
        $houseValue = $house ? $house->value : 0;
        $houseRent = (int)(($basic * $houseValue) / 100);
        $extraAmount = (int)($basic - $houseRent);

        foreach ($components as $component) {
            if ($basic_id == $component->component_id) {
                $component->value = $basic;
            }
            if ($salary_type == 2) {
                if ($component->component_id == $basic_id) {
                    $component->amount = $basic;
                } elseif ($component->type == 2) {
                    // If it's house rent → use full basic, else → use extra amount
                    $base = ($component->component_id == $houseRentId) ? $basic : $extraAmount;
                    $amount = ($component->value * $base) / 100;
                    $component->amount = ($amount);
                    $gross += $amount;
                } else {
                    $component->amount = ($component->value);
                    $gross += $component->value;
                }
            } else {
                if ($component->type == 2) {
                    $amount = ($component->value * $basic) / 100;
                    $component->amount = ($amount);
                    $gross += $amount;
                } else {
                    $component->amount = ($component->value);
                    $gross += $component->value;
                }
            }
            $retComponents[] = $component;
        }
        return (object)[
            'gross' => round($gross),
            'components' => $retComponents,
        ];
    }


    public function staffCurrentSalary($pbi_id = false, $desg_id = false, $retuenOnlyAll = false)
    {
        $staffId = $pbi_id ? $pbi_id : request()->input('pbi_id');
        $currentSalary = DB::table('employee_basics')->where('emp_id', $staffId)->where('status', 1)->first();
        if ($currentSalary->salary_details) {
            if ($currentSalary && isset($currentSalary->salary_details)) {
                $currentSalary->salary_details = json_decode($currentSalary->salary_details, true);
            }
            return returnData(2000, $currentSalary->salary_details);
        }
        $designation = $desg_id ? $desg_id : request()->input('desg_id');

        if ($designation) {
            $basicInfo = DB::table('personnel_basic_info')->where('PBI_ID', $staffId)->first();
            $designation = $basicInfo->PBI_DESIGNATION;
        }

        $employeeBasics = DB::table('employee_basics')
            ->join('salary_slabs', function ($join) {
                $join->on('employee_basics.slab_id', '=', 'salary_slabs.id');
                $join->where('employee_basics.salary_type', '=', DB::raw("salary_slabs.salary_type"));
            })
            ->where('employee_basics.status', 1)
            ->where('employee_basics.emp_id', $staffId)
            ->select('salary_slabs.basic', 'salary_slabs.pay_scale', 'employee_basics.slab_id', 'employee_basics.salary_type')
            ->first();

        $config = new ConfigurationController();
        $gradeData = $config->getGeneralData(['gradeWiseComponent' => [
            'DESG_ID' => $designation,
            'pay_scale' => $employeeBasics ? $employeeBasics->pay_scale : 0,
            'slab_id' => $employeeBasics ? $employeeBasics->slab_id : 0,
            'salary_type' => $employeeBasics ? $employeeBasics->salary_type : 0,
        ]], true);

        if ($retuenOnlyAll) {
            return $gradeData;
        }

        return returnData(2000, $gradeData);
    }


    public function transferableStuffs($type = 'branch', $keyword = false, $designation = false, $functional_designation = false, $perPage = 'all', $EDU_QUALIFICATION = false, $districts_id = false, $upazila_id = false, $service_length = false, $sector = false, $domain = false, $zone = false, $area = false, $branch = false)
    {
        $transferConfig = (object)configs(['zone_layer_number', 'zone_transferable_month', 'branch_layer_number', 'branch_transferable_month', 'microfinance_sector']);

        if ($type == 'zone') {
            $groupColumn = "personnel_basic_info.PBI_ZONE";
            $serviceLength = !$service_length ? $transferConfig->zone_transferable_month : $service_length;
        } else {
            $groupColumn = "personnel_basic_info.PBI_BRANCH";
            $serviceLength = !$service_length ? $transferConfig->branch_transferable_month : $service_length;
        }

        $transferable_stuff = BasicInfoModel::selectRaw("personnel_basic_info.*,
            MAX(TRANSFER_AFFECT_DATE) as last_transfer_date,
            CONCAT(
                TIMESTAMPDIFF(YEAR, MAX(TRANSFER_AFFECT_DATE), CURDATE()), ' years ',
                TIMESTAMPDIFF(MONTH, MAX(TRANSFER_AFFECT_DATE), CURDATE()) % 12, ' months ',
                DATEDIFF(CURDATE(), DATE_ADD(MAX(TRANSFER_AFFECT_DATE), INTERVAL TIMESTAMPDIFF(MONTH, MAX(TRANSFER_AFFECT_DATE), CURDATE()) MONTH)), ' days'
                ) as length
            ")
            ->with(
                'department:DEPT_ID,DEPT_SHORT_NAME',
                'designation:DESG_ID,DESG_SHORT_NAME',
                'ths_designation:id,thsDesignationType',
                'tti_designation:id,ttiDesignationType',
                'tpsc_designation:id,tpscDesignationType',
                'cb_designation:id,cb_designation',
                'ps_designation:id,ps_designation_type',
                'region:region_id,region_name',
                'sector:id,sector_name',
                'domain:DOMAIN_CODE,DOMAIN_SHORT_NAME',
                'zone:ZONE_CODE,ZONE_NAME',
                'area:AREA_CODE,AREA_NAME',
                'branch:BRANCH_ID,BRANCH_NAME',
                'functional_designation:id,functional_designation',
                'last_education:EDU_QUA_CODE,EDU_QUA_DESC',
                'permanent_address:pbi_id,district_id,upazila_id',
                'permanent_address.district:id,name',
                'permanent_address.upazila:id,name',
                'project:PROJECT_ID,PROJECT_DESC',
                'employee_types:id,employee_type'
            )
            ->when($designation, function ($query) use ($designation) {
                $query->where('DESG_ID', $designation);
            })
            ->when($functional_designation, function ($query) use ($functional_designation) {
                $query->where('personnel_basic_info.functional_designation', $functional_designation);
            })
            ->when($EDU_QUALIFICATION, function ($query) use ($EDU_QUALIFICATION) {
                $query->where('personnel_basic_info.PBI_EDU_QUALIFICATION', $EDU_QUALIFICATION);
            })
            ->when($districts_id, function ($query) use ($districts_id) {
                $query->whereHas('permanent_address', function ($q) use ($districts_id) {
                    $q->where('district_id', $districts_id);
                });
            })
            ->when($upazila_id, function ($query) use ($upazila_id) {
                $query->whereHas('permanent_address', function ($q) use ($upazila_id) {
                    $q->where('upazila_id', $upazila_id);
                });
            })
            ->when($sector, function ($query) use ($sector) {
                $query->where('personnel_basic_info.sector_id', $sector);
            })
            ->when($domain, function ($query) use ($domain) {
                $query->where('personnel_basic_info.PBI_DOMAIN', $domain);
            })
            ->when($zone, function ($query) use ($zone) {
                $query->where('personnel_basic_info.PBI_ZONE', $zone);
            })
            ->when($area, function ($query) use ($area) {
                $query->where('personnel_basic_info.PBI_AREA', $area);
            })
            ->when($branch, function ($query) use ($branch) {
                $query->where('personnel_basic_info.PBI_BRANCH', $branch);
            })
            ->join('transfer_detail', function ($join) use ($type) {
                $join->on('personnel_basic_info.PBI_ID', '=', 'transfer_detail.PBI_ID');
                if ($type == 'branch') {
                    $join->whereNotNull('transfer_detail.TRANSFER_PRESENT_BRANCH');
                    $join->whereNotNull('personnel_basic_info.PBI_BRANCH');
                }
                if ($type == 'zone') {
                    $join->whereNull('transfer_detail.TRANSFER_PRESENT_BRANCH');
                    $join->whereNull('personnel_basic_info.PBI_BRANCH');
                }
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereRaw("lpad(personnel_basic_info.PBI_ID, 6, 0) LIKE '%$keyword%'")
                    ->orWhere('personnel_basic_info.PBI_NAME', 'Like', "%$keyword%")
                    ->orWhere('personnel_basic_info.PBI_EMAIL', 'Like', "%$keyword%");
            })
            ->groupBy("personnel_basic_info.PBI_ID")
            ->groupBy($groupColumn)
            ->where('PBI_JOB_STATUS', 'In Service')
            ->where('sector_id', $transferConfig->microfinance_sector)
            ->havingRaw("(DATEDIFF(CURDATE(), last_transfer_date) / 365) >= $serviceLength")
            ->paginate(function ($total) use ($perPage) {
                return ($perPage == 'all') ? $total : $perPage;
            });

        return $transferable_stuff;
    }

    public function deleteAddRawData($modelOrTable, $ids, $pbiColumn = '', $primaryKey = 'id')
    {
        if (empty($pbiColumn)) {
            return false;
        }

        $user = auth()->guard('applicant')->user();
        $pbi_id = (isset($user->pbi_id) && $user->pbi_id) ? $user->pbi_id : $user->initial_pbi_id;
        $dataCount = $modelOrTable->whereNotIn($modelOrTable->getKeyName(), $ids)->where($pbiColumn, $pbi_id)->count();

        if ($dataCount <= 10) {
            $modelOrTable->whereNotIn($modelOrTable->getKeyName(), $ids)->where($pbiColumn, $pbi_id)->delete();
            //            DB::table('addresses')->whereNotIn('guarantors_id', $ids)->where('pbi_id', $pbi_id)->delete();
        }

        return true;
    }

    public function addApproval($data)
    {
        try {
            $criteria = [
                'type_id' => $data['type_id'],
                'layer' => $data['layer'],
                'pbi_id' => $data['pbi_id'],
            ];
            $approval = Approval::where($criteria)->first();
            if ($approval) {
                $approval->fill($data);
            } else {
                $approval = new Approval();
                $approval->fill($data);
            }
            $approval->type = $data['type'];
            $approval->save();

            return $approval;
        } catch (\Exception $exception) {
            Log::error('Error in addApproval: ' . $exception->getMessage());
            return false;
        }
    }

    public function storeNote($noteData = null)
    {
        try {
            // Check if $noteData is null, if so, use the Request data
            if (is_null($noteData)) {
                $request = request();
                $noteData = $request->except('_token');
            }

            $reqType = $noteData['reqType'] ?? null;
            $data = array_diff_key($noteData, array_flip(['reqType']));

            $exist = StaffNote::where('type', $noteData['type'])
                ->where('pbi_id', $noteData['pbi_id'])
                ->where('type_id', $noteData['type_id'])
                ->first();

            if ($reqType === 'remove') {
                if ($exist) {
                    $exist->delete();
                }
                return returnData(2000, null, 'Successfully Removed');
            }

            if ($exist) {
                $exist->content = $noteData['content'] ?? $exist->content;
                $exist->save();
            } else {
                $note = new StaffNote();
                $note->fill($data);
                $note->save();
            }

            return returnData(2000, null, 'Successfully Saved');
        } catch (\Exception $e) {
            return returnData(5000, $e->getMessage(), 'Something Wrong');
        }
    }
    //    public function createUpdateApproval($type, $formData, $manager)
    //    {
    //        try {
    //            $baseData = [
    //                'type' => $type,
    //                'type_id' => $formData['type_id'],
    //                'layer' => $manager->layer_type,
    //                'pbi_id' => $manager->pbi_id,
    //                'approval_type' => $manager->approval_type,
    //            ];
    //
    //            $approvals = Approval::where($baseData)->first();
    //
    //            if ($approvals){
    //                $approvals->fill($baseData);
    //            }else{
    //                $approvals = new Approval();
    //                $approvals->fill($baseData);
    //            }
    //
    //            $approvals->approval_type = $manager->resignation_request;
    //            $approvals->comment = $formData['comment'];
    //            $approvals->date = now();
    //            $approvals->save();
    //
    //            return (object) ['status' => true];
    //
    //        }catch (\Exception $exception){
    //            return (object) ['status' => false];
    //        }
    //
    //    }
    public function getApprovals($apprType = null, $appTypeId = null, $authResponsibility = null)
    {
        try {
            $type = $apprType ? $apprType : request()->input('type');

            $type_id = $appTypeId ? $appTypeId : request()->input('type_id');
            $responsibility = $authResponsibility ? $authResponsibility : request()->input('responsibility');

            $approvals = DB::table('approvals')
                ->selectRaw("approvals.*, personnel_basic_info.PBI_NAME as name, CONCAT(designationtype.DESG_SHORT_NAME, ' (',level_name_manages.value,')') as designation")
                ->leftJoin('designationtype', 'approvals.designation_id', '=', 'designationtype.DESG_ID')
                ->leftJoin('personnel_basic_info', 'approvals.pbi_id', '=', 'personnel_basic_info.PBI_ID')
                ->leftJoin('level_name_manages', 'level_name_manages.priority', '=', 'approvals.layer')
                ->where('approvals.type', $type)
                ->where('approvals.type_id', $type_id)
                ->orderBY('layer', 'ASC')
                ->get();

            $manager = Manager::where('pbi_id', auth()->user()->pbi_id)->first();

            $existNote = DB::table('staff_notes')->where('type', $type)->where('type_id', $type_id)->first();
            if (!$existNote || !$existNote->memo_prefix) {
                // jisan commit and new
                // $anyNote = DB::table('staff_notes')->where('type', $type)->orderBy('memo_no', 'DESC')->first();

                $anyNote = DB::table('memo_prefix')->where('status', 1)->where('prefix_type', $type)->first();
            }

            $memo_prefix = ($existNote && $existNote->memo_prefix) ? $existNote->memo_prefix : ($anyNote ? $anyNote->memo_prefix : '');
            // jisan commit and new
            // $memo_no = ($existNote && $existNote->memo_no) ? $existNote->memo_no : ($anyNote ? $anyNote->memo_no + 1 : 1);
            $memo_no = ($existNote && $existNote->memo_no) ? $existNote->memo_no : ($anyNote ? $anyNote->initial_number + 1 : 1);

            $noteId = $existNote ? $existNote->id : '';



            $responsibilityPermission = $manager ? ($manager->{$responsibility} == 3 ? 0 : $manager->{$responsibility}) : 0;

            $ownApprovals = collect($approvals)->where('pbi_id', auth()->user()->pbi_id)->first();

            if ($ownApprovals) {
                $ownApprovals->{'memo_prefix'} = $memo_prefix;
                $ownApprovals->{'memo_no'} = $memo_no;
                $ownApprovals->{'note_id'} = $noteId;
                $ownApprovals->{'approval_type'} = $responsibilityPermission;
                $ownApprovals->{'responsibility'} = $responsibility;
            }

            $defaultData = ['type' => $type, 'type_id' => $type_id, 'memo_prefix' => $memo_prefix, 'memo_no' => $memo_no, 'note_id' => $noteId, 'approval_type' => $responsibilityPermission, 'responsibility' => $responsibility, 'date' => date('Y-m-d')];

            $data['own'] = $ownApprovals ? $ownApprovals : $defaultData;
            $data['approvals'] = $approvals;

            if ($apprType && $appTypeId) {
                return $data['own'];
            }

            return returnData(2000, $data);
        } catch (Exception $exception) {
            return returnData(2000, ['own' => [], 'approvals' => []]);
        }
    }

    public function submitApprovals()
    {
        $data = request()->all();
        // dd($data);
        $basicInfo = DB::table('personnel_basic_info')->where('PBI_ID', auth()->user()->pbi_id)->first();
        $manager = DB::table('managers')->where('pbi_id', auth()->user()->pbi_id)->first();
        $approvalType = $data['approval_type'] ?? ($manager ? $manager->joining_letter : '');
        DB::beginTransaction();
        $dateformare = DateTime::createFromFormat('m/d/Y', $data['date']);
        $date = $dateformare->format('Y-m-d H:i:s');

        $approval = $this->addApproval([
            'type' => $data['type'],
            'type_id' => $data['type_id'],
            'layer' => $manager ? $manager->layer_type : '',
            'pbi_id' => \auth()->user()->pbi_id,
            'comment' => $data['comment'],
            'date' => $date,
            'designation_id' => $basicInfo ? $basicInfo->PBI_DESIGNATION : '',
            'approval_type' => $approvalType,
            'name' => $basicInfo ? $basicInfo->PBI_NAME : '',
            'status' => $data['approval_type'],
        ]);

        if (!$approval) {
            DB::rollBack();
            return returnData(5000, null, 'Forward/Approve Failed');
        }

        if ($data['responsibility'] == 'joining_letter' && $data['approval_type'] == 1) {
            DB::table('joinings')
                ->where('id', $data['type_id'])
                ->update([
                    'status' => 1,
                ]);
        }

        if ($data['approval_type']) {
            $noteData = DB::table('staff_notes')->where('type_id', $data['type_id'])
                ->where('type', $data['type'])
                ->first();
            if ($noteData) {
                DB::table('staff_notes')
                    ->where('type_id', $data['type_id'])
                    ->update([
                        'memo_prefix' => $data['memo_prefix'],
                        'memo_no' => $data['memo_no'],
                    ]);
            }
        }

        if ($data['type'] == '7' && $data['approval_type'] == 1) {
            $pbiId = DB::table('not_in_service_requests')->where('id', $data['type_id'])->first();
            $updatePbi = BasicinfoModel::findOrFail($pbiId->pbi_id);
            $resign_date = convertDateFormat($data['actual_resign_date']);
            $received_date = convertDateFormat($data['hrm_received_date']);
            $userAdmin = DB::table('admins')->where('pbi_id', $pbiId->pbi_id)->first();
            DB::table('not_in_service_requests')->where('id', $data['type_id'])->update([
                'separation_type_id' => $data['separation_type'],
                'actual_resign_date' => $resign_date,
                'hrm_received_date' => $received_date,
                'status' => 1,
            ]);
            $updatePbi->update([
                'PBI_JOB_STATUS' => 'Not In Service',
                'PBI_separation_type' => $data['separation_type'],
                'not_in_service_reason' => $pbiId->not_in_service_reason_id,
                'resign_date' => $data['actual_resign_date'],
                'date_of_application' => $pbiId->submission_date,
                'received_date_on_hrm' => $data['hrm_received_date'],
                'employee_approve' => 3
            ]);
            if ($userAdmin) {
                DB::table('admins')->where('pbi_id', $pbiId->pbi_id)->delete();
            }
        }
        DB::commit();
        $approvalData = $this->getApprovals($data['type'], $data['type_id'], $data['responsibility']);
        return returnData(2000, $approvalData, 'Successfully Submitted');
    }

    public function categoryWiseLeave($staffId = false, $basicInfo = false, $filters = [])
    {
        $userId =  $staffId ? $staffId : auth()->user()->pbi_id;
        $empType = $basicInfo ? $basicInfo :  DB::table('personnel_basic_info')->where('PBI_ID', $userId)->select('employee_type', 'PBI_DOJ')->first();

        if (!$empType) {
            return returnData(5000, null, "Employee Type not Found");
        }

        $earnLeaveCatId = (object)configs(['earn_leave_category', 'employee_type_leave', 'earn_leave_per_office_day']);

        $joiningDate = Carbon::parse($empType->PBI_DOJ);
        $currentDate = Carbon::now();

        $holidayDays = DB::table('holiday_configurations')
            ->whereBetween('holiday_date', [$joiningDate->toDateString(), $currentDate->toDateString()])
            ->where('status', 1)
            ->count();

        $daysDifference = $joiningDate->diffInDays($currentDate);
        $monthsDifference = $joiningDate->diffInMonths($currentDate);
        $officeDays = $daysDifference - $holidayDays;
        $actualLeave = round($officeDays / $earnLeaveCatId->earn_leave_per_office_day);

        $total_leave_days = DB::table('leave_categories')
            ->leftJoin('leaves', function ($query) use ($userId) {
                $query->on('leave_categories.id', '=', 'leaves.leave_category_id');
                $query->where('leaves.pbi_id', $userId);
            })
            ->leftJoin('leave_requests', function ($query) {
                $query->on('leaves.id', '=', 'leave_requests.leave_id');
                $query->where('leave_requests.status', 2);
            })
            ->leftJoin('leave_recalls', function ($query) {
                $query->on('leaves.id', '=', 'leave_recalls.leave_id');
                $query->where('leave_recalls.status', 1);
            })
            ->leftJoin('leave_in_worth as lw', function ($join) use ($earnLeaveCatId, $userId, $currentDate) {
                $join->where('lw.PBI_ID', $userId)->where('category_id', $earnLeaveCatId->earn_leave_category);
                $join->where('year', $currentDate->year);
            })
            ->when(($empType->employee_type != $earnLeaveCatId->employee_type_leave), function ($query) use ($empType, $monthsDifference) {
                $query->selectRaw("leave_categories.id,
                            leave_categories.title,
                            leave_categories.note,
                            (leave_categories.total_leave / 12 * $monthsDifference) as prorated_leave,
                            ROUND(SUM(leaves.no_of_days)) as applied_leave,
                            CASE WHEN leave_requests.no_of_days IS NOT NULL THEN SUM(leave_requests.no_of_days) ELSE 0 END -
                            CASE WHEN leave_recalls.recall_total_d IS NOT NULL THEN SUM(leave_recalls.recall_total_d) ELSE 0 END as approved,
                            CASE WHEN leave_recalls.recall_total_d IS NOT NULL THEN SUM(leave_recalls.recall_total_d) ELSE 0
                            END as recalled_leave,
                            (leave_categories.total_leave / 12 * $monthsDifference) -
                            ( CASE  WHEN leave_requests.no_of_days IS NOT NULL THEN SUM(leave_requests.no_of_days) ELSE 0 END - CASE  WHEN leave_recalls.recall_total_d IS NOT NULL THEN SUM(leave_recalls.recall_total_d) ELSE 0 END
                            ) as balance
                        ");
            })
            ->when(($empType->employee_type == $earnLeaveCatId->employee_type_leave), function ($query) use ($actualLeave, $earnLeaveCatId) {

                $query->selectRaw("
                    leave_categories.id,
                    leave_categories.title,
                    leave_categories.total_leave,
                    ROUND(SUM(leaves.no_of_days)) as applied_leave,
                    CASE WHEN leave_requests.no_of_days IS NOT NULL THEN SUM(leave_requests.no_of_days) ELSE 0 END -
                    CASE WHEN leave_recalls.recall_total_d IS NOT NULL THEN SUM(leave_recalls.recall_total_d) ELSE 0 END as approved,
                    CASE WHEN leave_recalls.recall_total_d IS NOT NULL THEN SUM(leave_recalls.recall_total_d) ELSE 0 END as recalled_leave,

                    CASE WHEN leave_categories.id = $earnLeaveCatId->earn_leave_category THEN CASE WHEN $actualLeave > leave_categories.total_leave THEN leave_categories.total_leave ELSE $actualLeave END - COALESCE(lw.leave_avail, 0)

                    ELSE leave_categories.total_leave - ( COALESCE(SUM(leave_requests.no_of_days), 0) - COALESCE(SUM(leave_recalls.recall_total_d), 0))
                    END AS balance
                ");
            })
            ->when(!empty($filters), function ($query) use ($filters) {
                $query->where($filters);
            })
            ->where('leave_categories.typed_id', $empType->employee_type)
            ->groupBy('leave_categories.id')
            ->get();

        return  $total_leave_days;
    }
}
