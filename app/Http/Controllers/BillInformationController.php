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
                ->leftJoin('meters', 'bill_informations.meter_id', '=', 'meters.id')
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('name', 'Like', "%$keyword%");
                })
                ->select('bill_informations.*','meters.meter_number')
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

            $this->model->fill($input);
            $this->model->user_id = auth()->user()->id;
            $this->model->save();

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
