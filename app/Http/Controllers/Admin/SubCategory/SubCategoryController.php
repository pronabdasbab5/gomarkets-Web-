<?php

namespace App\Http\Controllers\Admin\SubCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Carbon\Carbon;

class SubCategoryController extends Controller
{
    public function showSubCategoryForm () 
    {
        $top_category_list = DB::table('top_category')
            ->get();

        return view('admin.sub_category.new_sub_category', ['top_category_list' => $top_category_list]);
    }

    public function addSubCategory(Request $request) 
    {
        $this->validate($request, [
            'top_category_id' => 'required|numeric',
            'sub_cate_name' => 'required',
            'banner'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('banner')) {

            $count_sub_category = DB::table('sub_category')
                ->where('top_category_id', $request->input('top_category_id'))
                ->where('sub_cate_name', ucwords(strtolower($request->input('sub_cate_name'))))
                ->count();

            if($count_sub_category > 0)
                return redirect()->back()->with('error', 'Sub-Category has been already added');
                
            if(!File::exists(public_path()."/assets"))
                File::makeDirectory(public_path()."/assets");

            if(!File::exists(public_path()."/assets/sub_category"))
                File::makeDirectory(public_path()."/assets/sub_category");

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/sub_category');
            $img = Image::make($banner->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('sub_category')
	            ->insert([ 
	            	'top_category_id' => $request->input('top_category_id'), 
	            	'sub_cate_name' => $request->input('sub_cate_name'), 
	            	'banner' => $file, 
                    'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
	            ]);

            return redirect()->back()->with('msg', 'Sub-Category has been added successfully');
        } else 
        	return redirect()->back()->with('msg', 'Please ! select a banner');
    }

    public function subCategoryList () 
    {
        $sub_category_list = DB::table('sub_category')
            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
            ->select('sub_category.*', 'top_category.top_cate_name')
            ->get();

        return view('admin.sub_category.sub_category_list', ['sub_category_list' => $sub_category_list]);
    }

    public function showEditSubCategoryForm($sub_category_id) 
    {
        try {
            $sub_category_id = decrypt($sub_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $sub_category_record = DB::table('sub_category')
            ->where('id', $sub_category_id)
            ->first();

        $top_category_list = DB::table('top_category')->get();

        return view('admin.sub_category.edit_sub_category', ['sub_category_record' => $sub_category_record, 'top_category_list' => $top_category_list]);
    }

    public function updateSubCategory(Request $request, $sub_category_id) 
    {
        $this->validate($request, [
            'top_category_id' => 'required',
            'sub_cate_name' => 'required',
            'banner'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $sub_category_id = decrypt($sub_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $old_file_name = DB::table('sub_category')
            ->where('id', $sub_category_id)
            ->first();

        if ($request->hasFile('banner')) {
            File::delete(public_path('assets/sub_category/'.$old_file_name->banner));

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/sub_category');
            $img = Image::make($banner->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('sub_category')
                ->where('id', $sub_category_id)
                ->update([ 
                    'banner' => $file,
                    'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
                ]);
        }

        $count_sub_category = DB::table('sub_category')
            ->where('top_category_id', $request->input('top_category_id'))
            ->where('sub_cate_name', ucwords(strtolower($request->input('sub_cate_name'))))
            ->count();

        if($count_sub_category > 0)
            return redirect()->back()->with('sg', 'Sub-Category has been updated successfully');

        DB::table('sub_category')
            ->where('id', $sub_category_id)
            ->update([ 
                'top_category_id' => $request->input('top_category_id'),
                'sub_cate_name' => ucwords(strtolower($request->input('sub_cate_name'))),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);

        return redirect()->back()->with('msg', 'Sub-Category has been updated successfully');
    }

    public function changeStatus($sub_category_id, $status)
    {
        try {
            $sub_category_id = decrypt($sub_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $top_category = DB::table('sub_category')
            ->leftJoin('top_category', 'sub_category.top_category_id', '=', 'top_category.id')
            ->where('sub_category.id', $sub_category_id)
            ->select('top_category.*')
            ->first();

        if ($top_category->status == 2) {

            /** Update Status **/
            DB::table('sub_category')
                ->where('id', $sub_category_id)
                ->update([
                    'status' => 2,
                ]);

            /** Update Status **/
            DB::table('niece_category')
                ->where('sub_category_id', $sub_category_id)
                ->update([
                    'status' => 2,
                ]);
        } else {
        
            /** Update Status **/
            DB::table('sub_category')
                ->where('id', $sub_category_id)
                ->update([
                    'status' => $status,
                ]);

            /** Update Status **/
            DB::table('niece_category')
                ->where('sub_category_id', $sub_category_id)
                ->update([
                    'status' => $status,
                ]);
        }
        
        return redirect()->back();
    }
}
