<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function doLogin(Request $request){
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            return redirect('app/dashboard');
        }

        return back()->withErrors(['login' => 'Invalid credentials']);
    }

    public function logout(){
        Auth::logout();
        return redirect(route('login'));
    }
}
