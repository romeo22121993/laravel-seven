<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use App\Models\Order;

class ReportController extends Controller
{

    /**
     * Function reports view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
     public function ReportView(){
     	return view('backend.report.report_view');
     }

    /**
     * Function filtering data
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function ReportByDate(Request $request){
        $date       = new DateTime($request->date);
        $formatDate = $date->format('d F Y');
        $orders     = Order::where('order_date', $formatDate)->latest()->get();

        return view('backend.report.report_show',compact('orders'));
    }


    /**
     * Function searching by months
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ReportByMonth(Request $request){

        $orders = Order::where('order_month', $request->month )->where('order_year', $request->year_name)->latest()->get();
        return view('backend.report.report_show',compact('orders'));

    }

    /**
     * Function reporting by years
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ReportByYear(Request $request){

        $orders = Order::where('order_year', $request->year)->latest()->get();
        return view('backend.report.report_show',compact('orders'));

    }

}
