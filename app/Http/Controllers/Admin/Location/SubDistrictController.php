<?php

namespace App\Http\Controllers\Admin\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class SubDistrictController extends Controller
{
    public function showNewSubDistrictForm() 
    {
        /** Fetching All State **/
        $state_list = DB::table('states')
        	->where('status', 1)
        	->get();

        return view('admin.location.sub_district.new_sub_district', ['state_list' => $state_list]);
    }

    public function addSubDistrict(Request $request)
    {
        /** Validation **/
        $request->validate([
            'district_id'       => 'required',
            'sub_district_name' => 'required',
        ],
        [
            'district_id.required'       => 'District Name is required',
            'sub_district_name.required' => 'District Name is required',
        ]);

        $count_sub_district = DB::table('sub_district')
            ->where('district_id', $request->input('district_id'))
            ->where('sub_district_name', ucwords(strtolower($request->input('sub_district_name'))))
            ->count();

        if($count_sub_district > 0)
            return redirect()->back()->with('error', 'Sub-District already added');
        
        /** Insert Sub-District Name **/
        DB::table('sub_district')
            ->insert([
                'district_id'       => $request->input('district_id'),
                'sub_district_name' => ucwords(strtolower($request->input('sub_district_name'))),
                'created_at'        => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        
        return redirect()->back()->with('msg', 'Sub-District has been added successfully');
    }

    public function subDistrictList() 
    {
        return view('admin.location.sub_district.sub_district_list');
    }

    public function subDistrictListData(Request $request)
    {
        $columns = array( 
                            0 => 'id', 
                            1 => 'sub_district',
                            2 => 'district',
                            3 => 'state',
                            4 => 'status',
                            5 => 'created_at',
                            6 => 'updated_at',
                            7 => 'action',
                        );

        $totalData = DB::table('sub_district')->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))) {            
            
            $sub_district_data = DB::table('sub_district')
                                ->leftJoin('district', 'sub_district.district_id', '=', 'district.id')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('sub_district.*', 'district.district_name', 'states.state_name')
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
        }
        else {

            $search = $request->input('search.value'); 

            $sub_district_data = DB::table('sub_district')
                                ->leftJoin('district', 'sub_district.district_id', '=', 'district.id')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('sub_district.*', 'district.district_name', 'states.state_name')
                                ->where('sub_district.sub_district_name','LIKE',"%{$search}%")
                                ->orWhere('district.district_name','LIKE',"%{$search}%")
                                ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = DB::table('sub_district')
                                ->leftJoin('district', 'sub_district.district_id', '=', 'district.id')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('sub_district.*', 'district.district_name', 'states.state_name')
                                ->where('sub_district.sub_district_name','LIKE',"%{$search}%")
                                ->orWhere('district.district_name','LIKE',"%{$search}%")
                                ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                                ->count();
        }

        $data = array();

        if(!empty($sub_district_data)) {

            $cnt = 1;

            foreach ($sub_district_data as $single_data) {

                if($single_data->status == 1)
                    $status = "<a href=\"".route('admin.sub_district_change_status', ['sub_district_id' => encrypt($single_data->id), encrypt(2)])."\" class=\"btn btn-danger btn-sm\">Disabled</a>";
                else
                    $status = "<a href=\"".route('admin.sub_district_change_status', ['sub_district_id' => encrypt($single_data->id), encrypt(1)])."\" class=\"btn btn-success btn-sm\">Enabled</a>";

                if($single_data->status == 1)
                    $status_show = "<button class=\"btn btn-success btn-sm\">Enabled</button>";
                else
                    $status_show = "<button class=\"btn btn-danger btn-sm\">Disabled</button>";

                $nestedData['id']           = $cnt;
                $nestedData['sub_district'] = $single_data->sub_district_name;
                $nestedData['district']     = $single_data->district_name;
                $nestedData['state']        = $single_data->state_name;
                $nestedData['status']       = $status_show;
                $nestedData['created_at']  = \Carbon\Carbon::parse($single_data->created_at)->toDayDateTimeString();
                $nestedData['updated_at']  = \Carbon\Carbon::parse($single_data->updated_at)->toDayDateTimeString();
                $nestedData['action']       = "<div class=\"btn-group\">$status<a href=\"".route('admin.edit_sub_district_form', ['sub_district_id' => encrypt($single_data->id)])."\" class=\"btn btn-warning btn-sm\" >Edit</a></div>";

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

    public function showEditSubDistrictForm($sub_district_id) 
    {
        try {
            $sub_district_id = decrypt($sub_district_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        /** Sub-District Record **/
        $sub_district_record = DB::table('sub_district')
            ->leftJoin('district', 'sub_district.district_id', '=', 'district.id')
            ->leftJoin('states', 'district.state_id', '=', 'states.id')
            ->where('sub_district.id', $sub_district_id)
            ->select('sub_district.*', 'district.district_name', 'states.state_name')
            ->first();

        return view('admin.location.sub_district.edit_sub_district', ['sub_district_record' => $sub_district_record]);
    }

    public function updateSubDistrict(Request $request, $sub_district_id)
    {
        /** Validation **/
        $request->validate([
            'sub_district_name' => 'required',
        ],
        [
            'sub_district_name.required' => 'Sub-District Name is required',
        ]);

        try {
            $sub_district_id = decrypt($sub_district_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        
        /** Update Sub-District **/
        DB::table('sub_district')
            ->where('id', $sub_district_id)
            ->update([
                'sub_district_name' => ucwords(strtolower($request->input('sub_district_name'))),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        
        return redirect()->back()->with('msg', 'Sub-District has been updated successfully');
    }

    public function changeStatus($sub_district_id, $status)
    {
        try {
            $sub_district_id = decrypt($sub_district_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        
        /** Update Status **/
        DB::table('sub_district')
            ->where('id', $sub_district_id)
            ->update([
                'status' => $status,
            ]);
        
        return redirect()->back();
    }
}
