<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
class AdminRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    public function registrationForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $validation = $this->validator($request->all())->validate();
        $user = $this->create($request->all());

        if(Auth::guard('admin')->attempt(array('email'=>$request->email,'password'=>$request->password),$request->remember)){
            return redirect()->intended(route('admin.home'));
        }
        return redirect()->back()->withInput($request->only('email','name'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function create(array $data)
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'user_type' => $data['user_type'],
            'password' => Hash::make($data['password']),
        ]);
    }

}
