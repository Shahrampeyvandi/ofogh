<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    
    public function index()
    {
        return view('FrontEnd.login');

    }

    public function Login(Request $request)
    {
     
        $user = Admin::where('mobile', $request->username)->first();

        if($user){
            if(Hash::check($request->input('password'),$user->password))
             {
                if ($request->has('rememberme')) {
                    Auth::guard('admin')->Login($user,true);
                    $request->session()->flash('Success', 'ورود با موفقیت انجام شد .');
                return redirect()->route('Dashboard');
                } else {
                    Auth::guard('admin')->Login($user);
                    $request->session()->flash('Success', 'ورود با موفقیت انجام شد .');
                    return redirect()->route('Dashboard');
                }
              
            } else {
                $request->session()->flash('Error', 'رمز عبور شما صحیح نمی باشد .');
                return back();
            }
        } else {
            $request->session()->flash('Error', ' نام کاربری وارد شده اشتباه است');
            return back();
        }
    }

    public function LogOut()
    {
        
            Auth::guard('admin')->logout();
            return redirect()->route('BaseUrl');
        
    }
}
