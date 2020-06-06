<?php

namespace App\Http\Controllers\Admin\Mobile\Offer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Carbon\Carbon;

class OfferController extends Controller
{
    function showOfferForm() 
    {
        return view('admin.mobile.offer.new_offer');
    }

    public function addOffer(Request $request) 
    {
        $this->validate($request, [
            'offer' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($request->hasFile('offer')) {

            if(!File::exists(public_path()."/assets"))
                File::makeDirectory(public_path()."/assets");

            if(!File::exists(public_path()."/assets/mobile"))
                File::makeDirectory(public_path()."/assets/mobile");

            if(!File::exists(public_path()."/assets/mobile/offer"))
                File::makeDirectory(public_path()."/assets/mobile/offer");

            $offer = $request->file('offer');
            $file   = time().'.'.$offer->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/mobile/offer');
            $img = Image::make($offer->getRealPath());
            $img->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('app_offer')
	            ->insert([ 
	            	'banner' => $file, 
                    'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString()
	            ]);

            return redirect()->back()->with('msg', 'Offer banner has been added successfully');
        } else 
        	return redirect()->back()->with('error', 'Please ! select a offer');
    }

    public function offerList() 
    {
        $offer_list = DB::table('app_offer')
            ->get();
            
        return view('admin.mobile.offer.offer_list', ['offer_list' => $offer_list]);
    }

    public function changeOfferStatus($offer_id, $status) 
    {
        try {
            $offer_id = decrypt($offer_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        DB::table('app_offer')
            ->where('id', $offer_id)
            ->update([
                'status' => $status
            ]);

        DB::table('app_offer')
            ->where('id', '!=', $offer_id)
            ->update([
                'status' => 2
            ]);    

        return redirect()->back();
    }

    public function showEditOfferForm($offer_id) 
    {
        try {
            $offer_id = decrypt($offer_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $offer_record = DB::table('app_offer')
            ->where('id', $offer_id)
            ->first();

        return view('admin.mobile.offer.edit_offer', ['offer_record' => $offer_record]);
    }

    public function updateOffer(Request $request, $offer_id) 
    {
        $this->validate($request, [
            'banner'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $offer_id = decrypt($offer_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $old_file_name = DB::table('app_offer')
            ->where('id', $offer_id)
            ->first();

        if ($request->hasFile('banner')) {
            File::delete(public_path('assets/mobile/offer/'.$old_file_name->banner));

            $banner = $request->file('banner');
            $file   = time().'.'.$banner->getClientOriginalExtension();
         
            $destinationPath = public_path('/assets/mobile/offer');
            $img = Image::make($banner->getRealPath());
            $img->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$file);

            DB::table('app_offer')
            ->where('id', $offer_id)
            ->update([ 
                'banner' => $file,
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        }

        return redirect()->back()->with('msg', 'Offer banner has been updated successfully');
    }
}
