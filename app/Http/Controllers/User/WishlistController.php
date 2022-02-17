<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Wishlist;
use Carbon\Carbon;

class WishlistController extends Controller
{

    /**
     * Function showing wishlist page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function ViewWishlist(){
        $wishlist = Wishlist::with('product')->where('user_id', Auth::id() )->latest()->get();

		return view('frontend.wishlist.view_wishlist', compact('wishlist'));
	}

    /**
     * Function getting wishlist product
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function GetWishlistProduct(){
		$wishlist = Wishlist::with('product')->where('user_id', Auth::id() )->latest()->get();

		return response()->json($wishlist);
	}

    /**
     * Function removing item from wishlist page
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
	public function RemoveWishlistProduct($id){
		Wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
		return response()->json(['success' => 'Successfully Product Remove']);
	}


}
