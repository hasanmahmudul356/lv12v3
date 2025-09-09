<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\BillInformation;
use Illuminate\Http\Request;

class BillInformationController extends Controller
{
    use Helper;

    public function __construct()
    {
        $this->model = new BillInformation();
    }

    public function index()
    {
        try {
            $keyword = request()->input('keyword');
            $data = $this->model
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('name', 'Like', "%$keyword%");
                })
                ->paginate(input('perPage'));

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

            $exist= $this->model->where('meter_no', $input['meter_no'])->where('billing_month', $input['billing_month'])->first();
            if ($exist) {
                return returnData(5000, null,'Already Bill Generate');
            }

            $this->model->fill($input);
            $this->model->user_id = auth()->user()->id;
            $this->model->save();

            if ($request->has('enargy_calculate') && is_array($request->enargy_calculate)) {
                foreach ($request->enargy_calculate as $energy) {
                    if (in_array($energy['type'], [1, 2])) {
                        $this->model->energyBills()->create([
                            'meter_no'      => $input['meter_no'],
                            'type'          => $energy['type'],
                            'customer_unit' => $energy['customer_unit'],
                            'unit_rate'     => $energy['unit_rate'],
                            'bill_amount'   => $energy['bill_amount']
                        ]);
                    }
                }
            }

            return returnData(2000, null, 'Successfully Inserted');

        } catch (\Exception $exception) {
            return returnData(5000, $exception->getMessage(), 'Whoops, Something Went Wrong..!!');
        }

    }

    public function show($id)
    {
        //
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
            $data = $this->model->where('id', $id)->first();
            if ($data) {
                $data->delete();
                return returnData(2000, $data, 'Successfully Deleted');
            }
            return returnData(5000, null, 'Data Not found');

        } catch (\Exception $exception) {
            return returnData(5000, $exception->getMessage(), 'Whoops, Something Went Wrong..!!');
        }
    }
}
