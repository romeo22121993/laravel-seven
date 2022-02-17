<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDivision;
use Carbon\Carbon;
use App\Models\ShipDistrict;
use App\Models\ShipState;

class ShippingAreaController extends Controller
{

    /**
     * Function showing division page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function DivisionView(){
		$divisions = ShipDivision::orderBy('id','DESC')->get();
		return view('backend.ship.division.view_division',compact('divisions'));

	}

    /**
     * Function creating division
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function DivisionStore(Request $request){

    	$request->validate([
    		'division_name' => 'required',
    	]);

	    ShipDivision::insert([
            'division_name' => $request->division_name,
            'created_at'    => Carbon::now(),
    	]);

	    $notification = array(
			'message' => 'Division Inserted Successfully',
			'alert-type' => 'success'
		);

		return redirect()->back()->with($notification);

    }


    /**
     * Function view edit divison page
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function DivisionEdit($id){
        $divisions = ShipDivision::findOrFail($id);
	    return view('backend.ship.division.edit_division',compact('divisions'));
    }

    /**
     * Function updating division page
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function DivisionUpdate(Request $request,$id){

    	ShipDivision::findOrFail($id)->update([

		'division_name' => $request->division_name,
		'created_at' => Carbon::now(),

    	]);

	    $notification = array(
			'message'    => 'Division Updated Successfully',
			'alert-type' => 'info'
		);

		return redirect()->route('division.manage')->with($notification);


    }

    /**
     * Function deleting division by id
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function DivisionDelete($id){

    	ShipDivision::findOrFail($id)->delete();

    	$notification = array(
			'message'    => 'Division Deleted Successfully',
			'alert-type' => 'info'
		);

		return redirect()->back()->with($notification);

    }


    //// Start Ship District

    /**
     * District manage page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function DistrictView(){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::with('division')->orderBy('id','DESC')->get();
		return view('backend.ship.district.view_district',compact('division','district'));
    }

    /**
     * Function creating new disctrict
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function DistrictStore(Request $request){

    	$request->validate([
    		'division_id' => 'required',
    		'district_name' => 'required',
    	]);

	    ShipDistrict::insert([
            'division_id'   => $request->division_id,
            'district_name' => $request->district_name,
            'created_at'    => Carbon::now(),
    	]);

	    $notification = array(
			'message'    => 'District Inserted Successfully',
			'alert-type' => 'success'
		);

		return redirect()->back()->with($notification);

    }

    /**
     * Function edit of district page
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function DistrictEdit($id){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::findOrFail($id);
        return view('backend.ship.district.edit_district',compact('district','division'));
    }

    /**
     * District update function
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function DistrictUpdate(Request $request,$id){

    	ShipDistrict::findOrFail($id)->update([
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,
            'created_at' => Carbon::now(),
    	]);

	    $notification = array(
			'message' => 'District Updated Successfully',
			'alert-type' => 'info'
		);

		return redirect()->route('district.manage')->with($notification);

    }

    /**
     * District deleting function
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function DistrictDelete($id){

    	ShipDistrict::findOrFail($id)->delete();

    	$notification = array(
			'message' => 'District Deleted Successfully',
			'alert-type' => 'info'
		);

		return redirect()->back()->with($notification);

    }
   //// End Ship District


     ////////////////// Ship State //////////

    /**
     * Function shipping state manage page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function StateView(){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::orderBy('district_name','ASC')->get();
        $state = ShipState::with('division','district')->orderBy('id','DESC')->get();
		return view('backend.ship.state.view_state',compact('division','district','state'));
    }

    /**
     * Function creating state
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function StateStore(Request $request){

    	$request->validate([
    		'division_id' => 'required',
    		'district_id' => 'required',
    		'state_name'  => 'required',
    	]);

	    ShipState::insert([
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_name'  => $request->state_name,
            'created_at'  => Carbon::now(),
    	]);

	    $notification = array(
			'message'    => 'State Inserted Successfully',
			'alert-type' => 'success'
		);

		return redirect()->back()->with($notification);

    }

    /**
     * Function edit view page
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function StateEdit($id){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::orderBy('district_name','ASC')->get();
        $state    = ShipState::findOrFail($id);
		return view('backend.ship.state.edit_state',compact('division','district','state'));
    }

    /**
     * Function updating state
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function StateUpdate(Request $request,$id){

    	ShipState::findOrFail($id)->update([
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_name'  => $request->state_name,
            'created_at'  => Carbon::now(),
    	]);

	    $notification = array(
			'message'    => 'State Updated Successfully',
			'alert-type' => 'info'
		);

		return redirect()->route('state.manage')->with($notification);

    }


    /**
     * Function deleting state
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function StateDelete($id){

    	ShipState::findOrFail($id)->delete();

    	$notification = array(
			'message'    => 'State Deleted Successfully',
			'alert-type' => 'info'
		);

		return redirect()->back()->with($notification);

    }

    //////////////// End Ship State ////////////

}
