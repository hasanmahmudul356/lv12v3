<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\EnergyBill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EnergyBillController extends Controller
{

    use Helper;
    public function __construct()
    {
//        if (!can(request()->route()->action['as'])) {
//            return returnData(5001, null, 'You are not authorized to access this page');
//        }
        $this->model = new EnergyBill();
    }

    public function index(){
        try {
            $keyword = request()->input('keyword');
            $data = $this->model
                ->when($keyword, function ($query) use ($keyword) {
                    $type = null;
                    if(strtolower($keyword) == 'generator') $type = 1;
                    elseif(strtolower($keyword) == 'solar') $type = 2;
                    if($type !== null){
                        $query->where('type', $type);
                    } else {
                        $query->where('billing_month', 'like', "%$keyword%")->orWhere('unit', 'like', "%$keyword%"); // optional
                    }
                })->paginate(input('perPage'));

            return returnData(2000, $data);
        } catch (\Exception $exception) {
            return returnData(5000, $exception->getMessage(), 'Whoops, Something Went Wrong..!!');
        }
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validate = $this->model->validate($input);
            if ($validate->fails()) {
                return returnData(2000, $validate->errors());
            }
            $exgistData = $this->model->where('type',$input['type'])
                ->where('billing_month',$input['billing_month'])
                ->where('status',1)->first();
            if ($exgistData){
                return returnData(3000, $exgistData, 'AlReady Exist ');
            }
            $month =Carbon::createFromFormat('Y-m', $input['billing_month']);
            $prevMonth = $month->subMonth()->format('Y-m');
            $previewsUnitRate = $this->model->where('type',$input['type'])
                ->where('billing_month',$prevMonth)
                ->where('status',1)->first();
//            $unitRate = $input['unit_rate'] ? $input['unit_rate']: $previewsUnitRate->unit_rate;
            $unitRate = $input['unit_rate'] ?? $previewsUnitRate->unit_rate;

//ddA($input['unit_rate']);
            $this->model->fill($input);
            $this->model->unit_rate = $unitRate;
            $this->model->user_id = auth()->user()->id;
            $this->model->save();

            return returnData(2000, null, 'Successfully Inserted');

        } catch (\Exception $exception) {
            return returnData(5000, $exception->getMessage(), 'Whoops, Something Went Wrong..!!');
        }

    }
    public function calculateCustomerUnit(Request $request)
    {
        $type = (int) $request->type;
        $unit = $request->unit;
        if ($type) {
            $data['totalCustomers'] = Customer::Orwhere('generator', $type)
                ->Orwhere('solar', $type )
                ->Orwhere('nesco', $type)->count();
        }

        $data['customerUnit'] = $data['totalCustomers'] > 0 ? $unit / $data['totalCustomers'] : 0;

        return returnData(2000, $data);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = $this->model->validate($request->all());

        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors(), 'status' => 3000], 200);
        }

        $data = $this->model->where('id', $request->input('id'))->first();

        if ($data) {
            $data->fill($request->all());
            $data->user_id = auth()->user()->id;
            $data->update();
            return returnData(2000, null, 'Successfully Updated');
        }

        return returnData(2000, null, 'Unsuccessful Updated');
    }

    public function destroy($id)
    {
        try {
            $data = $this->model->where('id',$id)->first();
            if ($data){
                $data->delete();

                return returnData(2000, $data, 'Successfully Deleted');

            }
            return returnData(3000, $data, 'Data Not Found');

        } catch (\Exception $exception) {
            return returnData(5000, $exception->getMessage(), 'Whoops, Something Went Wrong..!!');
        }
    }
}
