<?php

namespace App\Http\Controllers\Admin\CommonFunction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class CommonFunctionController extends Controller
{
    public function retriveDistrict(Request $request)
    {
    	$all_district = DB::table('district')
    		->where('state_id', $request->input('state_id'))
            ->where('status', 1)
    		->get();

    	$data = "<option value=\"\" disabled selected>Choose District</option>";
    	foreach ($all_district as $key => $item)
    		$data = $data."<option value=\"".$item->id."\">".$item->district_name."</option>";

    	print $data;
    }

    public function retriveSubDistrict(Request $request)
    {
        $all_sub_district = DB::table('sub_district')
            ->where('district_id', $request->input('district_id'))
            ->where('status', 1)
            ->get();

        $data = "<option value=\"\" disabled selected>Choose Sub-District</option>";
        foreach ($all_sub_district as $key => $item)
            $data = $data."<option value=\"".$item->id."\">".$item->sub_district_name."</option>";

        print $data;
    }

    public function retriveSubCategory(Request $request)
    {
        $all_sub_category = DB::table('sub_category')
            ->where('top_category_id', $request->input('category_id'))
            ->get();

        $data = "<option value=\"\" disabled selected>Choose Sub-Category</option>";
        foreach ($all_sub_category as $key => $value)
            $data = $data."<option value=\"".$value->id."\">".$value->sub_cate_name."</option>";

        print $data;
    }

    public function retriveNieceCategory(Request $request)
    {
        $all_niece_category = DB::table('niece_category')
            ->where('sub_category_id', $request->input('sub_category_id'))
            ->get();

        $data = "<option value=\"\" disabled selected>Choose Niece-Category</option>";
        foreach ($all_niece_category as $key => $value)
            $data = $data."<option value=\"".$value->id."\">".$value->niece_cate_name."</option>";

        print $data;
    }

    public function retriveSize(Request $request)
    {
        $all_size = DB::table('size_mapping')
            ->leftJoin('size', 'size_mapping.size_id', '=', 'size.id')
            ->where('size_mapping.niece_category_id', $request->input('niece_category_id'))
            ->get();


        $data = "";
        foreach ($all_size as $key => $value)
            $data = $data."<input type=\"checkbox\" name=\"size_id[]\" value=\"".$value->id."\"> ".$value->size." ";;

        print $data;
    }
}
