<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\User;
class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function login_post(Request $request)
    {
        // $pass = Hash::make('123456789');
        // dd($pass);
        // dd($request->all());

        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password], true)){
            if(Auth::user()->is_role == 1){
                return redirect()->intended('admin/dashboard');
            }else if(Auth::user()->is_role == 2){
                return redirect()->intended('user/dashboard');
            }else{
                return redirect('/')->with('error','No available email.');
            }
        }else{
            return redirect()->back()->with('error','Invalid email or password.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
