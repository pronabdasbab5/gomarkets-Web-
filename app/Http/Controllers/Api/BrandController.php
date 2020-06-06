<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class BrandController extends Controller
{
    public function brandsList()
    {
        $brands_record = DB::table('brand')
            ->where('status', 1)
            ->get();

        if (!empty($brands_record)) {

        	$brand_record = null;
	        foreach ($brands_record as $key => $item){

	            $url = asset('assets/brand/'.$item->banner);

	            $brand_record [] = [
	                'id' => $item->id,
	                'brand_name' => $item->brand_name,
	                'designer_name' => $item->designer_name,
	                'url' => $url
	            ];
	        }

	    	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => $brand_record,
	            'message' => 'Brands has been retrive successfully'
	        ];
        } else {

        	$response = [
	            'code' => 200,
	            'status' => true,
	            'data' => null,
	            'message' => 'No Sub-Categories available'
	        ];
        }
        
    	return response()->json($response, 200);  
    }

    public function productList ($brand_id, $bpage_no, $sort, $sizes) {

        $product_count = DB::table('product')
        	->where('brand_id', $brand_id)
            ->where('status', '1')
            ->count();

        $product_record = DB::table('product')
        	->where('brand_id', $brand_id)
            ->first();

        $offset     = ($bpage_no * 4) - 4;
        $total_page = ceil($product_count/4);

        $products = DB::table('product')
            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id');

        if ($sizes != 0) {
                
            $sizes = explode(',', $sizes);
            $products = $products
                ->leftJoin('product_size_mapping', 'product.id', '=', 'product_size_mapping.product_id')
                ->whereIn('product_size_mapping.size_id', $sizes);
        }

        $products = $products    
        	->where('brand_id', $brand_id)
            ->where('product.status', '1');

        if ($sort == 3)
            $products = $products->orderBy('product.price', 'desc');
        if ($sort == 2)
            $products = $products->orderBy('product.price', 'asc');
        if ($sort == 1)
            $products = $products->orderBy('product.id', 'desc');
        
        $products = $products  
            ->distinct()
            ->select('product.*', 'brand.brand_name', 'brand.designer_name') 
            ->offset($offset)
            ->limit(5)
            ->get();

        $data = null;

        foreach ($products as $key => $item) {

            $url = asset('assets/product/banner/thumbnail/'.$item->banner);
            $discount = ($item->price * $item->discount) / 100;
            $selling_price = $item->price - $discount;

            $rating_count = DB::table('review')
            	->where('product_id', $item->id)
                ->where('status', '1')
                ->count();

            $sum    = 0;
            $rating = "";

            if ($rating_count > 0) {

                $rating_val = DB::table('review')
                	->where('product_id', $item->id)
                    ->where('status', '1')
                    ->get();

                foreach ($rating_val as $key_1 => $item_1)
                    $sum = $sum + $item_1->star;

                $rating = ceil($sum / $rating_count);
            }
            else
                $rating = 0;

            $sizes = DB::table('size_mapping')
                ->leftJoin('size', 'size_mapping.size_id', '=', 'size.id')
                ->where('size_mapping.sub_category_id', $product_record->sub_category_id)
                ->select('size.*')
                ->get();

            $data [] = [
                'id' => $item->id,
                'product_name' => $item->product_name,
                'original_price' => $item->price,
                'selling_price' => $selling_price,
                'discount' => $item->discount,
                'desc' => substr(strip_tags($item->desc), 0, 35)."...",
                'rating' => $rating,
                'star_count' => $rating_count,
                'comment_count' => $rating_count,
                'url' => $url
            ];
        }

        if (($data != null) && (count($data) > 0)) {
        	$response = [
	            'code' => 200,
	            'status' => true,
	            'total_item' => $product_count,
                'per_page_item' => 4,
                'total_page' => $total_page,
                'brand_name' => $item->brand_name,
                'designer_name' => $item->designer_name,
	            'data' => $data,
                'sizes' => $sizes,
	            'message' => 'Product list has been retrive successfully'
	        ];
        } else {
        	$response = [
	            'code' => 200,
	            'status' => false,
	            'total_item' => $product_count,
	            'first_index' => $offset,
	            'last_index' => $total_page,
	            'data' => $data,
	            'message' => 'Product list has been retrive successfully'
	        ];
        }

        return response()->json($response, 200);
    }
}
