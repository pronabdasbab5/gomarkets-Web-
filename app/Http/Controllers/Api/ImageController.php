<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Response;

class ImageController extends Controller
{
    public function sliderImage ($slider_id) 
    {
        try {
            $slider_id = decrypt($slider_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $app_slider_record = DB::table('app_slider')
            ->where('id', $slider_id)
            ->first();

        $path = public_path('assets/mobile/slider/'.$app_slider_record->slider);

        if (!File::exists($path)) 
            $response = 404;

        $file     = File::get($path);
        $type     = File::extension($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function offerImage ($offer_id) 
    {
        try {
            $offer_id = decrypt($offer_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $app_offer_record = DB::table('app_offer')
            ->where('id', $offer_id)
            ->first();

        $path = public_path('assets/mobile/offer/'.$app_offer_record->offer);

        if (!File::exists($path)) 
            $response = 404;

        $file     = File::get($path);
        $type     = File::extension($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function brandImage ($brand_id) 
    {
        try {
            $brand_id = decrypt($brand_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $brand_record = DB::table('brand')
            ->where('id', $brand_id)
            ->first();

        $path = public_path('assets/brand/'.$brand_record->banner);

        if (!File::exists($path)) 
            $response = 404;

        $file     = File::get($path);
        $type     = File::extension($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function productBanner ($product_id) 
    {
        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $product_record = DB::table('product')
            ->where('id', $product_id)
            ->first();

        $path = public_path('assets/product/banner/'.$product_record->banner);

        if (!File::exists($path)) 
            $response = 404;

        $file     = File::get($path);
        $type     = File::extension($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function productSlider ($product_slider_id) 
    {
        try {
            $product_slider_id = decrypt($product_slider_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $product_record = DB::table('product_additional_images')
            ->where('id', $product_slider_id)
            ->first();

        $path = public_path('assets/product/images/'.$product_record->additional_image);

        if (!File::exists($path)) 
            $response = 404;

        $file     = File::get($path);
        $type     = File::extension($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function subCategoryBanner ($sub_category_id) 
    {
        try {
            $sub_category_id = decrypt($sub_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $sub_category_record = DB::table('sub_category')
            ->where('id', $sub_category_id)
            ->first();

        $path = public_path('assets/sub_category/'.$sub_category_record->banner);

        if (!File::exists($path)) 
            $response = 404;

        $file     = File::get($path);
        $type     = File::extension($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
