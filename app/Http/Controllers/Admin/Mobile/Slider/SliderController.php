<?php

namespace App\Http\Controllers\Admin\Mobile\Slider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Carbon\Carbon;

class SliderController extends Controller
{
    function showSliderForm() 
    {
    	return view('admin.mobile.slider.new_slider');
    }

    public function addSlider(Request $request) 
    {
        $this->validate($request, [
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($request->hasFile('banner')) {

            if(!File::exists(public_path()."/assets"))
                File::makeDirectory(public_path()."/assets");

            if(!File::exists(public_path()."/assets/mobile"))
                File::makeDirectory(public_path()."/assets/mobile");

            if(!File::exists(public_path()."/assets/mobile/slider"))
                File::makeDirectory(public_path()."/assets/mobile/slider");

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/mobile/slider');
            $img = Image::make($banner->getRealPath());
            $img->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('app_slider')
                ->insert([ 
                    'slider' => $file, 
                    'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
                ]);

            return redirect()->back()->with('msg', 'Slider has been added successfully');
        } else 
            return redirect()->back()->with('error', 'Please ! select a slider');
    }

    public function sliderList() 
    {
        $slider_list = DB::table('app_slider')->get();
        return view('admin.mobile.slider.slider_list', ['slider_list' => $slider_list]);
    }

    public function changeSliderStatus($slider_id, $status) 
    {
        try {
            $slider_id = decrypt($slider_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        DB::table('app_slider')
            ->where('id', $slider_id)
            ->update([
                'status' => $status
            ]);   

        return redirect()->back();
    }

    public function showEditSliderForm($slider_id) 
    {
        try {
            $slider_id = decrypt($slider_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $slider_record = DB::table('app_slider')
            ->where('id', $slider_id)
            ->first();

        return view('admin.mobile.slider.edit_slider', ['slider_record' => $slider_record]);
    }

    public function updateSlider(Request $request, $slider_id) 
    {
        $this->validate($request, [
            'banner'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $slider_id = decrypt($slider_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $old_file_name = DB::table('app_slider')
            ->where('id', $slider_id)
            ->first();

        if ($request->hasFile('banner')) {
            File::delete(public_path('assets/mobile/slider/'.$old_file_name->slider));

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/mobile/slider');
            $img = Image::make($banner->getRealPath());
            $img->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('app_slider')
            ->where('id', $slider_id)
            ->update([ 
                'slider' => $file,
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        }

        return redirect()->back()->with('msg', 'Slider has been updated successfully');
    }
}
