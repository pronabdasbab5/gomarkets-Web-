<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class AddressController extends Controller
{
    public function addressFetching(Request $request)
    {
        $address = DB::table('address')
        	->leftJoin('sub_district', 'address.sub_district_id', '=', 'sub_district.id')
        	->leftJoin('area', 'address.area_id', '=', 'area.id')
        	->where('user_id', $request->input('user_id'))
        	->select('address.*', 'sub_district_name', 'area_name')
        	->first();

        $data [] = $address;

        if (!empty($address)) {

	    	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => $data,
	            'message' => 'Address has been fetch successfully'
	        ];
        } else {

        	$response = [
	            'code' => 200,
	            'status' => false,
	            'data' => [],
	            'message' => 'Please ! Add address'
	        ];
        }
        
    	return response()->json($response, 200);  
    }

    public function updateAddress (Request $request) {

    	$address_count = DB::table('address')
        	->where('user_id', $request->input('user_id'))
        	->count();

        $district_record = DB::table('district')
        	->where('id', $request->input('district_id'))
        	->first();

        if ($address_count > 0) {

        	DB::table('address')
            	->where('user_id', $request->input('user_id'))
	            ->update([
	                'address' => $request->input('address'),
	                'area_id' => $request->input('area_id'),
	                'sub_district_id' => $request->input('sub_district_id'),
	                'district_id' => $request->input('district_id'),
	                'state_id' => $district_record->state_id,
	                'pin_code' => $request->input('pin_code'),
	                'email' => $request->input('email'),
	                'mobile_no' => $request->input('mobile_no'),
	            ]);

	        $msg = 'Address has been upadated';
        } else {
        	DB::table('address')
	            ->insert([
	            	'user_id' => $request->input('user_id'),
	                'address' => $request->input('address'),
	                'area_id' => $request->input('area_id'),
	                'sub_district_id' => $request->input('sub_district_id'),
	                'district_id' => $request->input('district_id'),
	                'state_id' => $district_record->state_id,
	                'pin_code' => $request->input('pin_code'),
	                'email' => $request->input('email'),
	                'mobile_no' => $request->input('mobile_no'),
	            ]);

	        $msg = 'Address has been added';
        }

    	$response = [
			'code' => 200,
			'status' => true,
			'message' => $msg
		];

        return response()->json($response, 200);
    }
}
