<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(){
        return view('web.index');
    }

    public function login(){
        return view('web.auth.login');
    }
    public function register(){
        return view('web.auth.register');
    }
}
