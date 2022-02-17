<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDivision;
use App\Models\ShipDistrict;
use App\Models\ShipState;
use Auth;

use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{

    /**
     * Function checkout page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function Checkout(){

        if ( Auth::check() ) {
            $cartTotal = Cart::total();
            $cartTotal = str_replace( ',', '', $cartTotal);

            if ( $cartTotal > 0 ) {

                $user    = Auth::user();
                $carts   = Cart::content();
                $cartQty = Cart::count();

                $divisions = ShipDivision::orderBy('division_name','ASC')->get();

                return view('frontend.checkout.checkout_view',compact('carts','user', 'cartQty', 'cartTotal', 'divisions'));

            } else {

                $notification = array(
                    'message' => 'Shopping At list One Product',
                    'alert-type' => 'error'
                );
                return redirect()->to('/')->with($notification);
            }
        } else {
            $notification = array(
                'message' => 'You Need to Login First',
                'alert-type' => 'error'
            );
            return redirect()->route('login')->with($notification);
        }
    }

    /**
     * Function get district by ajax
     *
     * @param $division_id
     * @return false|string
     */
    public function DistrictGetAjax($division_id){
    	$ship = ShipDistrict::where('division_id',$division_id)->orderBy('district_name','ASC')->get();
    	return json_encode($ship);
    }

    /**
     * Function get states by district
     *
     * @param $district_id
     * @return false|string
     */
     public function StateGetAjax($district_id){
    	$ship = ShipState::where('district_id', $district_id)->orderBy('state_name','ASC')->get();
    	return json_encode($ship);

    }

    /**
     * Function checkout store
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function CheckoutStore(Request $request){
    	// dd($request->all());
    	$data = array();
    	$data['shipping_name']  = $request->shipping_name;
    	$data['shipping_email'] = $request->shipping_email;
    	$data['shipping_phone'] = $request->shipping_phone;
    	$data['post_code']      = $request->post_code;
    	$data['division_id']    = $request->division_id;
    	$data['district_id']    = $request->district_id;
    	$data['state_id']       = $request->state_id;
    	$data['notes']          = $request->notes;
    	$cartTotal  = Cart::total();
        $cartTotal  = str_replace( ',', '', $cartTotal);

    	if ( $request->payment_method == 'stripe' ) {
    		return view('frontend.payment.stripe',compact('data','cartTotal'));
    	} elseif ( $request->payment_method == 'card' ) {
    		return 'card';
    	} else {
            return view('frontend.payment.cash',compact('data','cartTotal'));
    	}

    }


}
