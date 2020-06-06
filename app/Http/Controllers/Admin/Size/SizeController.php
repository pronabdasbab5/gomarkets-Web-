<?php

namespace App\Http\Controllers\Admin\Size;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class SizeController extends Controller
{
    public function showSizeForm() 
    {
        return view('admin.size.new_size');
    }

    public function addSize(Request $request) 
    {
        $request->validate([
            'size' => 'required',
        ]);

        $size_count = DB::table('size')
        	->where('size', ucwords(strtolower($request->input('size'))))
        	->count();

        if ($size_count > 0)
        	return redirect()->back()->with('error', 'Size has been already added');

        DB::table('size')
	        ->insert([ 
	          	'size' => ucwords(strtolower($request->input('size'))), 
	          	'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
	        ]);

        return redirect()->back()->with('msg', 'Size has been added successfully');
    }

    public function sizeList () 
    {
        $size_list = DB::table('size')
        	->get();

        return view('admin.size.size_list', ['size_list' => $size_list]);
    }

    public function showEditSizeForm($size_id) 
    {
        try {
            $size_id = decrypt($size_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $size_record = DB::table('size')
            ->where('id', $size_id)
            ->first();

        return view('admin.size.edit_size', ['size_record' => $size_record]);
    }

    public function updateSize(Request $request, $size_id) 
    {
        $request->validate([
            'size' => 'required',
        ]);

        try {
            $size_id = decrypt($size_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $size_count = DB::table('size')
        	->where('size', ucwords(strtolower($request->input('size'))))
        	->count();

        if ($size_count > 0)
        	return redirect()->back()->with('msg', 'Size has been updated successfully');

        DB::table('size')
            ->where('id', $size_id)
            ->update([ 
                'size' => ucwords(strtolower($request->input('size')))
            ]);

        return redirect()->back()->with('msg', 'Size has been updated successfully');
    }

    public function showMappingSizeForm() 
    {
        $top_category_list = DB::table('top_category')->get();
        $size_list = DB::table('size')->get();

        $mapping_size_list = DB::table('size_mapping')
        	->leftJoin('size', 'size_mapping.size_id', '=', 'size.id')
            ->leftJoin('sub_category', 'size_mapping.sub_category_id', '=', 'sub_category.id')
            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
        	->select('size_mapping.*', 'size.size', 'sub_category.sub_cate_name', 'top_category.top_cate_name')
        	->get();

        return view('admin.size.new_mapping_size', ['top_category_list' => $top_category_list, 'size_list' => $size_list, 'mapping_size_list' => $mapping_size_list]);
    }

    public function addMappingSize(Request $request) 
    {
        $request->validate([
            'sub_category_id' => 'required',
            'size' => 'required'
        ]);

        $cnt = DB::table('size_mapping')
	        ->where('sub_category_id', $request->input('sub_category_id'))
	        ->where('size_id', $request->input('size'))
	        ->count();

	    if ($cnt > 0)
        	return redirect()->back()->with('error', 'Size already added');
        else {
        	DB::table('size_mapping')
	            ->insert([ 
	            	'sub_category_id' => $request->input('sub_category_id'), 
                    'size_id' => $request->input('size'), 
                    'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
	            ]);

        	return redirect()->back()->with('msg', 'Size has been mapped successfully');
        }
    }

    public function showEditMappingSizeForm($size_mapping_id) 
    {
        try {
            $size_mapping_id = decrypt($size_mapping_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $mapping_size_record = DB::table('size_mapping')
            ->where('id', $size_mapping_id)
            ->first();

        $sub_category_record = DB::table('sub_category')
            ->where('id', $mapping_size_record->sub_category_id)
            ->first();

        $top_category_record = DB::table('top_category')
            ->where('id', $sub_category_record->top_category_id)
            ->first();

        $size_list = DB::table('size')->get();

        return view('admin.size.edit_mapping_size', ['top_category_record' => $top_category_record, 'size_list' => $size_list, 'mapping_size_record' => $mapping_size_record, 'sub_category_record' => $sub_category_record]);
    }

    public function updateMappingSize(Request $request, $size_mapping_id)
    {
        try {
            $size_mapping_id = decrypt($size_mapping_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $size_mapping_record = DB::table('size_mapping')
            ->where('id', $size_mapping_id)
            ->first();

        $cnt = DB::table('size_mapping')
            ->where('sub_category_id', $size_mapping_record->sub_category_id)
            ->where('size_id', $request->input('size'))
            ->count();

        if ($cnt > 0)
            return redirect()->back()->with('msg', 'Mapping has been already done');
        else {
            DB::table('size_mapping')
            ->where('id', $size_mapping_id)
            ->update([
                'size_id' => $request->input('size')
            ]);

            return redirect()->back()->with('msg', 'Mapping has been updated');
        }
    }
}
