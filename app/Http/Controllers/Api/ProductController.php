<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class ProductController extends Controller
{
    function nieceCategories($sub_category_id)
    {
        $niece_categories = DB::table('niece_category')
            ->where('sub_category_id', $sub_category_id)
            ->where('status', 1)
            ->get();

        if (!empty($niece_categories) && (count($niece_categories) > 0)) {

            foreach ($niece_categories as $key => $item){

                $item->banner = asset('assets/niece_category/'.$item->banner);
            }

            $response = [
                'code' => 200,
                'status' => true,
                'data' => $niece_categories,
                'message' => 'Niece-Categories has been retrive successfully'
            ];
        } else {

            $response = [
                'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'No Niece-Categories available'
            ];
        }
        
        return response()->json($response, 200);  
    }

    public function productList ($niece_category_id, $district_id) {

        $products = DB::table('product')
        	->where('niece_category_id', $niece_category_id)
            ->where('district_id', $district_id)
            ->where('status', 1)
            ->distinct()
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

            $item->prices = DB::table('product_price')
                ->where('product_id', $item->id)
                ->where('status', 1)
                ->get();

            foreach ($item->prices as $key => $items) {
                
                $size = DB::table('size')
                    ->where('id', $items->size_id)
                    ->first();
                $items->size = $size->size;

                if(!empty($items->discount) && ($items->discount > 0)) {
                    $discount_amount = ($items->price * $items->discount)/100;
                    $amount_after_discount = $items->price - $discount_amount;
                    $items->amount_after_discount = $amount_after_discount;
                } else{
                    $items->discount = 0;
                    $items->amount_after_discount = $items->price;
                }
            }
        }

        if (!empty($products) && (count($products) > 0)) {
        	$response = [
	            'code' => 200,
	            'status' => true,
                'total_item' => count($products),
	            'data' => $products,
	            'message' => 'Product list has been retrive successfully'
	        ];
        } else {
        	$response = [
	            'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'No product available'
	        ];
        }

        return response()->json($response, 200);
    }

    public function productSearch ($district_id, $query) {

        $products = DB::table('product')
            ->where('district_id', $district_id)
            ->where('product_name_english', 'like',  '%'.ucwords($query).'%')
            ->where('status', 1)
            ->distinct()
            ->take(15)
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
                'total_item' => count($products),
                'data' => $products,
                'message' => 'Product list has been retrive successfully'
            ];
        } else {
            $response = [
                'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'No product available'
            ];
        }

        return response()->json($response, 200);
    }

    public function productDetail($product_id)
    {
        /** Product Data Retriving **/
        $product_record = DB::table('product')
            ->where('id', $product_id)
            ->first();

        /** Product Slider Data Retriving **/
        $product_sliders= DB::table('product_additional_images')
            ->where('product_id', $product_id)
            ->get();
        foreach ($product_sliders as $key => $item) {
            $item->additional_image = asset('assets/product_images/'.$item->additional_image);
        }

        /** Product Prices **/
        $product_prices = DB::table('product_price')
                ->where('product_id', $product_id)
                ->where('status', '1')
                ->get();
        if (count($product_prices) > 0) {
	        foreach ($product_prices as $key => $item) {
	            $size = DB::table('size')
	                    ->where('id', $item->size_id)
	                    ->first();
	            $item->size = $size->size;

	            if(!empty($item->discount) && ($item->discount > 0)) {
	                $discount_amount = ($item->price * $item->discount)/100;
	                $amount_after_discount = $item->price - $discount_amount;
	                $item->amount_after_discount = $amount_after_discount;
	            } else{
	                $item->discount = 0;
	                $item->amount_after_discount = $item->price;
	            }
	        }
	    }

        $brand = DB::table('brand')
                ->where('id', $product_record->brand_id)
                ->first();
        $product_record->brand_id = $brand->brand_name;

        $product_record->desc = strip_tags($product_record->desc);
        $product_record->product_sliders = $product_sliders;
        $product_record->product_prices = $product_prices;

        if (!empty($product_prices) && (count($product_prices) > 0)) {
        	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => $product_record,
	            'message' => 'Product detail has been fetch successfully'
	        ];
        } else {
        	$response = [
	            'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'No product detail available'
	        ];
        }

        return response()->json($response, 200);  
    }
}
