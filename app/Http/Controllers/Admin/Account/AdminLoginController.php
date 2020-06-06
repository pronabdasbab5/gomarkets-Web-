<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showAdminLoginForm(){
        return view('admin.login', ['url' => 'admin']);
    }

    public function adminLogin(Request $request){

        $this->validate($request, [
            'username'   => 'required',
            'password' => 'required'
        ]);

        if ((Auth::guard('admin')->attempt(['email' => $request->username, 'password' => $request->password])) || (Auth::guard('admin')->attempt(['contact_no' => $request->username, 'password' => $request->password]))) {
            return redirect()->intended('/admin/dashboard');
        }
        
        return back()->withInput($request->only('email'))->with('login_error','Username or password incorrect');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
