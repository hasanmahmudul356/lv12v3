<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        $data = DB::table('users')->paginate();
        return returnData(2000, $data, 'UnAuthenticate User', 'error');
    }
}
