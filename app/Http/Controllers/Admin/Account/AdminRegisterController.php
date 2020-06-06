<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class AdminRegisterController extends Controller
{
    public function showAdminRegisterForm()
    {
        return view('admin.register', ['url' => 'admin']);
    }

    protected function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_no' => 'required|numeric',
            'password' => 'required'
        ]);
        
        /** Check if user already exist **/
        $user_cnt = DB::table('admin')
            ->where('email', $request->input('email'))
            ->orWhere('contact_no', $request->input('contact_no'))
            ->count();

        if($user_cnt > 0){
            $msg = "Your already registered";
        } else {
            /** Registering User **/
            $admin = Admin::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'contact_no' => $request['contact_no'],
                'password' => Hash::make($request['password']),
            ]);

            $msg = "Registration has been done succesfully";
        }

        return redirect()->back()->with('msg', $msg);
    }
}
