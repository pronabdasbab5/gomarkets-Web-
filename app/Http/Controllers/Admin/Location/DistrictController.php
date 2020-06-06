<?php

namespace App\Http\Controllers\Admin\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class DistrictController extends Controller
{
    public function showNewDistrictForm() 
    {
        /** Fetching All State **/
        $state_list = DB::table('states')
        	->where('status', 1)
        	->get();

        return view('admin.location.district.new_district', ['state_list' => $state_list]);
    }

    public function addDistrict(Request $request)
    {
        /** Validation **/
        $request->validate([
            'state_id'      => 'required',
            'district_name' => 'required',
        ],
        [
            'state_id.required'      => 'State Name is required',
            'district_name.required' => 'District Name is required',
        ]);

        $count_district = DB::table('district')
            ->where('state_id', $request->input('state_id'))
            ->where('district_name', ucwords(strtolower($request->input('district_name'))))
            ->count();

        if($count_district > 0)
            return redirect()->back()->with('error', 'District already added');
        
        /** Insert District Name **/
        DB::table('district')
            ->insert([
                'state_id'      => $request->input('state_id'),
                'district_name' => ucwords(strtolower($request->input('district_name'))),
                'created_at'    => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        /** End of Insertion **/
        
        return redirect()->back()->with('msg', 'District has been added successfully');
    }

    public function districtList() 
    {
        return view('admin.location.district.district_list');
    }

    public function districtListData(Request $request)
    {
        $columns = array( 
                            0 => 'id', 
                            1 => 'district',
                            2 => 'state',
                            3 => 'status',
                            4 => 'created_at',
                            5 => 'updated_at',
                            6 => 'action',
                        );

        $totalData = DB::table('district')->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))) {            
            
            $district_data = DB::table('district')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('district.*', 'states.state_name')
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
        }
        else {

            $search = $request->input('search.value'); 

            $district_data = DB::table('district')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('district.*', 'states.state_name')
                                ->where('district.district_name','LIKE',"%{$search}%")
                                ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = DB::table('district')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('district.*', 'states.state_name')
                                ->where('district.district_name','LIKE',"%{$search}%")
                                ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                                ->count();
        }

        $data = array();

        if(!empty($district_data)) {

            $cnt = 1;

            foreach ($district_data as $single_data) {

                if($single_data->status == 1)
                    $status = "<a href=\"".route('admin.district_change_status', ['district_id' => encrypt($single_data->id), encrypt(2)])."\" class=\"btn btn-danger btn-sm\">Disabled</a>";
                else
                    $status = "<a href=\"".route('admin.district_change_status', ['district_id' => encrypt($single_data->id), encrypt(1)])."\" class=\"btn btn-success btn-sm\">Enabled</a>";

                if($single_data->status == 1)
                    $status_show = "<button class=\"btn btn-success btn-sm\">Enabled</button>";
                else
                    $status_show = "<button class=\"btn btn-danger btn-sm\">Disabled</button>";

                $nestedData['id']       = $cnt;
                $nestedData['district'] = $single_data->district_name;
                $nestedData['state']    = $single_data->state_name;
                $nestedData['status']   = $status_show;
                $nestedData['created_at']  = \Carbon\Carbon::parse($single_data->created_at)->toDayDateTimeString();
                $nestedData['updated_at']  = \Carbon\Carbon::parse($single_data->updated_at)->toDayDateTimeString();
                $nestedData['action']  = "<div class=\"btn-group\">$status<a href=\"".route('admin.edit_district_form', ['district_id' => encrypt($single_data->id)])."\" class=\"btn btn-warning btn-sm\" >Edit</a></div>";

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

    public function showEditDistrictForm($district_id) 
    {
        try {
            $district_id = decrypt($district_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        /** District Record **/
        $district_record = DB::table('district')
            ->where('id', $district_id)
            ->first();

        /** All State **/
        $state_list = DB::table('states')
            ->where('status', 1)
            ->get();

        return view('admin.location.district.edit_district', ['district_record' => $district_record, 'state_list' => $state_list]);
    }

    public function updateDistrict(Request $request, $district_id)
    {
        /** Validation **/
        $request->validate([
            'state_id'      => 'required',
            'district_name' => 'required',
        ],
        [
            'state_id.required'      => 'State Name is required',
            'district_name.required' => 'District Name is required',
        ]);
        /** End of District Name **/

        try {
            $district_id = decrypt($district_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $count_district = DB::table('district')
            ->where('state_id', $request->input('state_id'))
            ->where('district_name', ucwords(strtolower($request->input('district_name'))))
            ->count();

        if($count_district > 0)
            return redirect()->back()->with('msg', 'District has been updated successfully');
        
        /** Update District Name **/
        DB::table('district')
            ->where('id', $district_id)
            ->update([
                'state_id'      => $request->input('state_id'),
                'district_name' => ucwords(strtolower($request->input('district_name'))),
                'updated_at'    => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        /** End of Insertion **/
        
        return redirect()->back()->with('msg', 'District has been updated successfully');
    }

    public function changeStatus($district_id, $status)
    {
        try {
            $district_id = decrypt($district_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        
        /** Update Status **/
        DB::table('district')
            ->where('id', $district_id)
            ->update([
                'status' => $status,
            ]);
        
        return redirect()->back();
    }
}
