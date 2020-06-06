<?php

namespace App\Http\Controllers\Admin\Brand;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Carbon\Carbon;

class BrandController extends Controller
{
    public function showBrandForm() 
    {
        return view('admin.brand.new_brand');
    }

    public function addBrand(Request $request) 
    {
        $request->validate([
            'brand_name'    => 'required',
            'banner'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand_count = DB::table('brand')
        	->where('brand_name', ucwords(strtolower($request->input('brand_name'))))
        	->count();

        if ($brand_count > 0)
        	return redirect()->back()->with('error', 'Banner already added.');

        if ($request->hasFile('banner')) {

        	if(!File::exists(public_path()."/assets"))
                File::makeDirectory(public_path()."/assets");

            if(!File::exists(public_path()."/assets/brand"))
                File::makeDirectory(public_path()."/assets/brand");

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/brand');
            $img = Image::make($banner->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('brand')
	            ->insert([ 
	            	'brand_name' => ucwords(strtolower($request->input('brand_name'))), 
	            	'banner' => $file, 
	            	'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
	            ]);

            return redirect()->back()->with('msg', 'Brand has been added successfully');
        } else 
        	return redirect()->back()->with('error', 'Please ! select a banner');
    }

    public function brandList() 
    {
        $brand_list = DB::table('brand')
        	->get();

        return view('admin.brand.brand_list', ['brand_list' => $brand_list]);
    }

    public function changeStatus($brand_id, $status)
    {
        try {
            $brand_id = decrypt($brand_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        
        /** Update Status **/
        DB::table('brand')
            ->where('id', $brand_id)
            ->update([
                'status' => $status,
            ]);
        
        return redirect()->back();
    }

    public function showEditBrandForm($brand_id) 
    {
        try {
            $brand_id = decrypt($brand_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $brand_record = DB::table('brand')
            ->where('id', $brand_id)
            ->first();

        return view('admin.brand.edit_brand', ['brand_record' => $brand_record]);
    }

    public function updateBrand(Request $request, $brand_id) 
    {
        $this->validate($request, [
            'brand_name'    => 'required',
            'banner'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $brand_id = decrypt($brand_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $old_file_name = DB::table('brand')
            ->where('id', $brand_id)
            ->first();

        if ($request->hasFile('banner')) {
            File::delete(public_path('assets/brand/'.$old_file_name->banner));

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/brand');
            $img = Image::make($banner->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('brand')
                ->where('id', $brand_id)
                ->update([ 
                    'banner' => $file,
                    'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
                ]);
        }

        $brand_count = DB::table('brand')
        	->where('brand_name', ucwords(strtolower($request->input('brand_name'))))
        	->count();

        if ($brand_count > 0)
        	return redirect()->back()->with('msg', 'Brand has been updated successfully');

        DB::table('brand')
            ->where('id', $brand_id)
            ->update([ 
                'brand_name' => ucwords(strtolower($request->input('brand_name'))),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);

        return redirect()->back()->with('msg', 'Brand has been updated successfully');
    }
}
