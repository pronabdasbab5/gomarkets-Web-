<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class LoginController extends Controller
{
    public function userLogin(Request $request) 
    {
  		if (!empty($request->input('user_name')) && !empty($request->input('password'))) {

            $user_data = DB::table('users')
                    ->where('contact_no', $request->input('user_name'))
                    ->where('password', $request->input('password'))
                    ->count();

            if ($user_data > 0) {

                $user_data = DB::table('users')
                	->where('contact_no', $request->input('user_name'))
                	->first();

                $api_key_count = DB::table('api_key')
                	->where('user_id', $user_data->id)
                	->count();

                if ($api_key_count > 0) {

                    $api_key = uniqid('api');
                    
                    DB::table('api_key')
                    	->where('user_id', $user_data->id)
                    	->update(['api_key' => $api_key]);

                    $user_data->api_key = $api_key; 

                    $data = [];

                    $data [] = $user_data; 

                    $response = [
			            'code' => 200,
			            'status' => true,
			            'data' => $data,
			            'message' => 'Login Successfull'
			        ];

                    return response()->json($response, 200);
                }
                else {

                    $api_key = uniqid('api');

                    DB::table('api_key')
                    	->insert([
                    		'user_id' => $user_data->id,
                    		'api_key' => $api_key
                    	]);
                    
                    $user_data->api_key = $api_key; 

                    $data = [];

                    $data [] = $user_data; 

                    $response = [
			            'code' => 200,
			            'status' => true,
			            'data' => $data,
			            'message' => 'Login Successfull'
			        ];

                    return response()->json($response, 200);
                }
            } else {

                $response = [
			        'code' => 200,
			        'status' => false,
			        'data' => [],
			        'message' => 'Username or Password incorrect'
			    ];

                return response()->json($response, 200);
            }
  		}
  		else{

  			$response = [
			    'code' => 200,
			    'status' => false,
			    'data' => [],
			    'message' => 'Username or Password are required'
			];

            return response()->json($response, 200);
  		} 	
  	}
}
