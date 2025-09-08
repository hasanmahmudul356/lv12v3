<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use Helper;

    public function __construct()
    {
//        if (!can(request()->route()->action['as'])) {
//            return returnData(5001, null, 'You are not authorized to access this page');
//        }
        $this->model = new Notification();
    }

    public function index(){
        try {
            $keyword = request()->input('keyword');
            $data = $this->model->with(['customer'])
                ->when($keyword, function ($query) use ($keyword) {
                    $query->whereHas('customer', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    });
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
