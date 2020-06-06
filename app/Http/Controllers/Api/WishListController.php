<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class WishListController extends Controller
{
    public function addToWishList(Request $request) {

    	$count_wish_list = DB::table('wishlist')
	    	->where('user_id', $request->input('user_id'))
	    	->where('product_id', $request->input('product_id'))
	    	->count();

	    if ($count_wish_list > 0) {

	    	$response = [
		        'code' => 200,
		        'status' => false,
		        'message' => 'Product already added in the wish list'
		    ];
	    } else {

	    	DB::table('wishlist')
	    		->insert([
	    			'user_id' => $request->input('user_id'),
	    			'product_id' => $request->input('product_id'),
	    		]);

	    	$response = [
		        'code' => 200,
		        'status' => true,
		        'message' => 'Product has been added to wish list'
		    ];
	    }

        return response()->json($response, 200);
    }

    public function viewWishList(Request $request) {

    	DB::table('wishlist')
        	->leftJoin('product', 'wishlist.product_id', '=', 'product.id')
        	->where('wishlist.user_id', $request->input('user_id'))
            ->where('product.status', 2)
            ->where('product.district_id', '!=', $request->input('district_id'))
            ->delete();

        DB::table('wishlist')
            ->leftJoin('product', 'wishlist.product_id', '=', 'product.id')
            ->where('wishlist.user_id', $request->input('user_id'))
            ->where('product.status', 2)
            ->delete();

        $products = DB::table('wishlist')
        	->leftJoin('product', 'wishlist.product_id', '=', 'product.id')
        	->where('wishlist.user_id', $request->input('user_id'))
            ->select('product.*') 
            ->get();

        foreach ($products as $key => $item) {

            $product_banner = DB::table('product_additional_images')
                ->where('product_id', $item->id)
                ->first();

            $item->banner = asset('assets/product_images/thumbnail/'.$product_banner->additional_image);

            $brand = DB::table('brand')
                ->where('id', $item->brand_id)
                ->first();
            $item->brand_id = $brand->brand_name;

            $item->desc = strip_tags($item->desc);
        }

        if (!empty($products) && (count($products) > 0)) {
        	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => $products,
	            'message' => 'Wish list products has been fetching successfully'
	        ];
        } else {
        	$response = [
	            'code' => 200,
	            'status' => false,
	            'data' => [],
	            'message' => 'Wish list is empty'
	        ];
        }

        return response()->json($response, 200);
    }

    public function removeWishList(Request $request) {

        DB::table('wishlist')
            ->where('wishlist.user_id', $request->input('user_id'))
            ->where('wishlist.product_id', $request->input('product_id'))
            ->delete();

        $response = [
            'code' => 200,
            'status' => true,
            'message' => 'Product has been removed from wish list'
        ];

        return response()->json($response, 200);
    }
}
