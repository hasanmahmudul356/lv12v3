<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function singleApp(){
        return view('backend');
    }
    public function employeeApp(){
        return view('backend');
    }
}
