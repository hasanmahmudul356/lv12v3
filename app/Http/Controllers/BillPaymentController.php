<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\BillPayment;
use App\Models\MeterType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillPaymentController extends Controller
{
    use Helper;

    public function __construct()
    {
//        if (!can(request()->route()->action['as'])) {
//            return returnData(5001, null, 'You are not authorized to access this page');
//        }
        $this->model = new BillPayment();
    }

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $input = $request->all();

            // logged in user id insert
            $input['user_id'] = auth()->id();

            $now = Carbon::now('Asia/Dhaka');
            $input['receipt_no'] = $now->format('dmyHi');

            $validate = $this->model->validate($input);
            if ($validate->fails()) {
                return returnData(2000, $validate->errors());
            }

            $this->model->fill($input);
            $this->model->save();

            return returnData(2000, null, 'Successfully Inserted');

        } catch (\Exception $exception) {
            return returnData(5000, $exception->getMessage(), 'Whoops, Something Went Wrong..!!');
        }
    }


    public function show(BillPayment $billPayment)
    {
        //
    }


    public function edit(BillPayment $billPayment)
    {
        //
    }


    public function update(Request $request, BillPayment $billPayment)
    {
        //
    }


    public function destroy(BillPayment $billPayment)
    {
        //
    }
}
