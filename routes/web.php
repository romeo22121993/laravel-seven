<?php

use App\Models\User;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\AdminProfileController;
use App\Http\Controllers\Backend\UserProfileController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\ShippingAreaController;
use App\Http\Controllers\Backend\AdminOrderController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\SiteSettingController;
//use App\Http\Controllers\Backend\ReturnController;
//use App\Http\Controllers\Backend\AdminUserController;

use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Frontend\FrontEndController;
use App\Http\Controllers\Frontend\LanguageController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\GameController;
use App\Http\Controllers\Frontend\HomeBlogController;

use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\CartPageController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\StripeController;
use App\Http\Controllers\User\CashController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\UserOrderController;

use App\Http\Controllers\Frontend\ShopController;
use Inertia\Inertia;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['web']], function () {

    Route::get('/',          [IndexController::class, 'Index'])->name('home');
    Route::get('/dashboard', [AdminController::class, 'UserProfile'])->name('dashboard');

    Route::get('/logout',    [IndexController::class, 'UserLogout'])->name('logout');
    Route::post('/login',    [IndexController::class, 'CustomLogin']);
    Route::post('/testing-email',   [IndexController::class, 'TestEmail'])->name('send.testing.email');

    //// Frontend All Routes /////
    /// Multi Language All Routes ////
    Route::get('/language/hindi',   [LanguageController::class, 'Hindi'])->name('hindi.language');
    Route::get('/language/english', [LanguageController::class, 'English'])->name('english.language');

    // My Cart Page All Routes
    Route::get('/mycart',           [CartController::class, 'MyCart'])->name('mycart');

    // Checkout Routes
    Route::get('/checkout',         [CheckoutController::class, 'Checkout'])->name('checkout');
    Route::post('/checkout/store',  [CheckoutController::class, 'CheckoutStore'])->name('checkout.store');

    //  Frontend Blog Show Routes
    Route::get('/blog',              [HomeBlogController::class, 'BlogList'])->name('home.blog');
    Route::get('/post/details/{id}', [HomeBlogController::class, 'DetailsBlogPost'])->name('post.details');
    Route::get('/blog/category/post/{category_id}', [HomeBlogController::class, 'HomeBlogCatPost']);

    // Ajax requests
    Route::group(['prefix'=> 'ajax'], function(){
        /**
         * Categories ajax requests
         */
        Route::prefix('category')->group(function(){
            Route::get('/subcategory/{category_id}',        [CategoryController::class, 'GetSubCategory']);
            Route::get('/sub-subcategory/{subcategory_id}', [CategoryController::class, 'GetSubSubCategory']);
        });

        // Product View Modal with Ajax
        Route::get('/product/view/modal/{id}', [FrontEndController::class, 'ProductViewAjax']);

        // Add to Cart Store Data
        Route::post('/cart/data/store/{id}', [CartController::class, 'AddToCart']);

        // Get Data from mini cart
        Route::get('/product/mini/cart/',    [CartController::class, 'AddMiniCart']);

        // Remove mini cart
        Route::get('/minicart/product-remove/{rowId}', [CartController::class, 'RemoveMiniCart']);

        // Add to Wishlist - user actions
        Route::post('/add-to-wishlist/{product_id}', [CartController::class, 'AddToWishlist']);

        Route::get('/get-wishlist-product', [WishlistController::class, 'GetWishlistProduct']);

        Route::get('/wishlist-remove/{id}', [WishlistController::class, 'RemoveWishlistProduct']);

        Route::get('/get-cart-product',    [CartController::class, 'GetCartProduct']);

        Route::get('/cart-remove/{rowId}', [CartController::class, 'RemoveCartProduct']);

        Route::get('/cart-increment/{rowId}', [CartController::class, 'CartIncrement']);

        Route::get('/cart-decrement/{rowId}', [CartController::class, 'CartDecrement']);

        // Frontend Coupon Option
        Route::post('/coupon-apply',      [CartController::class, 'CouponApply']);
        Route::get('/coupon-calculation', [CartController::class, 'CouponCalculation']);
        Route::get('/coupon-remove',      [CartController::class, 'CouponRemove']);

        // Checkout Functions
        Route::get('/district-get/{division_id}', [CheckoutController::class, 'DistrictGetAjax']);
        Route::get('/state-get/{district_id}',    [CheckoutController::class, 'StateGetAjax']);

        // Advance Search Routes
        Route::post('search-product', [FrontEndController::class, 'SearchProduct']);

    });

    /// Product Search Route
    Route::post('/search',        [FrontEndController::class, 'ProductSearch'])->name('product.search');

    /**
     * Stripe
     */
    Route::post('/stripe/order', [StripeController::class, 'StripeOrder'])->name('stripe.order');

    Route::post('/cash/order', [CashController::class, 'CashOrder'])->name('cash.order');


    // Frontend Product Details Page url
    Route::get('/product/details/{id}', [FrontEndController::class, 'ProductDetails'])->name('productdetail');

    // Frontend Product Tags Page
    Route::get('/product/tag/{tag}', [FrontEndController::class, 'TagWiseProduct']);

    // Frontend SubCategory wise Data
    Route::get('/category/{subcat_id}/{slug}', [FrontEndController::class, 'SubCatWiseProduct']);

    // Frontend Sub-SubCategory wise Data
    Route::get('/subcategory/{subsubcat_id}/{slug}', [FrontEndController::class, 'SubSubCatWiseProduct']);

    /// Frontend Product Review Routes
    Route::post('/review/store', [ReviewController::class, 'ReviewStore'])->name('review.store');

    // Shop Page Route
    Route::get('/shop',         [ShopController::class, 'ShopPage'])->name('shop');
    Route::post('/shop/filter', [ShopController::class, 'ShopFilter'])->name('shop.filter');
});

Route::group(['middleware' => ['auth:sanctum', 'web'] ], function(){
    Route::get('/chat',      [ChatController::class, 'ChatVue'])->name('chat');
    Route::get('/chat-vue',  [ChatController::class, 'ChatVue1'])->name('chat1');
    Route::get('/tic-toc-game',    [GameController::class, 'GamePage'])->name('game-tik-tok');
    Route::post('/new-game',       [GameController::class, 'newGame']);
    Route::get('/board/{id}',      [GameController::class, 'board']);
    Route::post('/play/{id}',      [GameController::class, 'play']);
    Route::post('/game-over/{id}', [GameController::class, 'gameOver']);


    Route::middleware( 'auth:sanctum')->get('/chat/rooms', [ChatController::class, 'rooms']);
    Route::middleware( 'auth:sanctum')->get('/chat/rooms/{roomId}/messages', [ChatController::class, 'messages']);
    Route::middleware( 'auth:sanctum')->post('/chat/rooms/{roomId}/messages', [ChatController::class, 'newMessage']);
    Route::middleware( 'auth:sanctum')->post('/chat/rooms/create', [ChatController::class, 'newRoom']);

});


/**
 * Admin Dashboard
 */
Route::group(['prefix'=> 'admin', 'middleware' => ['auth', 'user'] ], function(){

	Route::get('/login',  [IndexController::class, 'loginForm'])->name('admin.login');
	Route::post('/login', [IndexController::class, 'CustomLogin']);

    Route::get('/dashboard', [AdminProfileController::class, 'AdminProfile'])->name('admin.dashboard')
    ->middleware(['auth:sanctum', 'verified']);

   // Admin All Routes

    Route::get('/logout',           [AdminController::class, 'destroy'])->name('admin.logout');
    Route::get('/profile',          [AdminProfileController::class, 'AdminProfile'])->name('admin.profile');
    Route::get('/profile/edit',     [AdminProfileController::class, 'AdminProfileEdit'])->name('admin.profile.edit');
    Route::post('/profile/store',   [AdminProfileController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/change/password',  [AdminProfileController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/update/change/password', [AdminProfileController::class, 'AdminUpdateChangePassword'])->name('admin.update.password');

    // Admin Brand All Routes
    Route::prefix('brand')->group(function(){

        Route::get('/view',        [BrandController::class, 'BrandView'])->name('brands.all');
        Route::post('/store',      [BrandController::class, 'BrandStore'])->name('brands.add');
        Route::get('/edit/{id}',   [BrandController::class, 'BrandEdit'])->name('brands.edit');
        Route::post('/update',     [BrandController::class, 'BrandUpdate'])->name('brands.update');
        Route::get('/delete/{id}', [BrandController::class, 'BrandDelete'])->name('brands.delete');

    });

    // Admin Category all Routes
    Route::prefix('category')->group(function(){

        Route::get('/view',         [CategoryController::class, 'CategoryView'])->name('category.all');
        Route::post('/store',       [CategoryController::class, 'CategoryStore'])->name('category.store');
        Route::get('/edit/{id}',    [CategoryController::class, 'CategoryEdit'])->name('category.edit');
        Route::post('/update/{id}', [CategoryController::class, 'CategoryUpdate'])->name('category.update');
        Route::get('/delete/{id}',  [CategoryController::class, 'CategoryDelete'])->name('category.delete');

        // Admin Sub Category All Routes
        Route::get('/sub/view',        [CategoryController::class, 'SubCategoryView'])->name('subcategory.all');
        Route::post('/sub/store',      [CategoryController::class, 'SubCategoryStore'])->name('subcategory.store');
        Route::get('/sub/edit/{id}',   [CategoryController::class, 'SubCategoryEdit'])->name('subcategory.edit');
        Route::post('/update',         [CategoryController::class, 'SubCategoryUpdate'])->name('subcategory.update');
        Route::get('/sub/delete/{id}', [CategoryController::class, 'SubCategoryDelete'])->name('subcategory.delete');

        // Admin Sub->Sub Category All Routes
        Route::get('/sub/sub/view',        [CategoryController::class, 'SubSubCategoryView'])->name('subsubcategory.all');
        Route::post('/sub/sub/store',      [CategoryController::class, 'SubSubCategoryStore'])->name('subsubcategory.store');
        Route::get('/sub/sub/edit/{id}',   [CategoryController::class, 'SubSubCategoryEdit'])->name('subsubcategory.edit');
        Route::post('/sub/update',         [CategoryController::class, 'SubSubCategoryUpdate'])->name('subsubcategory.update');
        Route::get('/sub/sub/delete/{id}', [CategoryController::class, 'SubSubCategoryDelete'])->name('subsubcategory.delete');


    });

    // Admin Products All Routes
    Route::prefix('product')->group(function(){

        Route::get('/manage',    [ProductController::class, 'ManageProduct'])->name('product.manage');
        Route::get('/add',       [ProductController::class, 'AddProduct'])->name('product.add');
        Route::post('/store',    [ProductController::class, 'StoreProduct'])->name('product.store');
        Route::get('/edit/{id}', [ProductController::class, 'EditProduct'])->name('product.edit');
        Route::post('/data/update',      [ProductController::class, 'ProductDataUpdate'])->name('product.update');
        Route::post('/image/update',     [ProductController::class, 'MultiImageUpdate'])->name('product.update-image');
        Route::post('/thumbnail/update', [ProductController::class, 'ThambnailImageUpdate'])->name('product.update.thambnail');
        Route::get('/multiimg/delete/{id}', [ProductController::class, 'MultiImageDelete'])->name('product.multiimg.delete');
        Route::get('/inactive/{id}', [ProductController::class, 'ProductInactive'])->name('product.inactive');
        Route::get('/active/{id}',   [ProductController::class, 'ProductActive'])->name('product.active');
        Route::get('/delete/{id}',   [ProductController::class, 'ProductDelete'])->name('product.delete');

    });

    // Admin Slider All Routes
    Route::prefix('slider')->group(function(){

        Route::get('/view',          [SliderController::class, 'SliderView'])->name('slider.manage');
        Route::post('/store',        [SliderController::class, 'SliderStore'])->name('slider.store');
        Route::get('/edit/{id}',     [SliderController::class, 'SliderEdit'])->name('slider.edit');
        Route::post('/update',       [SliderController::class, 'SliderUpdate'])->name('slider.update');
        Route::get('/delete/{id}',   [SliderController::class, 'SliderDelete'])->name('slider.delete');
        Route::get('/inactive/{id}', [SliderController::class, 'SliderInactive'])->name('slider.inactive');
        Route::get('/active/{id}',   [SliderController::class, 'SliderActive'])->name('slider.active');

    });

    // Admin Coupons All Routes
    Route::prefix('coupons')->group(function(){
        Route::get('/view',          [CouponController::class, 'CouponView'])->name('coupon.manage');
        Route::post('/store',        [CouponController::class, 'CouponStore'])->name('coupon.store');
        Route::get('/edit/{id}',     [CouponController::class, 'CouponEdit'])->name('coupon.edit');
        Route::post('/update/{id}',  [CouponController::class, 'CouponUpdate'])->name('coupon.update');
        Route::get('/delete/{id}',   [CouponController::class, 'CouponDelete'])->name('coupon.delete');
    });

    // Admin Shipping All Routes
    Route::prefix('shipping')->group(function(){

        // Ship Division
        Route::get('/division/view',         [ShippingAreaController::class, 'DivisionView'])->name('division.manage');
        Route::post('/division/store',       [ShippingAreaController::class, 'DivisionStore'])->name('division.store');
        Route::get('/division/edit/{id}',    [ShippingAreaController::class, 'DivisionEdit'])->name('division.edit');
        Route::post('/division/update/{id}', [ShippingAreaController::class, 'DivisionUpdate'])->name('division.update');
        Route::get('/division/delete/{id}',  [ShippingAreaController::class, 'DivisionDelete'])->name('division.delete');

        // Ship District
        Route::get('/district/view',         [ShippingAreaController::class, 'DistrictView'])->name('district.manage');
        Route::post('/district/store',       [ShippingAreaController::class, 'DistrictStore'])->name('district.store');
        Route::get('/district/edit/{id}',    [ShippingAreaController::class, 'DistrictEdit'])->name('district.edit');
        Route::post('/district/update/{id}', [ShippingAreaController::class, 'DistrictUpdate'])->name('district.update');
        Route::get('/district/delete/{id}',  [ShippingAreaController::class, 'DistrictDelete'])->name('district.delete');

        // Ship State
        Route::get('/state/view',         [ShippingAreaController::class, 'StateView'])->name('state.manage');
        Route::post('/state/store',       [ShippingAreaController::class, 'StateStore'])->name('state.store');
        Route::get('/state/edit/{id}',    [ShippingAreaController::class, 'StateEdit'])->name('state.edit');
        Route::post('/state/update/{id}', [ShippingAreaController::class, 'StateUpdate'])->name('state.update');
        Route::get('/state/delete/{id}',  [ShippingAreaController::class, 'StateDelete'])->name('state.delete');

    });

    // Admin Order All Routes
    Route::prefix('orders')->group(function(){

        Route::get('/pending/', [AdminOrderController::class, 'PendingOrders'])->name('pending-orders');
        Route::get('/order/details/{order_id}', [AdminOrderController::class, 'AdminOrdersDetails'])->name('pending.order.details');
        Route::get('/confirmed', [AdminOrderController::class, 'ConfirmedOrders'])->name('confirmed-orders');
        Route::get('/processing', [AdminOrderController::class, 'ProcessingOrders'])->name('processing-orders');
        Route::get('/picked', [AdminOrderController::class, 'PickedOrders'])->name('picked-orders');
        Route::get('/shipped', [AdminOrderController::class, 'ShippedOrders'])->name('shipped-orders');
        Route::get('/delivered', [AdminOrderController::class, 'DeliveredOrders'])->name('delivered-orders');
        Route::get('/canceled', [AdminOrderController::class, 'CanceledOrders'])->name('canceled-orders');

        // Update Status
        Route::get('/pending/confirm/{order_id}', [AdminOrderController::class, 'PendingToConfirm'])->name('pending-confirm');
        Route::get('/confirmed/processing/{order_id}', [AdminOrderController::class, 'ConfirmToProcessing'])->name('confirm.processing');
        Route::get('/processing/picked/{order_id}', [AdminOrderController::class, 'ProcessingToPicked'])->name('processing.picked');
        Route::get('/picked/shipped/{order_id}', [AdminOrderController::class, 'PickedToShipped'])->name('picked.shipped');
        Route::get('/shipped/delivered/{order_id}', [AdminOrderController::class, 'ShippedToDelivered'])->name('shipped.delivered');
        Route::get('/invoice/download/{order_id}', [AdminOrderController::class, 'AdminInvoiceDownload'])->name('invoice.download');

    });


    // Admin Reports Routes
    Route::prefix('reports')->group(function(){

        Route::get('/view',             [ReportController::class, 'ReportView'])->name('all-reports');
        Route::post('/search/by/date',  [ReportController::class, 'ReportByDate'])->name('search-by-date');
        Route::post('/search/by/month', [ReportController::class, 'ReportByMonth'])->name('search-by-month');
        Route::post('/search/by/year', [ReportController::class, 'ReportByYear'])->name('search-by-year');

    });

    // Admin Get All User Routes
    Route::prefix('allusers')->group(function(){
        Route::get('/view', [AdminProfileController::class, 'AllUsers'])->name('all-users')->middleware('user');
    });

    // Admin Blog  Routes
    Route::prefix('blog')->group(function(){

        Route::get('/category', [BlogController::class, 'BlogCategory'])->name('blog.category');
        Route::post('/store',   [BlogController::class, 'BlogCategoryStore'])->name('blogcategory.store');
        Route::get('/category/edit/{id}', [BlogController::class, 'BlogCategoryEdit'])->name('blog.category.edit');
        Route::post('/update', [BlogController::class, 'BlogCategoryUpdate'])->name('blogcategory.update');

        // Admin View Blog Post Routes
        Route::get('/list/post', [BlogController::class, 'ListBlogPost'])->name('list.post');
        Route::get('/add/post', [BlogController::class, 'AddBlogPost'])->name('add.post');
        Route::post('/post/store', [BlogController::class, 'BlogPostStore'])->name('post-store');
    });


    // Admin Site Setting Routes
    Route::prefix('settings')->group(function(){

        Route::get('/site',         [SiteSettingController::class, 'SiteSetting'])->name('site.settings');
        Route::post('/site/update', [SiteSettingController::class, 'SiteSettingUpdate'])->name('update.sitesetting');
//        Route::get('/seo', [SiteSettingController::class, 'SeoSetting'])->name('seo.settings');
//        Route::post('/seo/update', [SiteSettingController::class, 'SeoSettingUpdate'])->name('update.seosetting');
    });

    // Admin Manage Review Routes
    Route::prefix('review')->group(function(){
        Route::get('/pending',            [ReviewController::class, 'PendingReview'])->name('pending.review');
        Route::get('/admin/approve/{id}', [ReviewController::class, 'ReviewApprove'])->name('review.approve');
        Route::get('/publish',            [ReviewController::class, 'PublishReview'])->name('publish.review');
        Route::get('/delete/{id}',        [ReviewController::class, 'DeleteReview'])->name('delete.review');

    });

    // Admin Manage Stock Routes
    Route::prefix('stock')->group(function(){

        Route::get('/product', [ProductController::class, 'ProductStock'])->name('product.stock');


    });

    // Admin User Role Routes
    Route::prefix('adminuserrole')->group(function(){

        Route::get('/all', [AdminUserController::class, 'AllAdminRole'])->name('all.admin.user');

        Route::get('/add', [AdminUserController::class, 'AddAdminRole'])->name('add.admin');

        Route::post('/store', [AdminUserController::class, 'StoreAdminRole'])->name('admin.user.store');

        Route::get('/edit/{id}', [AdminUserController::class, 'EditAdminRole'])->name('edit.admin.user');

        Route::post('/update', [AdminUserController::class, 'UpdateAdminRole'])->name('admin.user.update');

        Route::get('/delete/{id}', [AdminUserController::class, 'DeleteAdminRole'])->name('delete.admin.user');

    });


});


/////////////////////  User Must Login  ////
Route::group( ['prefix'=>'user', 'middleware' => ['auth', 'user'] ],function(){

    Route::get('/profile',          [UserProfileController::class, 'UserProfile'])->name('user.profile');
    Route::post('/profile/store',   [UserProfileController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::get('/change/password',  [UserProfileController::class, 'UserChangePassword'])->name('user.change.password');
    Route::post('/update/password', [UserProfileController::class, 'UserPasswordUpdate'])->name('user.update.password');

    // Wishlist page
    Route::get('/wishlist', [WishlistController::class, 'ViewWishlist'])->name('wishlist');

    /// Order Traking Route
    Route::post('/order/tracking',              [UserOrderController::class, 'OrderTraking'])->name('order.tracking');
    Route::get('/my/orders',                    [UserOrderController::class, 'MyOrders'])->name('my.orders');
    Route::get('/order_details/{order_id}',     [UserOrderController::class, 'OrderDetails']);
    Route::get('/invoice_download/{order_id}',  [UserOrderController::class, 'InvoiceDownload']);
    Route::post('/return/order/{order_id}',     [UserOrderController::class, 'ReturnOrder'])->name('return.order');
    Route::get('/return/order/list',            [UserOrderController::class, 'ReturnOrderList'])->name('return.order.list');
    Route::get('/cancel/orders',                [UserOrderController::class, 'CancelOrders'])->name('cancel.orders');

});


//// Admin Return Order Routes
//Route::prefix('return')->group(function(){
//
//Route::get('/admin/request', [ReturnController::class, 'ReturnRequest'])->name('return.request');
//
//Route::get('/admin/return/approve/{order_id}', [ReturnController::class, 'ReturnRequestApprove'])->name('return.approve');
//
//Route::get('/admin/all/request', [ReturnController::class, 'ReturnAllRequest'])->name('all.request');
//
//});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
