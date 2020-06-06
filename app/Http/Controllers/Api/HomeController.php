<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class HomeController extends Controller
{
    function home($district_id)
    {
        /** Top-Category **/
        $top_categories = DB::table('top_category')
            ->where('status', 1)
            ->get();

        foreach ($top_categories as $key => $item) {

            $item->banner = asset('assets/top_category/'.$item->banner);
        }

        /** Offer Banner **/ 
        $offer_banner = DB::table('app_offer')
            ->where('status', 1)
            ->first();

        $offer_banner->banner = asset('assets/mobile/offer/'.$offer_banner->banner);

        /** Discounted Product **/
        $discount_products = DB::table('product')
            ->where('district_id', $district_id)
            ->where('make_discount_product', 1)
            ->where('status', 1)
            ->get();

        foreach ($discount_products as $key => $item) {

            $product_banner = DB::table('product_additional_images')
                ->where('product_id', $item->id)
                ->first();

            $item->banner = asset('assets/product_images/thumbnail/'.$product_banner->additional_image);
        }

        /** Slider **/
        $app_slider = DB::table('app_slider')
            ->where('status', 1)
            ->get();

        foreach ($app_slider as $key => $item) {

            $item->slider = asset('assets/mobile/slider/'.$item->slider);
        }

        /** Latest Product **/
        $latest_products = DB::table('product')
            ->where('district_id', $district_id)
            ->where('status', 1)
            ->take(10)
            ->get();

        foreach ($latest_products as $key => $item) {

            $product_banner = DB::table('product_additional_images')
                ->where('product_id', $item->id)
                ->first();

            $item->banner = asset('assets/product_images/thumbnail/'.$product_banner->additional_image);
        }

    	$data = [];
    	$data = [
            'top_categories' => $top_categories,
            'offer_banner' => $offer_banner,
            'discount_products' => $discount_products,
            'slider' => $app_slider,
            'latest_products' => $latest_products
    	];

        if (count($data) > 0) {
            $response = [
                'code' => 200,
                'status' => true,
                'data' => $data,
                'message' => 'Home Info. has been retrive successfully'
            ];
        } else {
            $response = [
                'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'No record found'
            ];
        }

    	return response()->json($response, 200);  
    }
}
