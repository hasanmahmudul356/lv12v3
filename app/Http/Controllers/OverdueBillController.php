<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\BillInformation;
use App\Models\BillPayment;
use App\Models\Meter;
use App\Models\OverdueBill;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverdueBillController extends Controller
{
    use Helper;

    public function __construct()
    {
//        if (!can(request()->route()->action['as'])) {
//            return returnData(5001, null, 'You are not authorized to access this page');
//        }
        $this->model = new OverdueBill();
    }

    public function index()
    {
        try {
            $keyword = request()->input('keyword');
            $data = $this->model
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('meter_no', 'Like', "%$keyword%");
                })
                ->paginate(input('perPage'));

            return returnData(2000, $data);
        } catch (\Exception $exception) {
            return returnData(5000, $exception->getMessage(), 'Whoops, Something Went Wrong..!!');
        }

    }
    public function overDue(Request $request)
    {
        $bill_info = BillInformation::where('meter_no', $request->meter_no)
            ->where('billing_month', $request->billing_month)
            ->first();

        if (!empty($bill_info)) {
            $bill_payment_info = BillPayment::where('meter_no', $request->meter_no)
                ->where('bill_month', $request->billing_month)
                ->first();

            $bill = [];

            if ($bill_payment_info) {
                $due_amount = $bill_info->bill_amount - $bill_payment_info->payment_amount;

                $bill['due_amount'] = $due_amount;
                $bill['penalty_due'] = $due_amount != 0.00 ? 20 : 0;
                $bill['total_due_bill'] = $due_amount + $bill['penalty_due'];
            } else {
                $bill['due_amount'] = $bill_info->bill_amount;
                $bill['penalty_due'] = 50;
                $bill['total_due_bill'] = $bill['due_amount'] + $bill['penalty_due'];
            }

            return returnData(2000, $bill);
        }

        return returnData(3000, [], 'Cannot Be found Bill');
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try{

            $validator = $this->model->validate($request->all());

            if ($validator->fails()) {
                return returnData(3000, $validator->errors());
            }
            $this->model->fill($request->all());
            $this->model->user_id = auth()->user()->id;
            $this->model->save();

            return returnData(2000, null, 'Successfully Inserted');
        }catch (\Exception $exception){
            return returnData(5000, $exception, 'Not Inserted');
        }

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
