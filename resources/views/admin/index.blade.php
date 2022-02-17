@extends('admin.admin_master')

@php
    $date  = date('d-m-y');
     $today = \App\Models\Order::where('order_date',$date)->sum('amount');

     $month = date('F');
     $month = \App\Models\Order::where('order_month',$month)->sum('amount');

     $year  = date('Y');
     $year  = \App\Models\Order::where('order_year',$year)->sum('amount');

     $pending = \App\Models\Order::where('status','pending')->get();

@endphp

@section('admin')

    <div class="container-full">

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xl-3 col-6">
                    <div class="box overflow-hidden pull-up">
                        <div class="box-body">
                            <div class="icon bg-primary-light rounded w-60 h-60">
                                <i class="text-primary mr-0 font-size-24 mdi mdi-account-multiple"></i>
                            </div>
                            <div>
                                <p class="text-mute mt-20 mb-0 font-size-16">Today's Sale</p>
                                <h3 class="text-white mb-0 font-weight-500">${{ $today  }} <small class="text-success"><i class="fa fa-caret-up"></i> usd</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-6">
                    <div class="box overflow-hidden pull-up">
                        <div class="box-body">
                            <div class="icon bg-warning-light rounded w-60 h-60">
                                <i class="text-warning mr-0 font-size-24 mdi mdi-car"></i>
                            </div>
                            <div>
                                <p class="text-mute mt-20 mb-0 font-size-16">Monthly Sale</p>
                                <h3 class="text-white mb-0 font-weight-500">${{ $month }} <small class="text-success"><i class="fa fa-caret-up"></i> usd</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-6">
                    <div class="box overflow-hidden pull-up">
                        <div class="box-body">
                            <div class="icon bg-info-light rounded w-60 h-60">
                                <i class="text-info mr-0 font-size-24 mdi mdi-sale"></i>
                            </div>
                            <div>
                                <p class="text-mute mt-20 mb-0 font-size-16">Yearly Sale</p>
                                <h3 class="text-white mb-0 font-weight-500">${{ $year }} <small class="text-danger"><i class="fa fa-caret-down"></i> usd</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-6">
                    <div class="box overflow-hidden pull-up">
                        <div class="box-body">
                            <div class="icon bg-danger-light rounded w-60 h-60">
                                <i class="text-danger mr-0 font-size-24 mdi mdi-phone-incoming"></i>
                            </div>
                            <div>
                                <p class="text-mute mt-20 mb-0 font-size-16">Pending Orders</p>
                                <h3 class="text-white mb-0 font-weight-500">{{ count($pending) }} <small class="text-danger"><i class="fa fa-caret-up"></i> Orders</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">
                                Earning Summary
                            </h4>
                        </div>
                        <div class="box-body py-0">
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="box no-shadow mb-0">
                                        <div class="box-body px-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div>
                                                    <div id="chart41"></div>
                                                </div>
                                                <div>
                                                    <h5>Top Order</h5>
                                                    <h4 class="text-white my-0 font-weight-500">$39k</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="box no-shadow mb-0">
                                        <div class="box-body px-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div>
                                                    <div id="chart42"></div>
                                                </div>
                                                <div>
                                                    <h5>Average Order</h5>
                                                    <h4 class="text-white my-0 font-weight-500">$24k</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="charts_widget_43_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-12">
                    <div class="box bg-info bg-img" style="background-image: url(../images/gallery/bg-1.png)">
                        <div class="box-body text-center">
                            <img src="../images/trophy.png" class="mt-50" alt="" />
                            <div class="max-w-500 mx-auto">
                                <h2 class="text-white mb-20 font-weight-500">Best Employee Johen,</h2>
                                <p class="text-white-50 mb-10 font-size-20">You've got 50.5% more sales today. You've reached 8th milestone, checkout author section</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="box overflow-hidden">
                                <div class="box-body pb-0">
                                    <div>
                                        <h2 class="text-white mb-0 font-weight-500">18.8k</h2>
                                        <p class="text-mute mb-0 font-size-20">Total users</p>
                                    </div>
                                </div>
                                <div class="box-body p-0">
                                    <div id="revenue1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="box overflow-hidden">
                                <div class="box-body pb-0">
                                    <div>
                                        <h2 class="text-white mb-0 font-weight-500">35.8k</h2>
                                        <p class="text-mute mb-0 font-size-20">Average reach per post</p>
                                    </div>
                                </div>
                                <div class="box-body p-0">
                                    <div id="revenue2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxxl-7 col-xl-6 col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Recent Stats</h4>
                        </div>
                        <div class="box-body">
                            <div id="recent_trend"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title align-items-start flex-column">
                                New Orders
                                <small class="subtitle">recently orders</small>
                            </h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-border">
                                    <thead>
                                        <tr class="text-uppercase bg-lightest">
                                            <th style="min-width: 250px"><span class="text-white">Date</span></th>
                                            <th style="min-width: 100px"><span class="text-fade">Invoice</span></th>
                                            <th style="min-width: 100px"><span class="text-fade">Amount</span></th>
                                            <th style="min-width: 150px"><span class="text-fade">Payment</span></th>
                                            <th style="min-width: 130px"><span class="text-fade">Status</span></th>
                                            <th style="min-width: 120px"><span class="text-fade">Process</span> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $item)
                                            <tr>
                                                <td class="pl-0 py-8">
                                                     <span class="text-white font-weight-600 d-block font-size-16">
                                                        {{ Carbon\Carbon::parse($item->order_date)->diffForHumans()  }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-white font-weight-600 d-block font-size-16">
                                                        {{ $item->invoice_no }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-fade font-weight-600 d-block font-size-16">
                                                        $ {{ $item->amount }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-white font-weight-600 d-block font-size-16">
                                                        {{ $item->payment_method }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary-light badge-lg">{{ $item->status }}</span>
                                                </td>

                                                <td class="text-right">
                                                    <a href="#" class="waves-effect waves-light btn btn-info btn-circle mx-5"><span class="mdi mdi-bookmark-plus"></span></a>
                                                    <a href="#" class="waves-effect waves-light btn btn-info btn-circle mx-5"><span class="mdi mdi-arrow-right"></span></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

@endsection
