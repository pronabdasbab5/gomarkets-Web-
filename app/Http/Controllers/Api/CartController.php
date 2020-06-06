<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class CartController extends Controller
{
    public function addToCart(Request $request) {

    	$check_availability = DB::table('product_price')
    		->where('id', $request->input('product_price_id'))
    		->where('stock', '>=', $request->input('quantity'))
    		->first();
            
    	if (!empty($check_availability) && ($check_availability->stock > 0)) {

    		$count_cart = DB::table('cart')
	    		->where('user_id', $request->input('user_id'))
	    		->where('product_price_id', $request->input('product_price_id'))
	    		->count();

	    	if ($count_cart > 0) {

                $count_data = DB::table('cart')
                    ->where('user_id', $request->input('user_id'))
                    ->where('product_price_id', $request->input('product_price_id'))
                    ->first();

                $quantity = $count_data->quantity + $request->input('quantity');

                if ($check_availability->stock >= $quantity) {

                    DB::table('cart')
                        ->where('user_id', $request->input('user_id'))
                        ->where('product_price_id', $request->input('product_price_id'))
                        ->update([
                            'quantity' => $quantity,
                        ]);

                    $response = [
                        'code' => 200,
                        'status' => true,
                        'message' => 'Product has been added to bag'
                    ];
                } else {

                    $response = [
                        'code' => 200,
                        'status' => false,
                        'message' => 'Sorry ! Product is out of stock'
                    ];
                }
	    	} else {
	    		DB::table('cart')
	    			->insert([
	    				'user_id' => $request->input('user_id'),
	    				'product_price_id' => $request->input('product_price_id'),
	    				'quantity' => $request->input('quantity'),
	    			]);

                $response = [
                    'code' => 200,
                    'status' => true,
                    'message' => 'Product has been added to bag'
                ];
	    	}
    	} else {
    		$response = [
	            'code' => 200,
	            'status' => false,
	            'message' => 'Sorry ! Product is out of stock'
	        ];
    	}

        return response()->json($response, 200);
    }

    public function viewCart(Request $request) {

        DB::table('cart')
            ->leftJoin('product_price', 'cart.product_price_id', '=', 'product_price.id')
            ->leftJoin('product', 'product_price.product_id', '=', 'product.id')
            ->where('product.status', 2)
            ->where('cart.user_id', $request->input('user_id'))
            ->delete();

        DB::table('cart')
            ->leftJoin('product_price', 'cart.product_price_id', '=', 'product_price.id')
            ->leftJoin('product', 'product_price.product_id', '=', 'product.id')
            ->where('product.status', 2)
            ->where('cart.user_id', $request->input('user_id'))
            ->where('product.district_id', '!=', $request->input('district_id'))
            ->delete();

    	$products = DB::table('cart')
    			->leftJoin('product_price', 'cart.product_price_id', '=', 'product_price.id')
                ->leftJoin('size', 'product_price.size_id', '=', 'size.id')
    			->leftJoin('product', 'product_price.product_id', '=', 'product.id')
	    		->where('cart.user_id', $request->input('user_id'))
	    		->select('product.*', 'cart.quantity', 'size.size', 'cart.product_price_id')
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

            $product_price = DB::table('product_price')
                ->where('id', $item->product_price_id)
                ->first();
            if(!empty($product_price->discount) && ($product_price->discount > 0)) {
                $discount_amount = ($product_price->price * $product_price->discount)/100;
                $amount_after_discount = $product_price->price - $discount_amount;
                $item->price = $product_price->price;
                $item->amount_after_discount = $amount_after_discount;
            } else{
                $product_price->discount = 0;
                $item->price = $product_price->price;
                $item->amount_after_discount = $product_price->price;
            }
        }

        if (!empty($products) && (count($products) > 0)) {
            $response = [
                'code' => 200,
                'status' => true,
                'total_item' => count($products),
                'data' => $products,
                'message' => 'Cart Product list has been retrive successfully'
            ];
        } else {
            $response = [
                'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'Cart is empty'
            ];
        }

        return response()->json($response, 200);
    }

    public function updateCart(Request $request) {

        $check_availability = DB::table('product_price')
            ->where('id', $request->input('product_price_id'))
            ->where('stock', '>=', $request->input('quantity'))
            ->first();
            
        if (!empty($check_availability) && ($check_availability->stock > 0)) {

            DB::table('cart')
                ->where('user_id', $request->input('user_id'))
                ->where('product_price_id', $request->input('product_price_id'))
                ->update([
                    'quantity' => $request->input('quantity'),
                ]);

            $response = [
                'code' => 200,
                'status' => true,
                'message' => 'Quantity has been updated to bag'
            ];
        } else {
            $response = [
                'code' => 200,
                'status' => false,
                'message' => 'Sorry ! Product is out of stock'
            ];
        }

        return response()->json($response, 200);
    }

    public function removeCart(Request $request) {

        DB::table('cart')
            ->where('cart.user_id', $request->input('user_id'))
            ->where('cart.product_price_id', $request->input('product_price_id'))
            ->delete();

        $response = [
            'code' => 200,
            'status' => true,
            'message' => 'Product has been removed from bag'
        ];

        return response()->json($response, 200);
    }
}
