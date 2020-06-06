<?php

namespace App\Http\Controllers\Admin\NieceCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Carbon\Carbon;

class NieceCategoryController extends Controller
{
    public function showNieceCategoryForm () 
    {
    	$top_category_list = DB::table('top_category')
            ->get();

        return view('admin.niece_category.new_niece_category', ['top_category_list' => $top_category_list]);
    }

    public function addNieceCategory(Request $request) 
    {
        $this->validate($request, [
            'top_category_id' => 'required',
            'sub_category_id' => 'required',
            'niece_cate_name' => 'required',
            'banner'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('banner')) {

            $count_niece_category = DB::table('niece_category')
                ->where('sub_category_id', $request->input('sub_category_id'))
                ->where('niece_cate_name', $request->input('niece_cate_name'))
                ->count();

            if($count_niece_category > 0)
                return redirect()->back()->with('error', 'Niece-Category has been already added');
                
            if(!File::exists(public_path()."/assets"))
                File::makeDirectory(public_path()."/assets");

            if(!File::exists(public_path()."/assets/niece_category"))
                File::makeDirectory(public_path()."/assets/niece_category");

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/niece_category');
            $img = Image::make($banner->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('niece_category')
	            ->insert([  
	            	'sub_category_id' => $request->input('sub_category_id'), 
	            	'niece_cate_name' => $request->input('niece_cate_name'), 
	            	'banner' => $file, 
                    'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
	            ]);

            return redirect()->back()->with('msg', 'Niece-Category has been added successfully');
        } else 
        	return redirect()->back()->with('msg', 'Please ! select a banner');
    }

    public function nieceCategoryList () 
    {
        $niece_category_list = DB::table('niece_category')
        	->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
            ->select('niece_category.*', 'sub_category.sub_cate_name', 'top_category.top_cate_name')
            ->get();

        return view('admin.niece_category.niece_category_list', ['niece_category_list' => $niece_category_list]);
    }

    public function showEditNieceCategoryForm($niece_category_id) 
    {
        try {
            $niece_category_id = decrypt($niece_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $niece_category_record = DB::table('niece_category')
            ->where('id', $niece_category_id)
            ->first();

        $sub_category_record = DB::table('sub_category')
            ->where('id', $niece_category_record->sub_category_id)
            ->first();

        $sub_category_list = DB::table('sub_category')
            ->where('top_category_id', $sub_category_record->top_category_id)
            ->get();

        $top_category_list = DB::table('top_category')
            ->get();

        return view('admin.niece_category.edit_niece_category', ['niece_category_record' => $niece_category_record, 'top_category_list' => $top_category_list, 'sub_category_list' => $sub_category_list, 'sub_category_record' => $sub_category_record]);
    }

    public function updateNieceCategory(Request $request, $niece_category_id) 
    {
        $this->validate($request, [
            'top_category_id' => 'required',
            'sub_category_id' => 'required',
            'niece_cate_name' => 'required',
            'banner'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $niece_category_id = decrypt($niece_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $old_file_name = DB::table('niece_category')
            ->where('id', $niece_category_id)
            ->first();

        if ($request->hasFile('banner')) {
            File::delete(public_path('assets/niece_category/'.$old_file_name->banner));

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/niece_category');
            $img = Image::make($banner->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('niece_category')
                ->where('id', $niece_category_id)
                ->update([ 
                    'banner' => $file,
                    'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
                ]);
        }

        $count_niece_category = DB::table('niece_category')
            ->where('sub_category_id', $request->input('sub_category_id'))
            ->where('niece_cate_name', $request->input('niece_cate_name'))
            ->count();

        if($count_niece_category > 0)
            return redirect()->back()->with('msg', 'Niece-Category has been updated successfully');

        DB::table('niece_category')
            ->where('id', $niece_category_id)
            ->update([ 
                'sub_category_id' => $request->input('sub_category_id'),
                'niece_cate_name' => $request->input('niece_cate_name'),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);

        return redirect()->back()->with('msg', 'Niece-Category has been updated successfully');
    }

    public function changeStatus($niece_category_id, $status)
    {
        try {
            $niece_category_id = decrypt($niece_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $sub_category = DB::table('niece_category')
            ->leftJoin('sub_category', 'niece_category.sub_category_id', '=', 'sub_category.id')
            ->where('niece_category.id', $niece_category_id)
            ->select('sub_category.*')
            ->first();

        if ($sub_category->status == 2) {

            /** Update Status **/
            DB::table('niece_category')
                ->where('id', $niece_category_id)
                ->update([
                    'status' => 2,
                ]);

        } else {
        
            /** Update Status **/
            DB::table('niece_category')
                ->where('id', $niece_category_id)
                ->update([
                    'status' => $status,
                ]);
        }
        
        return redirect()->back();
    }
}
