<?php

namespace App\Http\Controllers\Admin\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class AreaController extends Controller
{
    public function showNewAreaForm() 
    {
        /** Fetching All State **/
        $state_list = DB::table('states')
        	->where('status', 1)
        	->get();

        return view('admin.location.area.new_area', ['state_list' => $state_list]);
    }

    public function addArea(Request $request)
    {
        /** Validation **/
        $request->validate([
            'sub_district_id'       => 'required',
            'area_name' => 'required',
        ],
        [
            'sub_district_id.required'       => 'District Name is required',
            'area_name.required' => 'Area Name is required',
        ]);

        $count_area = DB::table('area')
            ->where('sub_district_id', $request->input('sub_district_id'))
            ->where('area_name', ucwords(strtolower($request->input('area_name'))))
            ->count();

        if($count_area > 0)
            return redirect()->back()->with('error', 'Area already added');
        
        /** Insert Sub-District Name **/
        DB::table('area')
            ->insert([
                'sub_district_id'       => $request->input('sub_district_id'),
                'area_name' => ucwords(strtolower($request->input('area_name'))),
                'created_at'        => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        
        return redirect()->back()->with('msg', 'Area has been added successfully');
    }

    public function areaList() 
    {
        return view('admin.location.area.area_list');
    }

    public function areaListData(Request $request)
    {
        $columns = array( 
                            0 => 'id', 
                            1 => 'area',
                            2 => 'sub_district',
                            3 => 'district',
                            4 => 'state',
                            5 => 'status',
                            6 => 'created_at',
                            7 => 'updated_at',
                            8 => 'action',
                        );

        $totalData = DB::table('area')->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))) {            
            
            $area_data = DB::table('area')
            					->leftJoin('sub_district', 'area.sub_district_id', '=', 'sub_district.id')
                                ->leftJoin('district', 'sub_district.district_id', '=', 'district.id')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('area.*', 'sub_district.sub_district_name', 'district.district_name', 'states.state_name')
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
        }
        else {

            $search = $request->input('search.value'); 

            $area_data = DB::table('area')
            					->leftJoin('sub_district', 'area.sub_district_id', '=', 'sub_district.id')
                                ->leftJoin('district', 'sub_district.district_id', '=', 'district.id')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('area.*', 'sub_district.sub_district_name', 'district.district_name', 'states.state_name')
                                ->where('area.area_name','LIKE',"%{$search}%")
                                ->orWhere('sub_district.sub_district_name','LIKE',"%{$search}%")
                                ->orWhere('district.district_name','LIKE',"%{$search}%")
                                ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

            $totalFiltered = DB::table('area')
            					->leftJoin('sub_district', 'area.sub_district_id', '=', 'sub_district.id')
                                ->leftJoin('district', 'sub_district.district_id', '=', 'district.id')
                                ->leftJoin('states', 'district.state_id', '=', 'states.id')
                                ->select('area.*', 'sub_district.sub_district_name', 'district.district_name', 'states.state_name')
                                ->where('area.area_name','LIKE',"%{$search}%")
                                ->orWhere('sub_district.sub_district_name','LIKE',"%{$search}%")
                                ->orWhere('district.district_name','LIKE',"%{$search}%")
                                ->orWhere('states.state_name', 'LIKE',"%{$search}%")
                                ->count();
        }

        $data = array();

        if(!empty($area_data)) {

            $cnt = 1;

            foreach ($area_data as $single_data) {

                if($single_data->status == 1)
                    $status = "<a href=\"".route('admin.area_change_status', ['area_id' => encrypt($single_data->id), encrypt(2)])."\" class=\"btn btn-danger btn-sm\">Disabled</a>";
                else
                    $status = "<a href=\"".route('admin.area_change_status', ['area_id' => encrypt($single_data->id), encrypt(1)])."\" class=\"btn btn-success btn-sm\">Enabled</a>";

                if($single_data->status == 1)
                    $status_show = "<button class=\"btn btn-success btn-sm\">Enabled</button>";
                else
                    $status_show = "<button class=\"btn btn-danger btn-sm\">Disabled</button>";

                $nestedData['id']           = $cnt;
                $nestedData['area'] = $single_data->area_name;
                $nestedData['sub_district'] = $single_data->sub_district_name;
                $nestedData['district']     = $single_data->district_name;
                $nestedData['state']        = $single_data->state_name;
                $nestedData['status']       = $status_show;
                $nestedData['created_at']  = \Carbon\Carbon::parse($single_data->created_at)->toDayDateTimeString();
                $nestedData['updated_at']  = \Carbon\Carbon::parse($single_data->updated_at)->toDayDateTimeString();
                $nestedData['action']       = "<div class=\"btn-group\">$status<a href=\"".route('admin.edit_area_form', ['area_id' => encrypt($single_data->id)])."\" class=\"btn btn-warning btn-sm\" >Edit</a></div>";

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

    public function showEditAreaForm($area_id) 
    {
        try {
            $area_id = decrypt($area_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        /** Area Record **/
        $area_record = DB::table('area')
        	->leftJoin('sub_district', 'area.sub_district_id', '=', 'sub_district.id')
            ->leftJoin('district', 'sub_district.district_id', '=', 'district.id')
            ->leftJoin('states', 'district.state_id', '=', 'states.id')
            ->where('area.id', $area_id)
            ->select('area.*', 'sub_district.sub_district_name', 'district.district_name', 'states.state_name')
            ->first();

        return view('admin.location.area.edit_area', ['area_record' => $area_record]);
    }

    public function updateArea(Request $request, $area_id)
    {
        /** Validation **/
        $request->validate([
            'area_name' => 'required',
        ],
        [
            'area_name.required' => 'Area Name is required',
        ]);

        try {
            $area_id = decrypt($area_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        
        /** Update Area **/
        DB::table('area')
            ->where('id', $area_id)
            ->update([
                'area_name' => ucwords(strtolower($request->input('area_name'))),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        
        return redirect()->back()->with('msg', 'Area has been updated successfully');
    }

    public function changeStatus($area_id, $status)
    {
        try {
            $area_id = decrypt($area_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        
        /** Update Status **/
        DB::table('area')
            ->where('id', $area_id)
            ->update([
                'status' => $status,
            ]);
        
        return redirect()->back();
    }
}
