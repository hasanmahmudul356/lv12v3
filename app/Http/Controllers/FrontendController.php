<?php

namespace App\Http\Controllers;


use App\Helpers\Helper;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FrontendController extends Controller
{
    use Helper;
    public function authApp(){
        return view('frontend');
    }


    public function index()
    {

        return view('web.index');
    }

    public function login()
    {
        $data['next_url'] = \request()->input('next_url');
        return view('web.auth.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $creadiantials = [
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
        ];
        if (Auth::guard( 'auth')->attempt($creadiantials)) {
            session()->flash('success', 'Successfully Login');
            return redirect('auth/dashboard');
        }

        session()->flash('error', 'Credentials did not match');
        return redirect()->back();
    }

    public function doRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:web_users,email',
            'phone' => 'required|string|min:11|max:11|unique:web_users,phone',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password'
        ]);


        try {
            DB::beginTransaction();
            $user = new WebUser();
            $user->fill($request->all());
            $user->password = Hash::make($request->input('password'));
            $user->save();


            DB::commit();
            session()->flash('success', 'Registration Successful');
            return redirect("login");
        } catch (\Exception $exception) {
            DB::rollBack();
            session()->flash('error', 'Something went wrong, please try again');
            return redirect()->back()->withInput();
        }
    }

    public function register()
    {
        return view('web.auth.register');
    }

    public function profile()
    {
        $data['webUser'] = Auth::guard('webUser')->user();
        return view('web.layouts.master',$data);

    }
}
