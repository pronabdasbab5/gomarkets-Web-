<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class UserController extends Controller
{
    public function updateUserProfile (Request $request) 
    {
        DB::table('users')
            ->where('id', $request->input('user_id'))
            ->update([
                'name' => ucwords(strtolower($request->input('name'))),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ]);

        $response = [
            'code' => 200,
            'status' => true,
            'message' => 'Your profile has been updated'
        ];

        return response()->json($response, 200);
    }

    public function fetchingUserProfile (Request $request) 
    {
        $user_info [] = DB::table('users')
            ->where('id', $request->input('user_id'))
            ->first();

        $response = [
            'code' => 200,
            'status' => true,
            'data' => $user_info,
            'message' => 'Your profile has been loaded'
        ];

        return response()->json($response, 200);
    }
}
