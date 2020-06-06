<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class CheckoutController extends Controller
{
    public function checkout(Request $request) {

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

	    $total = 0;
	    foreach ($products as $key => $item) {

            $product_price = DB::table('product_price')
                ->where('id', $item->product_price_id)
                ->first();
            if(!empty($product_price->discount) && ($product_price->discount > 0)) {
                $discount_amount = ($product_price->price * $product_price->discount)/100;
                $amount_after_discount = $product_price->price - $discount_amount;
            } else
            	$amount_after_discount = $product_price->price;


            $total = $total + ($amount_after_discount * $item->quantity);
        }

        $delivery_charge = 0;
        if ($total < 500)
        	$delivery_charge = 50;
        else 
        	$delivery_charge = 40;

        $grand_total = $total + $delivery_charge;

        $data [] = [
        	'sub_total' => $total,
        	'delivery_charge' => $delivery_charge,
        	'grand_total' => $grand_total
        ];

        if (!empty($products) && (count($products) > 0)) {
            $response = [
                'code' => 200,
                'status' => true,
                'data' => $data,
                'message' => 'Total has been calculated successfully'
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

    public function stockChecking(Request $request) {

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

        $cart_product = DB::table('cart')->get();

        $status = true;
        if (count($cart_product) > 0) {
            foreach ($cart_product as $key => $item) {

                $product_price = DB::table('product_price')
                    ->where('id', $item->product_price_id)
                    ->first();

                if($product_price->stock >= $item->quantity) {
                    $status = true;
                    $msg = 'Stock available.';
                }
                else{
                    $status = false;
                    $msg = 'Some product out of stock ! please remove';
                    break;
                }
            }
        } else{
            $status = false;
            $msg = 'Cart is empty ! please add product';
        }

        if ($status) {
            $response = [
                'code' => 200,
                'status' => true,
                'message' => $msg
            ];
        } else {
            $response = [
                'code' => 200,
                'status' => false,
                'message' => $msg
            ];
        }

        return response()->json($response, 200);
    }
}
