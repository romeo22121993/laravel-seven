<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use PDF;
use DB;


class AdminOrderController extends Controller
{

    /**
     * Function of page with pending orders
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function PendingOrders(){
		$orders = Order::where('status','pending')->orderBy('id','DESC')->get();
		return view('backend.orders.pending_orders',compact('orders'));
	}

    /**
     * Function Pending Order Details
     *
     * @param $order_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function AdminOrdersDetails( $order_id ){

		$order = Order::with('division','district','state','user')->where('id', $order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id','DESC')->get();
    	return view('backend.orders.admin_order_details',compact('order','orderItem'));

	}


    /**
     * Function for  Confirmed Orders
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function ConfirmedOrders(){
		$orders = Order::where('status','confirmed')->orderBy('id','DESC')->get();
		return view('backend.orders.confirmed_orders',compact('orders'));
	}

    /**
     * Processing Orders
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ProcessingOrders(){
		$orders = Order::where('status','processing')->orderBy('id','DESC')->get();
		return view('backend.orders.processing_orders',compact('orders'));
	}


    /**
     * Picked Orders
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function PickedOrders(){
		$orders = Order::where('status','picked')->orderBy('id','DESC')->get();
		return view('backend.orders.picked_orders',compact('orders'));
	}


    /**
     * Shipped Orders
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ShippedOrders(){
		$orders = Order::where('status','shipped')->orderBy('id','DESC')->get();
		return view('backend.orders.shipped_orders',compact('orders'));
	}


    /**
     * Delivered Orders
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function DeliveredOrders(){
		$orders = Order::where('status','delivered')->orderBy('id','DESC')->get();
		return view('backend.orders.delivered_orders',compact('orders'));
	}


    /**
     * Canceled Orders
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function CanceledOrders(){
		$orders = Order::where('status', 'canceled')->orderBy('id','DESC')->get();
		return view('backend.orders.canceled_orders',compact('orders'));
	}


    /**
     * Function changing status
     *
     * @param $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
	public function PendingToConfirm($order_id){

        Order::findOrFail($order_id)->update(['status' => 'confirmed']);

        $notification = array(
            'message'    => 'Order Confirm Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pending-orders')->with($notification);

	}


    /**
     * Function changing status
     *
     * @param $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
	public function ConfirmToProcessing($order_id){

        Order::findOrFail($order_id)->update(['status' => 'processing']);

        $notification = array(
            'message'    => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('confirmed-orders')->with($notification);

	}

    /**
     * Function changing status
     *
     * @param $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ProcessingToPicked($order_id){

        Order::findOrFail($order_id)->update(['status' => 'picked']);

        $notification = array(
            'message' => 'Order Picked Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('processing-orders')->with($notification);

	}

    /**
     * Function changing status
     *
     * @param $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
	 public function PickedToShipped($order_id){

        Order::findOrFail($order_id)->update(['status' => 'shipped']);

        $notification = array(
            'message'    => 'Order Shipped Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('picked-orders')->with($notification);

	}

    /**
     * Function changing status
     *
     * @param $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
	public function ShippedToDelivered($order_id){

        $order_item = OrderItem::where('order_id', $order_id)->get();
        foreach ($order_item as $item) {
            Product::where('id', $item->product_id)
            ->update(['product_qty' => DB::raw('product_qty-'.$item->qty)]);
        }

        Order::findOrFail($order_id)->update(['status' => 'delivered']);

        $notification = array(
            'message'    => 'Order Delivered Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('shipped-orders')->with($notification);


	}


    /**
     * Function download pdf from order
     *
     * @param $order_id
     * @return mixed
     */
	public function AdminInvoiceDownload($order_id){

		$order = Order::with('division','district','state','user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();

		$pdf = PDF::loadView('backend.orders.order_invoice', compact('order','orderItem'))->setPaper('a4')->setOptions([
				'tempDir' => public_path(),
				'chroot'  => public_path(),
		]);
		return $pdf->download('invoice.pdf');

	}



}
