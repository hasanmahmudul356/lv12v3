<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    public function index(){
        $user = auth()->user();
        return returnData(2000, $user);
    }
    public function store(Request $request)
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return returnData(5000, null, 'User Not Found');
        }

        $reqFor = $request->input('request');

        if ($reqFor === 'theme') {
            $request->validate(['theme' => 'required|string']);
            $user->theme = $request->input('theme');
            $user->save();

            return returnData(2000, $user, 'Successfully Theme Updated');
        }

        if ($reqFor === 'locale') {
            $request->validate(['locale' => 'required|string|in:en,bn,ar']);

            $user->locale = $request->input('locale');
            $user->save();

            return returnData(2000, $user, 'Successfully Locale Updated');
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'theme' => 'nullable|string',
        ]);

        $user->update($request->only(['name','email','phone','theme']));
        return returnData(2000, $user, 'Successfully Updated');
    }

}
