<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Auth;
use App\Models\Wishlist;
use Carbon\Carbon;

use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

use App\Models\ShipDivision;

class CartController extends Controller
{

    /**
     * Function adding to cart with product id
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddToCart(Request $request, $id){

        if (Session::has('coupon')) {
            Session::forget('coupon');
        }

    	$product = Product::findOrFail($id);

        $price = ($product->discount_price == NULL) ? $product->selling_price : $product->discount_price;

        //ToDo : check color and size via adding to cart

        Cart::add([
            'id'     => $id,
            'name'   => $request->product_name,
            'qty'    => $request->quantity,
            'price'  => $price,
            'weight' => 1,
            'options' => [
                'image' => $product->product_thambnail,
                'color' => $request->color,
                'size'  => $request->size,
            ],
        ]);

        return response()->json(['success' => 'Successfully Added on Your Cart']);

    }


    /**
     * Function getting cart details data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddMiniCart(){

    	$carts     = Cart::content();
    	$cartQty   = Cart::count();
    	$cartTotal = Cart::total();

        $cartTotal = str_replace( ',', '', $cartTotal);
        $cartQty   = str_replace( ',', '', $cartQty);

    	return response()->json(array(
    		'carts'     => $carts,
    		'cartQty'   => $cartQty,
    		'cartTotal' => $cartTotal,
    	));
    }

    /**
     * Function removing mini cart
     *
     * @param $rowId
     * @return \Illuminate\Http\JsonResponse
     */
    public function RemoveMiniCart($rowId){
    	Cart::remove($rowId);
    	return response()->json(['success' => 'Product Remove from Cart']);
    }

    /**
     * Function adding product to wishlist
     *
     * @param Request $request
     * @param $product_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddToWishlist(Request $request, $product_id){

        if ( Auth::check() ) {

            $exists = Wishlist::where('user_id', Auth::id())->where('product_id',$product_id)->first();

            if ( !$exists ) {
                Wishlist::insert([
                    'user_id'    => Auth::id(),
                    'product_id' => $product_id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json(['success' => 'Successfully Added On Your Wishlist']);

            } else {
                return response()->json(['error' => 'This Product is Already on Your Wishlist']);
            }

        } else {
            return response()->json(['error' => 'At First Login Your Account']);
        }

    }

    /**
     * Function applying coupon
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CouponApply(Request $request){

        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();

        if ( $coupon ) {

            $cartTotal = Cart::total();
            $cartTotal = str_replace( ',', '', $cartTotal);

            $discountAmount = $cartTotal * $coupon->coupon_discount/100;
            $discountAmount = round($discountAmount, 3);
            $totalAmount    = $cartTotal - $discountAmount;
            $totalAmount    = round($totalAmount, 3);

            Session::put('coupon',[
                'coupon_name'     => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => $discountAmount,
                'total_amount'    => $totalAmount
            ]);

            return response()->json(array(
                'validity' => true,
                'success'  => 'Coupon Applied Successfully'
            ));

        } else {
            return response()->json(['error' => 'Invalid Coupon']);
        }
    }

    /**
     * Function calculating of coupon
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function CouponCalculation(){

        $cartTotal = Cart::total();
        $cartTotal = str_replace( ',', '', $cartTotal);

        if (Session::has('coupon')) {
            return response()->json(array(
                'subtotal'        => $cartTotal,
                'coupon_name'     => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount'    => session()->get('coupon')['total_amount'],
            ));
        }else{
            return response()->json(array(
                'total' => $cartTotal,
            ));
        }
    }

    // Remove Coupon
    /**
     * Function removing coupon
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function CouponRemove()
    {
        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);
    }


    /**
     * Function showing cart page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function MyCart(){

        $carts     = Cart::content();
        $cartQty   = Cart::count();
        $cartTotal = Cart::total();

        $cartTotal = str_replace( ',', '', $cartTotal);
        $cartQty   = str_replace( ',', '', $cartQty);

        return view('frontend.wishlist.view_mycart', compact( 'carts', 'cartTotal', 'cartQty'));
    }


    /**
     * Function getting cart product by ajax
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetCartProduct(){
        $carts     = Cart::content();
        $cartQty   = Cart::count();
        $cartTotal = Cart::total();

        $cartTotal = str_replace( ',', '', $cartTotal);
        $cartQty   = str_replace( ',', '', $cartQty);

        return response()->json(array(
            'carts'     => $carts,
            'cartQty'   => $cartQty,
            'cartTotal' => round($cartTotal),
        ));

    }

    /**
     * Function removing cart product
     *
     * @param $rowId
     * @return \Illuminate\Http\JsonResponse
     */
    public function RemoveCartProduct($rowId){
        Cart::remove($rowId);

//        if (Session::has('coupon')) {
//           Session::forget('coupon');
//        }

        return response()->json(['success' => 'Successfully Remove From Cart']);
    }

    /**
     * Function cart incrementing on backend side
     *
     * @param $rowId
     * @return \Illuminate\Http\JsonResponse
     */
    public function CartIncrement( $rowId ){
        $row = Cart::get( $rowId );
        Cart::update( $rowId, $row->qty + 1);

        if ( Session::has('coupon') ) {

            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon      = Coupon::where('coupon_name',$coupon_name)->first();

            $cartTotal = Cart::total();
            $cartTotal = str_replace( ',', '', $cartTotal);

            $discountAmount = $cartTotal * $coupon->coupon_discount/100;
            $discountAmount = round($discountAmount, 2);
            $totalAmount    = $cartTotal - $discountAmount;
            $totalAmount    = round($totalAmount, 2);

            Session::put('coupon',[
                'coupon_name'     => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => $discountAmount,
                'total_amount'    => $totalAmount
            ]);
        }

        return response()->json('increment');

    }

    /**
     * Function Cart Decrementing
     *
     * @param $rowId
     * @return \Illuminate\Http\JsonResponse
     */
    public function CartDecrement($rowId){

        $row = Cart::get( $rowId );
        Cart::update( $rowId, $row->qty - 1 );

        if ( Session::has('coupon') ) {

            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon      = Coupon::where('coupon_name',$coupon_name)->first();

            $cartTotal = Cart::total();
            $cartTotal = str_replace( ',', '', $cartTotal);

            $discountAmount = $cartTotal * $coupon->coupon_discount/100;
            $discountAmount = round($discountAmount, 2);
            $totalAmount    = $cartTotal - $discountAmount;
            $totalAmount    = round($totalAmount, 2);

            Session::put('coupon',[
                'coupon_name'     => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => $discountAmount,
                'total_amount'    => $totalAmount
            ]);
        }

        return response()->json('Decrement');

    }

}
