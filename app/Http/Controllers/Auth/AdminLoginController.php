<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    public function loginForm()
    {
        return view('admin.login');
    }
    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::guard('admin')->attempt(array('email'=>$request->email,'password'=>$request->password),$request->remember)){
            return redirect()->intended(route('admin.home'));
        }
        return redirect()->back()->withInput($request->only('email','remember'));
    }

}
