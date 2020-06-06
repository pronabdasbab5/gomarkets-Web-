<?php

namespace App\Http\Controllers\Admin\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class StateController extends Controller
{
    public function showNewStateForm() 
    {
        return view('admin.location.state.new_state');
    }

    public function addState(Request $request)
    {
        /** Checking State Name **/
        $request->validate([
            'state_name' => 'required'
        ],
        [
            'state_name.required' => 'State Name is required'
        ]);
        /** End of State Name **/

        $count_state = DB::table('states')
            ->where('state_name', ucwords(strtolower($request->input('state_name'))))
            ->count();

        if($count_state > 0)
            return redirect()->back()->with('error', 'State already added');
        
        /** Insert State Name **/
        DB::table('states')
            ->insert([
                'state_name' => ucwords(strtolower($request->input('state_name'))),
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        /** End of Insertion **/
        
        return redirect()->back()->with('msg', 'State has been added successfully');
    }

    public function stateList() 
    {
        /** Fetching All State **/
        $state_list = DB::table('states')->get();

        return view('admin.location.state.state_list', ['state_list' => $state_list]);
    }

    public function showEditStateForm($state_id) 
    {
        try {
            $state_id = decrypt($state_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        /** State Record **/
        $state_record = DB::table('states')
            ->where('id', $state_id)
            ->first();

        return view('admin.location.state.edit_state', ['state_record' => $state_record]);
    }

    public function updateState(Request $request, $state_id)
    {
        /** Checking State Name **/
        $request->validate([
            'state_name' => 'required'
        ],
        [
            'state_name.required' => 'State Name is required'
        ]);

        try {
            $state_id = decrypt($state_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $count_state = DB::table('states')
            ->where('state_name', ucwords(strtolower($request->input('state_name'))))
            ->count();

        if($count_state > 0)
            return redirect()->back()->with('msg', 'State has been updated successfully');
        
        /** Update State Name **/
        DB::table('states')
            ->where('id', $state_id)
            ->update([
                'state_name' => ucwords(strtolower($request->input('state_name'))),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        
        return redirect()->back()->with('msg', 'State has been updated successfully');
    }

    public function changeStatus($state_id, $status)
    {
        try {
            $state_id = decrypt($state_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        try {
            $status = decrypt($status);
        }catch(DecryptException $e) {
            return redirect()->back();
        }
        
        /** Update Status **/
        DB::table('states')
            ->where('id', $state_id)
            ->update([
                'status' => $status,
            ]);
        
        return redirect()->back();
    }
}
