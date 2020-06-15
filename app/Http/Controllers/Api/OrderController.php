<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function cashOrder(Request $request) {

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

        $address = DB::table('address')
        	->leftJoin('area', 'address.area_id', '=', 'area.id')
        	->leftJoin('sub_district', 'address.sub_district_id', '=', 'sub_district.id')
        	->leftJoin('district', 'address.district_id', '=', 'district.id')
        	->leftJoin('states', 'address.state_id', '=', 'states.id')
    		->where('address.user_id', $request->input('user_id'))
    		->select('address.*', 'area.area_name', 'sub_district.sub_district_name', 'district.district_name', 'states.state_name')
            ->first();

        $cart_item = DB::table('cart')
	    		->where('cart.user_id', $request->input('user_id'))
	    		->get();
	   	$total = 0;
	    foreach ($cart_item as $key => $item) {

            $product_price = DB::table('product_price')
                ->where('id', $item->product_price_id)
                ->first();
            if(!empty($product_price->discount) && ($product_price->discount > 0)) {
                $discount_amount = ($product_price->price * $product_price->discount)/100;
                $amount_after_discount = $product_price->price - $discount_amount;
            } else
            	$amount_after_discount = $product_price->price;


            $total = $total + $amount_after_discount;

            DB::table('product_price')
            	->where('id', $item->product_price_id)
            	->update(['stock' => DB::raw('GREATEST(stock - '.$item->quantity.', 0)')]);
        }

        $delivery_charge = 0;
        if ($total < 500)
        	$delivery_charge = 50;
        else 
        	$delivery_charge = 40;

        $grand_total = $total + $delivery_charge;

        $order_id = time();

        $order_auto_id = DB::table('order')
            ->insertGetId([ 
                'order_id' => $order_id, 
                'user_id' => $request->input('user_id'), 
                'amount' => $total, 
                'delivery_charge' => $delivery_charge, 
                'grand_total' => $grand_total, 
                'payment_status' => 3,
                'order_status' => 1,
                'address' => $address->address, 
                'area' => $address->area_name, 
                'sub_district' => $address->sub_district_name, 
                'district' => $address->district_name, 
                'state' => $address->state_name, 
                'pin_code' => $address->pin_code, 
                'email' => $address->email, 
                'mobile_no' => $address->mobile_no, 
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
            ]);

    	$products = DB::table('cart')
    			->leftJoin('product_price', 'cart.product_price_id', '=', 'product_price.id')
                ->leftJoin('size', 'product_price.size_id', '=', 'size.id')
    			->leftJoin('product', 'product_price.product_id', '=', 'product.id')
    			->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
	    		->where('cart.user_id', $request->input('user_id'))
	    		->select('product.*', 'cart.quantity', 'size.size', 'product_price.price', 'product_price.discount', 'brand.brand_name', 'product_price.size_id')
	    		->get();

	    foreach ($products as $key => $item) {

            DB::table('order_detail')
	            ->insert([ 
	                'order_id' => $order_auto_id, 
	                'product_id' => $item->id, 
                    'size_id' => $item->size_id, 
	                'product_name_english' => $item->product_name_english, 
	                'product_name_assamese' => $item->product_name_assamese, 
	                'brand' => $item->brand_name, 
	                'size' => $item->size, 
	                'price' => $item->price,
	                'discount' => $item->discount,
	                'quantity' => $item->quantity, 
	            ]);
        }

        DB::table('cart')
	    	->where('cart.user_id', $request->input('user_id'))
	    	->delete();

        $response = [
            'code' => 200,
            'status' => true,
            'message' => 'Order has been placed successfully'
        ];

        return response()->json($response, 200);
    }

    public function onlineOrder(Request $request) {

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

        $address = DB::table('address')
            ->leftJoin('area', 'address.area_id', '=', 'area.id')
            ->leftJoin('sub_district', 'address.sub_district_id', '=', 'sub_district.id')
            ->leftJoin('district', 'address.district_id', '=', 'district.id')
            ->leftJoin('states', 'address.state_id', '=', 'states.id')
            ->where('address.user_id', $request->input('user_id'))
            ->select('address.*', 'area.area_name', 'sub_district.sub_district_name', 'district.district_name', 'states.state_name')
            ->first();

        $cart_item = DB::table('cart')
                ->where('cart.user_id', $request->input('user_id'))
                ->get();
        $total = 0;
        foreach ($cart_item as $key => $item) {

            $product_price = DB::table('product_price')
                ->where('id', $item->product_price_id)
                ->first();
            if(!empty($product_price->discount) && ($product_price->discount > 0)) {
                $discount_amount = ($product_price->price * $product_price->discount)/100;
                $amount_after_discount = $product_price->price - $discount_amount;
            } else
                $amount_after_discount = $product_price->price;


            $total = $total + $amount_after_discount;

            // DB::table('product_price')
            //     ->where('id', $item->product_price_id)
            //     ->update(['stock' => DB::raw('GREATEST(stock - '.$item->quantity.', 0)')]);

            DB::table('product_price')
                ->where('id', $item->product_price_id)
                ->decrement('stock', $item->quantity);
        }

        $delivery_charge = 0;
        if ($total < 500)
            $delivery_charge = 50;
        else 
            $delivery_charge = 40;

        $grand_total = $total + $delivery_charge;

        $order_id = time();

        $order_auto_id = DB::table('order')
            ->insertGetId([ 
                'order_id' => $order_id, 
                'user_id' => $request->input('user_id'), 
                'amount' => $total, 
                'delivery_charge' => $delivery_charge, 
                'grand_total' => $grand_total, 
                'payment_status' => 3,
                'order_status' => 1,
                'address' => $address->address, 
                'area' => $address->area_name, 
                'sub_district' => $address->sub_district_name, 
                'district' => $address->district_name, 
                'state' => $address->state_name, 
                'pin_code' => $address->pin_code, 
                'email' => $address->email, 
                'mobile_no' => $address->mobile_no, 
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
            ]);

        $products = DB::table('cart')
                ->leftJoin('product_price', 'cart.product_price_id', '=', 'product_price.id')
                ->leftJoin('size', 'product_price.size_id', '=', 'size.id')
                ->leftJoin('product', 'product_price.product_id', '=', 'product.id')
                ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
                ->where('cart.user_id', $request->input('user_id'))
                ->select('product.*', 'cart.quantity', 'size.size', 'product_price.price', 'product_price.discount', 'brand.brand_name', 'product_price.size_id')
                ->get();

        foreach ($products as $key => $item) {

            DB::table('order_detail')
                ->insert([ 
                    'order_id' => $order_auto_id, 
                    'product_id' => $item->id, 
                    'size_id' => $item->size_id,
                    'product_name_english' => $item->product_name_english, 
                    'product_name_assamese' => $item->product_name_assamese, 
                    'brand' => $item->brand_name, 
                    'size' => $item->size, 
                    'price' => $item->price,
                    'discount' => $item->discount,
                    'quantity' => $item->quantity, 
                ]);
        }

        $data [] = [
            'order_id' => $order_auto_id,
            'total' => ($grand_total * 100)
        ];

        $response = [
            'code' => 200,
            'status' => true,
            'data' => $data,
            'message' => 'Order has been added successfully'
        ];

        return response()->json($response, 200);
    }

    public function onlinePaymentSuccess(Request $request) {

        DB::table('order')
            ->where('id', $request->input('order_id'))
            ->update([
                'payment_id' => $request->input('payment_id'),
                'payment_status' => 2
            ]);

        DB::table('cart')
            ->where('cart.user_id', $request->input('user_id'))
            ->delete();

        $response = [
            'code' => 200,
            'status' => true,
            'message' => 'Order has been placed successfully'
        ];

        return response()->json($response, 200);
    }

    public function onlinePaymentFailed(Request $request) {

        $order_detail = DB::table('order_detail')
            ->where('order_id', $request->input('order_id'))
            ->get();

        DB::table('order')
            ->where('id', $request->input('order_id'))
            ->update([
                'payment_status' => 1
            ]);

        foreach ($order_detail as $key => $item) {

            DB::table('product_price')
                ->where('product_id', $item->product_id)
                ->where('size_id', $item->size_id)
                ->increment('stock', $item->quantity);
        }

        $response = [
            'code' => 200,
            'status' => true,
            'message' => 'Payment has been failed'
        ];

        return response()->json($response, 200);
    }

    public function orderList(Request $request) {

        $order_count = DB::table('order')
            ->where('order.user_id', $request->input('user_id'))
            ->count();

        $offset     = ($request->input('page_no') * 3) - 3;
        $total_page = ceil($order_count/3);

        $order_list = DB::table('order')
            ->where('order.user_id', $request->input('user_id'))
            ->orderBy('id', 'DESC')
            ->offset($offset)
            ->limit(3)
            ->get();

        foreach ($order_list as $key => $item) {

            if ($item->payment_status == 1) 
                $item->payment_status = 'Failed';

            if ($item->payment_status == 2) 
                $item->payment_status = 'Paid Online';

            if ($item->payment_status == 3) 
                $item->payment_status = 'Cash';

            if ($item->order_status == 1) 
                $item->order_status = 'New Order';

            if ($item->order_status == 2) 
                $item->order_status = 'Packed';

            if ($item->order_status == 3) 
                $item->order_status = 'Picked';

            if ($item->order_status == 4) 
                $item->order_status = 'Delivered';

            if ($item->order_status == 5) 
                $item->order_status = 'Canceled';

            $item->created_at = \Carbon\Carbon::parse($item->created_at)->toDayDateTimeString();
        }

        if (!empty($order_list) && (count($order_list) > 0)) {
            $response = [
                'code' => 200,
                'status' => true,
                'total_product' => $order_count,
                'product_per_page' => count($order_list),
                'total_page' => $total_page,
                'data' => $order_list,
                'message' => 'Order List has been fetching successfully'
            ];
        } else {
            $response = [
                'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'Order List is empty'
            ];
        }

        return response()->json($response, 200);
    }

    public function orderDetail(Request $request) {

        $order_detail = DB::table('order')
            ->where('id', $request->input('order_id'))
            ->first();

        if ($order_detail->payment_status == 1) 
            $order_detail->payment_status = 'Failed';

        if ($order_detail->payment_status == 2) 
            $order_detail->payment_status = 'Paid Online';

        if ($order_detail->payment_status == 3) 
            $order_detail->payment_status = 'Cash';

        if ($order_detail->order_status == 1) 
            $order_detail->order_status = 'New';

        if ($order_detail->order_status == 2) 
            $order_detail->order_status = 'Packed';

        if ($order_detail->order_status == 3) 
            $order_detail->order_status = 'Picked';

        if ($order_detail->order_status == 4) 
            $order_detail->order_status = 'Delivered';

        if ($order_detail->order_status == 5) 
            $order_detail->order_status = 'Canceled';

        $order_detail->created_at = \Carbon\Carbon::parse($order_detail->created_at)->toDayDateTimeString();

        $order_product_detail = DB::table('order_detail')
            ->where('order_id', $order_detail->id)
            ->get();

        foreach ($order_product_detail as $key => $item) {
            
            if (!empty($item->discount)) {
                
                $discount = ($item->discount * $item->price) / 100;
                $amount_after_discount = $item->price - $discount;
                $sub_total = $amount_after_discount * $item->quantity;
            } else {
                $sub_total = $item->price * $item->quantity;
            }

            $item->sub_total = $sub_total;
        }

        $order_detail->product_detail = $order_product_detail;

        if (!empty($order_detail)) {
            $response = [
                'code' => 200,
                'status' => true,
                'data' => $order_detail,
                'message' => 'Order Detail has been fetching successfully'
            ];
        } else {
            $response = [
                'code' => 200,
                'status' => false,
                'data' => [],
                'message' => 'Order Detail is empty'
            ];
        }

        return response()->json($response, 200);
    }
}
