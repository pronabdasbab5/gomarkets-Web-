<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class LocationController extends Controller
{
    public function stateFetching()
    {
        $state_list = DB::table('states')
            ->where('status', 1)
            ->get();

        if (!empty($state_list) && (count($state_list) > 0)) {

	    	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => $state_list,
	            'message' => 'State list has been retrive successfully'
	        ];
        } else {

        	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => [],
	            'message' => 'No State available'
	        ];
        }
        
    	return response()->json($response, 200);  
    }

    public function districtFetching($state_id)
    {
        $district_list = DB::table('district')
        	->where('state_id', $state_id)
            ->where('status', 1)
            ->get();

        if (!empty($district_list) && (count($district_list) > 0)) {

	    	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => $district_list,
	            'message' => 'District list has been retrive successfully'
	        ];
        } else {

        	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => [],
	            'message' => 'No District available'
	        ];
        }
        
    	return response()->json($response, 200);  
    }

    public function fetchSubDistrict(Request $request)
    {
        $sub_district_list = DB::table('sub_district')
            ->where('district_id', $request->input('district_id'))
            ->where('status', 1)
            ->get();

        if (!empty($sub_district_list) && (count($sub_district_list) > 0)) {

            $response = [
                'code' => 200,
                'status' => true,
                'data' => $sub_district_list,
                'message' => 'Sub-District list has been retrive successfully'
            ];
        } else {

            $response = [
                'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'No Sub-District available'
            ];
        }
        
        return response()->json($response, 200);  
    }

    public function fetchArea(Request $request)
    {
        $area_list = DB::table('area')
            ->where('sub_district_id', $request->input('sub_district_id'))
            ->where('status', 1)
            ->get();

        if (!empty($area_list) && (count($area_list) > 0)) {

            $response = [
                'code' => 200,
                'status' => true,
                'data' => $area_list,
                'message' => 'Area list has been retrive successfully'
            ];
        } else {

            $response = [
                'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'No Area available'
            ];
        }
        
        return response()->json($response, 200);  
    }
}
