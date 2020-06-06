<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class SubCategoryController extends Controller
{
    function subCategories($category_id)
    {
        $sub_categories = DB::table('sub_category')
            ->where('top_category_id', $category_id)
            ->where('status', 1)
            ->get();

        if (!empty($sub_categories) && (count($sub_categories) > 0)) {

	        foreach ($sub_categories as $key => $item){

	            $item->banner = asset('assets/sub_category/'.$item->banner);

	            $niece_categories = DB::table('niece_category')
		            ->where('sub_category_id', $item->id)
		            ->where('status', 1)
		            ->get();

		        $item->niece_categories = $niece_categories;
	        }

	    	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => $sub_categories,
	            'message' => 'Sub-Categories has been retrive successfully'
	        ];
        } else {

        	$response = [
	            'code' => 200,
	            'status' => false,
	            'data' => [],
	            'message' => 'No Sub-Categories available'
	        ];
        }
        
    	return response()->json($response, 200);  
    }
}
