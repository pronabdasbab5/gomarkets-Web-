<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Response;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function showProductForm () 
    {
    	$top_category_list = DB::table('top_category')
            ->where('status', 1)
            ->get();

        $brand_list = DB::table('brand')
            ->where('status', 1)
            ->get();

        $size_list = DB::table('size')
            ->get();

        /** Fetching All State **/
        $state_list = DB::table('states')
            ->where('status', 1)
            ->get();

        return view('admin.product.new_product', ['top_category_list' => $top_category_list, 'brand_list' => $brand_list, 'size_list' => $size_list, 'state_list' => $state_list]);
    }

    public function retriveSubCategory(Request $request)
    {
    	$sub_category_list = DB::table('sub_category')
    		->where('top_category_id', $request->input('category_id'))
            ->where('status', 1)
    		->get();

    	$data = "<option value=\"\" disabled selected>Choose Sub-Category</option>";
    	foreach ($sub_category_list as $key => $value)
    		$data = $data."<option value=\"".$value->id."\">".$value->sub_cate_name."</option>";

    	print $data;
    }

    public function retriveNieceCategory(Request $request)
    {
        $niece_category_list = DB::table('niece_category')
            ->where('sub_category_id', $request->input('sub_category_id'))
            ->where('status', 1)
            ->get();

        $data = "<option value=\"\" disabled selected>Choose Niece-Category</option>";
        foreach ($niece_category_list as $key => $value)
            $data = $data."<option value=\"".$value->id."\">".$value->niece_cate_name."</option>";

        print $data;
    }

    public function addProduct(Request $request) 
    {
        $request->validate([
            'state_id' => 'required',
            'district_id' => 'required',
            'niece_category_id' => 'required',
            'product_name_english'  => 'required',
            'product_name_assamese'  => 'required',
            'slug'         => 'required',
            'product_desc'     => 'required',
            'product_images.*'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $count_product = DB::table('product')
            ->where('niece_category_id', $request->input('niece_category_id'))
            ->where('product_name_english', ucwords(strtolower($request->input('product_name_english'))))
            ->count();

        if($count_product > 0)
            return redirect()->back()->with('error', 'Product has been already added');

        DB::table('product')
            ->insert([ 
                'district_id' => $request->input('district_id'), 
                'niece_category_id' => $request->input('niece_category_id'), 
                'product_name_english' => $request->input('product_name_english'), 
                'product_name_assamese' => $request->input('product_name_assamese'), 
                'brand_id' => $request->input('brand_id'), 
                'slug' => $request->input('slug'),
                'desc' => $request->product_desc,
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
            ]);

        $product_id = DB::getPdo()->lastInsertId();

        if ($request->hasFile('product_images')) {

            if(!File::exists(public_path()."/assets"))
                File::makeDirectory(public_path()."/assets");

            if(!File::exists(public_path()."/assets/product_images"))
                File::makeDirectory(public_path()."/assets/product_images");

            if(!File::exists(public_path()."/assets/product_images/thumbnail"))
                File::makeDirectory(public_path()."/assets/product_images/thumbnail");

            for($i = 0; $i < count($request->file('product_images')); $i++) 
            {
                $original_file = $request->file('product_images')[$i];
                $file   = time().$i.'.'.$original_file->getClientOriginalExtension();
                
                $destinationPath = public_path('/assets/product_images');
                $img = Image::make($original_file->getRealPath());
                $img->save($destinationPath.'/'.$file);

                $destinationPath = public_path('/assets/product_images/thumbnail');
                $img = Image::make($original_file->getRealPath());
                $img->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$file);

                DB::table('product_additional_images')
                    ->insert([ 
                        'product_id' => $product_id,
                        'additional_image' => $file, 
                    ]);
            }

            return redirect()->route('admin.product_price_entry', ['product_id' => encrypt($product_id)]);
        } else
            return redirect()->back()->with('error', 'Please ! select a banner');
    }

    public function productPriceEntry($product_id)
    {
        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $product_detail = DB::table('product')
            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
            ->where('product.id', $product_id)
            ->select('niece_category.*')
            ->first();

        $size_list = DB::table('size_mapping')
            ->leftJoin('size', 'size_mapping.size_id', '=', 'size.id')
            ->where('size_mapping.sub_category_id', $product_detail->sub_category_id)
            ->select('size_mapping.*', 'size.size')
            ->get();

        return view('admin.product.product_price_entry', ['product_id' => $product_id, 'size_list' => $size_list]);
    }

    public function addPriceEntry(Request $request, $product_id)
    {
        $request->validate([
            'size_id' => 'required',
            'stock' => 'required',
            'price' => 'required',
        ]);

        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        for ($i=0; $i < count($request->input('price')); $i++) { 

            if ($request->input('stock')[$i] > 0) {
            
                DB::table('product_price')
                    ->insert([
                        'product_id' => $product_id,
                        'size_id' => $request->input('size_id')[$i], 
                        'stock' => $request->input('stock')[$i], 
                        'price' => $request->input('price')[$i], 
                        'discount' => $request->input('discount')[$i], 
                        'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
                    ]);
            }
        }

        return redirect()->route('admin.new_product')->with('msg', 'Product has been added sucessfully');
    }

    public function productList()
    {
        return view('admin.product.product_list.product_list');
    }

    public function productListData(Request $request)
    {
        $columns = array( 
                            0 => 'id', 
                            1 => 'state',
                            2 => 'district',
                            3 => 'product_name_english',
                            4 => 'product_name_assamese',
                            5 => 'niece_category',
                            6 => 'sub_category',
                            7 => 'top_category',
                            8 => 'brand',
                            9 => 'product_images',
                            10 => 'action',
                        );

        $totalData = DB::table('product')->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))) {            
            
            $product_data = DB::table('product')
                            ->leftJoin('district', 'product.district_id', '=', 'district.id')
                            ->leftJoin('states', 'district.state_id', '=', 'states.id')
                            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
                            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
                            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
                            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
                            ->select('product.*', 'sub_category.sub_cate_name', 'top_category.top_cate_name', 'brand.brand_name', 'niece_category.niece_cate_name', 'district.district_name', 'states.state_name')
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
        }
        else {

            $search = $request->input('search.value'); 

            $product_data = DB::table('product')
                            ->leftJoin('district', 'product.district_id', '=', 'district.id')
                            ->leftJoin('states', 'district.state_id', '=', 'states.id')
                            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
                            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
                            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
                            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
                            ->select('product.*', 'sub_category.sub_cate_name', 'top_category.top_cate_name', 'brand.brand_name', 'niece_category.niece_cate_name', 'district.district_name', 'states.state_name')
                            ->where('top_category.top_cate_name','LIKE',"%{$search}%")
                            ->orWhere('sub_category.sub_cate_name', 'LIKE',"%{$search}%")
                            ->orWhere('niece_category.niece_cate_name', 'LIKE',"%{$search}%")
                            ->orWhere('brand.brand_name', 'LIKE',"%{$search}%")
                            ->orWhere('product.product_name', 'LIKE',"%{$search}%")
                            ->orWhere('district.district_name', 'LIKE',"%{$search}%")
                            ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = DB::table('product')
                            ->leftJoin('district', 'product.district_id', '=', 'district.id')
                            ->leftJoin('states', 'district.state_id', '=', 'states.id')
                            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
                            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
                            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
                            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
                            ->select('product.*', 'sub_category.sub_cate_name', 'top_category.top_cate_name', 'brand.brand_name', 'niece_category.niece_cate_name', 'district.district_name', 'states.state_name')
                            ->where('top_category.top_cate_name','LIKE',"%{$search}%")
                            ->orWhere('sub_category.sub_cate_name', 'LIKE',"%{$search}%")
                            ->orWhere('niece_category.niece_cate_name', 'LIKE',"%{$search}%")
                            ->orWhere('brand.brand_name', 'LIKE',"%{$search}%")
                            ->orWhere('product.product_name', 'LIKE',"%{$search}%")
                            ->orWhere('product.price', 'LIKE',"%{$search}%")
                            ->orWhere('district.district_name', 'LIKE',"%{$search}%")
                            ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();

        if(!empty($product_data)) {

            $cnt = 1;

            foreach ($product_data as $single_data) {

                if($single_data->status == 1)
                    $val = "<a href=\"".route('admin.change_product_status', ['product_id' => encrypt($single_data->id), 'status' => encrypt(2)])."\" class=\"btn btn-sm btn-default\">Disabled</a>";
                else
                    $val = "<a href=\"".route('admin.change_product_status', ['product_id' => encrypt($single_data->id), 'status' => encrypt(1)])."\" class=\"btn btn-sm btn-success\">Enabled</a>";

                if($single_data->make_discount_product == 1)
                    $val_1 = "<a href=\"".route('admin.make_discount_status', ['product_id' => encrypt($single_data->id), 'status' => encrypt(2)])."\" class=\"btn btn-sm btn-danger\">Remove</a>";
                else
                    $val_1 = "<a href=\"".route('admin.make_discount_status', ['product_id' => encrypt($single_data->id), 'status' => encrypt(1)])."\" class=\"btn btn-sm btn-success\">Make Discount Product</a>";

                $nestedData['id']            = $cnt;
                $nestedData['state']  = $single_data->state_name;
                $nestedData['district']  = $single_data->district_name;
                $nestedData['product_name_english']  = $single_data->product_name_english;
                $nestedData['product_name_assamese']  = $single_data->product_name_assamese;
                $nestedData['niece_category']  = $single_data->niece_cate_name;
                $nestedData['sub_category']  = $single_data->sub_cate_name;
                $nestedData['top_category']  = $single_data->top_cate_name;
                $nestedData['brand']         = $single_data->brand_name;
                $nestedData['product_images']  = "&emsp;<a href=\"".route('admin.additional_product_image_list', ['product_id' => encrypt($single_data->id)])."\" class=\"btn btn-primary\" target=\"_blank\">Product Images List</a>";

                $nestedData['action']  = "&emsp;<div class=\"btn-group\"><a href=\"".route('admin.view_product', ['product_id' => encrypt($single_data->id)])."\" class=\"btn btn-sm btn-primary\" target=\"_blank\">View</a><a href=\"".route('admin.edit_product', ['product_id' => encrypt($single_data->id)])."\" class=\"btn btn-sm btn-warning\" target=\"_blank\">Edit</a><a href=\"".route('admin.edit_product_price', ['product_id' => encrypt($single_data->id)])."\" class=\"btn btn-sm btn-info\" target=\"_blank\">Price & Stock</a>$val$val_1</div>";

                $data[] = $nestedData;

                $cnt++;
            }
        }

        $json_data = array(
                        "draw"            => intval($request->input('draw')),  
                        "recordsTotal"    => intval($totalData),  
                        "recordsFiltered" => intval($totalFiltered), 
                        "data"            => $data   
                    );
            
        print json_encode($json_data); 
    }

    public function showProductImageList($product_id)
    {
        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $product_image_list = DB::table('product_additional_images')
            ->where('product_id', $product_id)
            ->get();

        return view('admin.product.additional_image.additional_image' , ['product_image_list' => $product_image_list, 'product_id' => $product_id]);
    }

    public function updateProductAdditionalImage(Request $request, $additional_image_id)
    {
        $request->validate([
            'additional_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        try {
            $additional_image_id = decrypt($additional_image_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $additional_image_record = DB::table('product_additional_images')
            ->where('id', $additional_image_id)
            ->first();

        if ($request->hasFile('additional_image')) {
            $additional_image = $request->file('additional_image');
            $file   = time().'.'.$additional_image->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/product_images');
            $img = Image::make($additional_image->getRealPath());
            $img->save($destinationPath.'/'.$file);

            $destinationPath = public_path('/assets/product_images/thumbnail');
            $img = Image::make($additional_image->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            File::delete(public_path('assets/product_images/'.$additional_image_record->additional_image));
            DB::table('product_additional_images')
                ->where('id', $additional_image_id)
                ->update([ 
                    'additional_image' => $file, 
                ]);
        }

        return redirect()->back();
    }

    public function viewProduct($product_id)
    {
        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $product_record = DB::table('product')
            ->leftJoin('district', 'product.district_id', '=', 'district.id')
            ->leftJoin('states', 'district.state_id', '=', 'states.id')
            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
            ->where('product.id', $product_id)
            ->select('product.*', 'sub_category.sub_cate_name', 'top_category.top_cate_name', 'brand.brand_name', 'niece_category.niece_cate_name', 'district.district_name', 'states.state_name')
            ->first();

        $product_price = DB::table('product_price')
            ->leftJoin('size', 'product_price.size_id', '=', 'size.id')
            ->where('product_price.product_id', $product_id)
            ->select('product_price.*', 'size.size')
            ->get();

        $product_image_list = DB::table('product_additional_images')
            ->where('product_id', $product_id)
            ->get();

        return view('admin.product.action.view_product', ['product_record' => $product_record, 'product_price' => $product_price, 'product_image_list' => $product_image_list]);
    }

    public function showEditProduct ($product_id) 
    {
        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $product_record = DB::table('product')
            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
            ->where('product.id', $product_id)
            ->select('product.*', 'sub_category.id as sub_cate_id', 'top_category.id as top_cate_id')
            ->first();

        $top_category_list = DB::table('top_category')->get();
        $brand_list = DB::table('brand')->get();
        $sub_category_list = DB::table('sub_category')
            ->where('top_category_id', $product_record->top_cate_id)
            ->get();
        $niece_category_list = DB::table('niece_category')
            ->where('sub_category_id', $product_record->sub_cate_id)
            ->get();

        /** District and States **/
        $district_data = DB::table('district')
            ->where('id', $product_record->district_id)
            ->first();

        $district_list = DB::table('district')
            ->where('state_id', $district_data->state_id)
            ->get();

        $state_list = DB::table('states')
            ->get();

        return view('admin.product.action.edit_product', ['top_category_list' => $top_category_list, 'brand_list' => $brand_list, 'sub_category_list' => $sub_category_list, 'product_record' => $product_record, 'niece_category_list' => $niece_category_list, 'state_id' => $district_data->state_id, 'district_list' => $district_list, 'state_list' => $state_list]);
    }

    public function updateProduct(Request $request, $product_id) 
    {
        $request->validate([
            'district_id' => 'required',
            'niece_category_id' => 'required',
            'product_name_english'  => 'required',
            'product_name_assamese'  => 'required',
            'slug' => 'required',
            'product_desc'          => 'required',
        ]);

        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $count_product = DB::table('product')
            ->where('id', '!=', $product_id)
            ->where('niece_category_id', $request->input('niece_category_id'))
            ->where('product_name_english', ucwords(strtolower($request->input('product_name_english'))))
            ->count();

        if($count_product > 0)
            return redirect()->back();

        DB::table('product')
                ->where('id', $product_id)
                ->update([ 
                    'district_id' => $request->input('district_id'),
                    'niece_category_id' => $request->input('niece_category_id'), 
                    'product_name_english' => ucwords(strtolower($request->input('product_name_english'))),
                    'product_name_assamese' => $request->input('product_name_assamese'), 
                    'slug' => $request->input('slug'), 
                    'brand_id' => $request->input('brand_id'),  
                    'desc' => $request->product_desc, 
                    'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
                ]);
        
        return redirect()->back()->with('msg', 'Product has been updated sucessfully');
    }

    public function showEditProductPrice($product_id) 
    {
        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $product_price_list = DB::table('product_price')
            ->leftJoin('size', 'product_price.size_id', '=', 'size.id')
            ->where('product_price.product_id', $product_id)
            ->select('product_price.*', 'size.size')
            ->get();

        $product_detail = DB::table('product')
            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
            ->where('product.id', $product_id)
            ->select('niece_category.*')
            ->first();

        $size_list = DB::table('size_mapping')
            ->leftJoin('size', 'size_mapping.size_id', '=', 'size.id')
            ->where('size_mapping.sub_category_id', $product_detail->sub_category_id)
            ->select('size_mapping.*', 'size.size')
            ->get();

        return view('admin.product.action.edit_product_price', ['product_id' => $product_id, 'product_price_list' => $product_price_list, 'size_list' => $size_list]);
    }

    public function updateProductPrice(Request $request, $product_id) 
    {
        $request->validate([
            'size_id' => 'required',
            'stock' => 'required',
            'price' => 'required',
        ]);

        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        for ($i=0; $i < count($request->input('size_id')); $i++) { 
            $count = DB::table('product_price')
                ->where('product_price.product_id', $product_id)
                ->where('product_price.size_id', $request->input('size_id')[$i])
                ->count();

            if ($count > 0) {
                DB::table('product_price')
                    ->where('product_price.product_id', $product_id)
                    ->where('product_price.size_id', $request->input('size_id')[$i])
                    ->update([
                        'stock' => $request->input('stock')[$i],
                        'price' => $request->input('price')[$i],
                        'discount' => $request->input('discount')[$i],
                        'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
                    ]);
            } 
            else
            {
                if ($request->input('stock')[$i] > 0) {

                    DB::table('product_price')
                        ->insert([
                            'product_id' => $product_id,
                            'size_id' => $request->input('size_id')[$i],
                            'stock' => $request->input('stock')[$i],
                            'price' => $request->input('price')[$i],
                            'discount' => $request->input('discount')[$i],
                            'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
                        ]);
                }
            }
        }

        return redirect()->back()->with('msg', 'Price and Stock has added updated sucessfully');
    }

    public function changeProductStatus($product_id, $status) 
    {
        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        DB::table('product')
            ->where('id', $product_id)
            ->update([
                'status' => $status
            ]);

        return redirect()->back();
    }

    public function activeProductList()
    {
        return view('admin.product.product_list.active_product_list');
    }

    public function inactiveProductList()
    {
        return view('admin.product.product_list.in_active_product_list');
    }

    public function activeInactiveProductListData(Request $request)
    {
        $columns = array( 
                            0 => 'id', 
                            1 => 'state',
                            2 => 'district',
                            3 => 'product_name_english',
                            4 => 'product_name_assamese',
                            5 => 'niece_category',
                            6 => 'sub_category',
                            7 => 'top_category',
                            8 => 'brand',
                            9 => 'product_images',
                            10 => 'action',
                        );

        $totalData = DB::table('product')
            ->where('product.status', $request->input('status'))
            ->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))) {            
            
            $product_data = DB::table('product')
                            ->leftJoin('district', 'product.district_id', '=', 'district.id')
                            ->leftJoin('states', 'district.state_id', '=', 'states.id')
                            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
                            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
                            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
                            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
                            ->where('product.status', $request->input('status'))
                            ->select('product.*', 'sub_category.sub_cate_name', 'top_category.top_cate_name', 'brand.brand_name', 'niece_category.niece_cate_name', 'district.district_name', 'states.state_name')
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
        }
        else {

            $search = $request->input('search.value'); 

            $product_data = DB::table('product')
                            ->leftJoin('district', 'product.district_id', '=', 'district.id')
                            ->leftJoin('states', 'district.state_id', '=', 'states.id')
                            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
                            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
                            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
                            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
                            ->select('product.*', 'sub_category.sub_cate_name', 'top_category.top_cate_name', 'brand.brand_name', 'niece_category.niece_cate_name', 'district.district_name', 'states.state_name')
                            ->where('product.status', $request->input('status'))
                            ->where('top_category.top_cate_name','LIKE',"%{$search}%")
                            ->orWhere('sub_category.sub_cate_name', 'LIKE',"%{$search}%")
                            ->orWhere('niece_category.niece_cate_name', 'LIKE',"%{$search}%")
                            ->orWhere('brand.brand_name', 'LIKE',"%{$search}%")
                            ->orWhere('product.product_name', 'LIKE',"%{$search}%")
                            ->orWhere('district.district_name', 'LIKE',"%{$search}%")
                            ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = DB::table('product')
                            ->leftJoin('district', 'product.district_id', '=', 'district.id')
                            ->leftJoin('states', 'district.state_id', '=', 'states.id')
                            ->leftJoin('niece_category', 'product.niece_category_id', '=', 'niece_category.id')
                            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
                            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
                            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
                            ->select('product.*', 'sub_category.sub_cate_name', 'top_category.top_cate_name', 'brand.brand_name', 'niece_category.niece_cate_name', 'district.district_name', 'states.state_name')
                            ->where('product.status', $request->input('status'))
                            ->where('top_category.top_cate_name','LIKE',"%{$search}%")
                            ->orWhere('sub_category.sub_cate_name', 'LIKE',"%{$search}%")
                            ->orWhere('niece_category.niece_cate_name', 'LIKE',"%{$search}%")
                            ->orWhere('brand.brand_name', 'LIKE',"%{$search}%")
                            ->orWhere('product.product_name', 'LIKE',"%{$search}%")
                            ->orWhere('product.price', 'LIKE',"%{$search}%")
                            ->orWhere('district.district_name', 'LIKE',"%{$search}%")
                            ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();

        if(!empty($product_data)) {

            $cnt = 1;

            foreach ($product_data as $single_data) {

                if($single_data->status == 1)
                    $val = "<a href=\"".route('admin.change_product_status', ['product_id' => encrypt($single_data->id), 'status' => encrypt(2)])."\" class=\"btn btn-sm btn-default\">Disabled</a>";
                else
                    $val = "<a href=\"".route('admin.change_product_status', ['product_id' => encrypt($single_data->id), 'status' => encrypt(1)])."\" class=\"btn btn-sm btn-success\">Enabled</a>";

                $nestedData['id']            = $cnt;
                $nestedData['state']  = $single_data->state_name;
                $nestedData['district']  = $single_data->district_name;
                $nestedData['product_name_english']  = $single_data->product_name_english;
                $nestedData['product_name_assamese']  = $single_data->product_name_assamese;
                $nestedData['niece_category']  = $single_data->niece_cate_name;
                $nestedData['sub_category']  = $single_data->sub_cate_name;
                $nestedData['top_category']  = $single_data->top_cate_name;
                $nestedData['brand']         = $single_data->brand_name;
                $nestedData['product_images']  = "&emsp;<a href=\"".route('admin.additional_product_image_list', ['product_id' => encrypt($single_data->id)])."\" class=\"btn btn-primary\" target=\"_blank\">Product Images List</a>";

                $nestedData['action']  = "&emsp;<div class=\"btn-group\"><a href=\"".route('admin.view_product', ['product_id' => encrypt($single_data->id)])."\" class=\"btn btn-sm btn-primary\" target=\"_blank\">View</a><a href=\"".route('admin.edit_product', ['product_id' => encrypt($single_data->id)])."\" class=\"btn btn-sm btn-warning\" target=\"_blank\">Edit</a><a href=\"".route('admin.edit_product_price', ['product_id' => encrypt($single_data->id)])."\" class=\"btn btn-sm btn-info\" target=\"_blank\">Price & Stock</a>$val</div>";

                $data[] = $nestedData;

                $cnt++;
            }
        }

        $json_data = array(
                        "draw"            => intval($request->input('draw')),  
                        "recordsTotal"    => intval($totalData),  
                        "recordsFiltered" => intval($totalFiltered), 
                        "data"            => $data   
                    );
            
        print json_encode($json_data); 
    }

    public function changeProductPriceStatus($product_price_id, $status) 
    {
        try {
            $product_price_id = decrypt($product_price_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        DB::table('product_price')
            ->where('id', $product_price_id)
            ->update([
                'status' => $status
            ]);

        return redirect()->back();
    }

    public function makeDiscountProduct($product_id, $status) 
    {
        try {
            $product_id = decrypt($product_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $count = DB::table('product')
            ->where('make_discount_product', 1)
            ->count();

        if ($status == 1) {
            if ($count < 3) {

                DB::table('product')
                    ->where('id', $product_id)
                    ->update([
                        'make_discount_product' => 1
                    ]);
            } else {

                DB::table('product')
                    ->where('id', $product_id)
                    ->update([
                        'make_discount_product' => 2
                    ]);
            }
        } else {

            DB::table('product')
                ->where('id', $product_id)
                ->update([
                    'make_discount_product' => 2
                ]);
        }

        return redirect()->back();
    }
}
