<?php

namespace App\Http\Controllers\Admin\TopCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Carbon\Carbon;

class TopCategoryController extends Controller
{
    public function showTopCategoryForm () 
    {
        return view('admin.top_category.new_top_category');
    }

    public function topCategoryList () 
    {
        $top_category_list = DB::table('top_category')
            ->get();

        return view('admin.top_category.top_category_list', ['top_category_list' => $top_category_list]);
    }

    public function addTopCategory(Request $request) 
    {
        $this->validate($request, [
            'top_cate_name' => 'required',
            'banner'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('banner')) {

            $count_top_category = DB::table('top_category')
                ->where('top_cate_name', ucwords(strtolower($request->input('top_cate_name'))))
                ->count();

            if($count_top_category > 0)
                return redirect()->back()->with('error', 'Top-Category has been already added');
                
            if(!File::exists(public_path()."/assets"))
                File::makeDirectory(public_path()."/assets");

            if(!File::exists(public_path()."/assets/top_category"))
                File::makeDirectory(public_path()."/assets/top_category");

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/top_category');
            $img = Image::make($banner->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('top_category')
                ->insert([ 
                    'top_cate_name' => ucwords(strtolower($request->input('top_cate_name'))), 
                    'banner' => $file, 
                    'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
                ]);

            return redirect()->back()->with('msg', 'Top-Category has been added successfully');
        } else 
            return redirect()->back()->with('msg', 'Please ! select a banner');
    }

    public function showEditTopCategoryForm($top_category_id) 
    {
    	try {
            $top_category_id = decrypt($top_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $top_category_record = DB::table('top_category')
        	->where('id', $top_category_id)
        	->first();

        return view('admin.top_category.edit_top_category', ['top_category_record' => $top_category_record]);
    }

    public function updateTopCategory(Request $request, $top_category_id) 
    {
        $this->validate($request, [
            'top_cate_name' => 'required',
            'banner'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $top_category_id = decrypt($top_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $old_file_name = DB::table('top_category')
            ->where('id', $top_category_id)
            ->get();

        if ($request->hasFile('banner')) {
            File::delete(public_path('assets/top_category/'.$old_file_name[0]->banner));

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/top_category');
            $img = Image::make($banner->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('top_category')
            ->where('id', $top_category_id)
            ->update([ 
                'banner' => $file,
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        }

        $count_top_category = DB::table('top_category')
            ->where('top_cate_name', ucwords(strtolower($request->input('top_cate_name'))))
            ->count();

        if($count_top_category > 0)
            return redirect()->back()->with('msg', 'Top-Category has been updated successfully');

        DB::table('top_category')
            ->where('id', $top_category_id)
            ->update([ 
                'top_cate_name' => ucwords(strtolower($request->input('top_cate_name'))),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);

        return redirect()->back()->with('msg', 'Top-Category has been updated successfully');
    }

    public function changeStatus($top_category_id, $status)
    {
        try {
            $top_category_id = decrypt($top_category_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        
        /** Update Status **/
        DB::table('top_category')
            ->where('id', $top_category_id)
            ->update([
                'status' => $status,
            ]);

        /** Update Status **/
        DB::table('sub_category')
            ->where('top_category_id', $top_category_id)
            ->update([
                'status' => $status,
            ]);
        
        return redirect()->back();
    }
}
