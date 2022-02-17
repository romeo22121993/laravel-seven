@php
  $prefix = Request::route()->getPrefix();
  $route  = Route::current()->getName();
  $settings = \App\Models\SiteSetting::find(1);
@endphp
<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar">

        <div class="user-profile">
            <div class="ulogo">
                <a href="{{ route('home') }}">
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="{{ asset($settings->logo) }}" alt="">
                        <h3>Admin Panel</h3>
                    </div>
                </a>
            </div>
        </div>

        <!-- sidebar menu-->
        <ul class="sidebar-menu" data-widget="tree">

            <li>
                <a class="{{ ($route == 'dashboard')? 'active':'' }}" href="{{ route('dashboard') }}">
                    <i data-feather="pie-chart"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="treeview {{ ($prefix == 'admin/brand')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="message-circle"></i>
                    <span>Brands</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('brands.all') }}"><i class="ti-more"></i>All Brands</a></li>
                    <li><a href="{{ route('brands.add') }}"><i class="ti-more"></i>Add Brand</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == 'admin/category')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="mail"></i> <span>Category </span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'category.all')? 'active':'' }}"><a href="{{ route('category.all') }}"><i class="ti-more"></i>All Category</a></li>
                    <li class="{{ ($route == 'subcategory.all')? 'active':'' }}"><a href="{{ route('subcategory.all') }}"><i class="ti-more"></i>All SubCategory</a></li>
                    <li class="{{ ($route == 'subsubcategory.all')? 'active':'' }}"><a href="{{ route('subsubcategory.all') }}"><i class="ti-more"></i>All Sub->SubCategory</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == '/settings') ? 'active':'' }}  ">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>Manage Settings</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'site.settings')? 'active':'' }}"><a href="{{ route('site.settings') }}"><i class="ti-more"></i>Site Setting</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == '/blog')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>Manage Blog</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'blog.category')? 'active':'' }}"><a href="{{ route('blog.category') }}"><i class="ti-more"></i>Blog Category</a></li>
                    <li class="{{ ($route == 'list.post')? 'active':'' }}"><a href="{{ route('list.post') }}"><i class="ti-more"></i>List Blog Post</a></li>
                    <li class="{{ ($route == 'add.post')? 'active':'' }}"><a href="{{ route('add.post') }}"><i class="ti-more"></i>Add Blog Post</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == 'admin/product')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="mail"></i> <span>Products </span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'product.manage')? 'active':'' }}"><a href="{{ route('product.manage') }}"><i class="ti-more"></i>All Products</a></li>
                    <li class="{{ ($route == 'product.add')? 'active':'' }}"><a href="{{ route('product.add') }}"><i class="ti-more"></i>Add Product</a></li>
                    <li class="{{ ($route == 'products.all')? 'active':'' }}"><a href="{{ route('product.manage') }}"><i class="ti-more"></i>Manage Products</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == 'admin/slider')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="mail"></i> <span>Slider</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'slider.manage')? 'active':'' }}"><a href="{{ route('slider.manage') }}"><i class="ti-more"></i>All Sliders</a></li>
                    <li class="{{ ($route == 'slider.add')? 'active':'' }}"><a href="{{ route('slider.manage') }}"><i class="ti-more"></i>Add Slider</a></li>
                    <li class="{{ ($route == 'slider.manage')? 'active':'' }}"><a href="{{ route('slider.manage') }}"><i class="ti-more"></i>Manage Sliders</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == 'admin/coupons')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="mail"></i> <span>Coupons</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'coupon.manage')? 'active':'' }}"><a href="{{ route('coupon.manage') }}"><i class="ti-more"></i>All Coupons</a></li>
                    <li class="{{ ($route == 'coupon.add')? 'active':'' }}"><a href="{{ route('coupon.manage') }}"><i class="ti-more"></i>Add Coupon</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == 'admin/shipping')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="mail"></i> <span>Shipping</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'division.manage')? 'active':'' }}"><a href="{{ route('division.manage') }}"><i class="ti-more"></i>Shipping Division</a></li>
                    <li class="{{ ($route == 'district.manage')? 'active':'' }}"><a href="{{ route('district.manage') }}"><i class="ti-more"></i>Shipping District</a></li>
                    <li class="{{ ($route == 'state.manage')? 'active':'' }}"><a href="{{ route('state.manage') }}"><i class="ti-more"></i>Shipping State</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == 'admin/orders') ? 'active':'' }}  ">
                <a href="#">
                    <i data-feather="mail"></i> <span>Orders</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'pending-orders')? 'active':'' }}"><a href="{{ route('pending-orders') }}"><i class="ti-more"></i>Pending Orders</a></li>
                    <li class="{{ ($route == 'confirmed-orders')? 'active':'' }}"><a href="{{ route('confirmed-orders') }}"><i class="ti-more"></i>Confirmed Orders</a></li>
                    <li class="{{ ($route == 'processing-orders')? 'active':'' }}"><a href="{{ route('processing-orders') }}"><i class="ti-more"></i>Processing Orders</a></li>
                    <li class="{{ ($route == 'picked-orders')? 'active':'' }}"><a href="{{ route('picked-orders') }}"><i class="ti-more"></i> Picked Orders</a></li>
                    <li class="{{ ($route == 'shipped-orders')? 'active':'' }}"><a href="{{ route('shipped-orders') }}"><i class="ti-more"></i> Shipped Orders</a></li>
                    <li class="{{ ($route == 'delivered-orders')? 'active':'' }}"><a href="{{ route('delivered-orders') }}"><i class="ti-more"></i> Delivered Orders</a></li>
                    <li class="{{ ($route == 'canceled-orders')? 'active':'' }}"><a href="{{ route('canceled-orders') }}"><i class="ti-more"></i> Canceled Orders</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == '/reports')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>All Reports </span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'all-reports')? 'active':'' }}"><a href="{{ route('all-reports') }}"><i class="ti-more"></i>All Reports</a></li>
                </ul>
            </li>


            <li class="treeview {{ ($prefix == '/alluser')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>All Users </span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'all-users')? 'active':'' }}"><a href="{{ route('all-users') }}"><i class="ti-more"></i>All Users</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == '/reports')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>All Reports </span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'all-reports')? 'active':'' }}"><a href="{{ route('all-reports') }}"><i class="ti-more"></i>All Reports</a></li>


                </ul>
            </li>

            <li class="treeview {{ ($prefix == '/stock')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>Manage Stock </span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'product.stock')? 'active':'' }}"><a href="{{ route('product.stock') }}"><i class="ti-more"></i>Product Stock</a></li>


                </ul>
            </li>

            <li class="treeview {{ ($prefix == '/review')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>Manage Review</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'pending.review')? 'active':'' }}"><a href="{{ route('pending.review') }}"><i class="ti-more"></i>Pending Review</a></li>
                    <li class="{{ ($route == 'publish.review')? 'active':'' }}"><a href="{{ route('publish.review') }}"><i class="ti-more"></i>Published Review</a></li>
                </ul>
            </li>

            <li class="treeview {{ ($prefix == '/return')?'active':'' }}  ">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>Return Order</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
{{--                    <li class="{{ ($route == 'return.request')? 'active':'' }}"><a href="{{ route('return.request') }}"><i class="ti-more"></i>Return Request</a></li>--}}
{{--                    <li class="{{ ($route == 'all.request')? 'active':'' }}"><a href="{{ route('all.request') }}"><i class="ti-more"></i>All Request</a></li>--}}
                </ul>
            </li>


            <li class="treeview">
                <a href="#">
                    <i data-feather="mail"></i> <span>Mailbox</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="mailbox_inbox.html"><i class="ti-more"></i>Inbox</a></li>
                    <li><a href="mailbox_compose.html"><i class="ti-more"></i>Compose</a></li>
                    <li><a href="mailbox_read_mail.html"><i class="ti-more"></i>Read</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i data-feather="file"></i>
                    <span>Pages</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="profile.html"><i class="ti-more"></i>Profile</a></li>
                    <li><a href="invoice.html"><i class="ti-more"></i>Invoice</a></li>
                    <li><a href="gallery.html"><i class="ti-more"></i>Gallery</a></li>
                    <li><a href="faq.html"><i class="ti-more"></i>FAQs</a></li>
                    <li><a href="timeline.html"><i class="ti-more"></i>Timeline</a></li>
                </ul>
            </li>

            <li class="header nav-small-cap">User Interface</li>

            <li class="treeview">
                <a href="#">
                    <i data-feather="grid"></i>
                    <span>Components</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="components_alerts.html"><i class="ti-more"></i>Alerts</a></li>
                    <li><a href="components_badges.html"><i class="ti-more"></i>Badge</a></li>
                    <li><a href="components_buttons.html"><i class="ti-more"></i>Buttons</a></li>
                    <li><a href="components_sliders.html"><i class="ti-more"></i>Sliders</a></li>
                    <li><a href="components_dropdown.html"><i class="ti-more"></i>Dropdown</a></li>
                    <li><a href="components_modals.html"><i class="ti-more"></i>Modal</a></li>
                    <li><a href="components_nestable.html"><i class="ti-more"></i>Nestable</a></li>
                    <li><a href="components_progress_bars.html"><i class="ti-more"></i>Progress Bars</a></li>
                </ul>
            </li>

            <li class="header nav-small-cap">EXTRA</li>

            <li class="treeview">
                <a href="#">
                    <i data-feather="layers"></i>
                    <span>Multilevel</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#">Level One</a></li>
                    <li class="treeview">
                        <a href="#">Level One
                            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#">Level Two</a></li>
                            <li class="treeview">
                                <a href="#">Level Two
                                    <span class="pull-right-container">
					  <i class="fa fa-angle-right pull-right"></i>
					</span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#">Level Three</a></li>
                                    <li><a href="#">Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#">Level One</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('admin.logout') }}">
                    <i data-feather="lock"></i>
                    <span>Log Out</span>
                </a>
            </li>

        </ul>
    </section>

    <div class="sidebar-footer">
        <!-- item-->
        <a href="javascript:void(0)" class="link" data-toggle="tooltip" title="" data-original-title="Settings" aria-describedby="tooltip92529"><i class="ti-settings"></i></a>
        <!-- item-->
        <a href="mailbox_inbox.html" class="link" data-toggle="tooltip" title="" data-original-title="Email"><i class="ti-email"></i></a>
        <!-- item-->
        <a href="javascript:void(0)" class="link" data-toggle="tooltip" title="" data-original-title="Logout"><i class="ti-lock"></i></a>
    </div>
</aside>
