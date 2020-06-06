<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function userRegistration (Request $request) {

    	if ($request->has('name') && $request->has('contact_no') && $request->has('password')) {

	        $user_count = DB::table('users')
                ->where('contact_no', $request->input('contact_no'))
                ->count();

            if ($user_count > 0) {

            	$response = [
			        'code' => 200,
			        'status' => false,
			        'data' => [],
			        'message' => 'Contact No. already registered'
			    ];

                return response()->json($response, 200);
            }

            DB::table('users')
	            ->insert([ 
	            	'name' => ucwords(strtolower($request->input('name'))), 
	            	'email' => $request->input('email'), 
	            	'contact_no' => $request->input('contact_no'), 
	            	'password' => $request->input('password'),
	            	'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
	            ]);

	        $response = [
			    'code' => 200,
			    'status' => true,
			    'message' => 'Registration Successfull'
			];

            return response()->json($response, 200);

    	} else {

    		$response = [
			    'code' => 200,
			    'status' => false,
			    'data' => [],
			    'message' => 'Please ! Provide Info.'
			];

            return response()->json($response, 200);
    	}
    }
}
