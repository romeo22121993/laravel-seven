<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;

use App\Models\Product;
use App\Models\MultiImg;
use Carbon\Carbon;
use Image;

class ProductController extends Controller
{

    public $settings = [];

    public function __construct() {
        $this->settings = SiteSetting::find(1);
    }

    /**
    * Function for view all products page
    *
    * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    */
    public function ManageProduct(){

        $products = Product::latest()->get();
        $settings = $this->settings;

        return view('backend.product.product_view',compact('products', 'settings'));
    }

    /**
    * Function for view of adding products
    *
    * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    */
    public function AddProduct(){
        $categories = Category::where('category_id', 0)->get();
        $brands     = Brand::latest()->get();
        $settings   = $this->settings;

        return view('backend.product.product_add',compact('categories','brands', 'settings'));
    }

    /**
    * Function of creating product
    *
    * @param Request $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function StoreProduct(Request $request){

        $digitalItem = '';

        $request->validate([
            'file' => 'required|mimes:doc,docx,pdf|max:2048'
        ]);

        if ( !empty( $request->file('file') ) && ( $files = $request->file('file') )) {
            $destinationPath = 'upload/products/pdf'; // upload path
            $digitalItem = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $digitalItemPath = $destinationPath . '/' . $digitalItem;
            $files->move( $destinationPath,$digitalItem );
        }

        $image = $request->file('product_thambnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,500)->save('upload/products/thambnail/'.$name_gen);
        $save_url = 'upload/products/thambnail/'.$name_gen;

        $product_id = Product::insertGetId([
            'brand_id'          => $request->brand_id,
            'category_id'       => $request->category_id,
            'subcategory_id'    => $request->subcategory_id,
            'subsubcategory_id' => $request->subsubcategory_id,
            'product_name_en'   => $request->product_name_en,
            'product_name_hin'  => $request->product_name_hin,
            'product_slug_en'   => strtolower(str_replace(' ', '-', $request->product_name_en)),
            'product_slug_hin'  => strtolower( str_replace(' ', '-', $request->product_name_hin)),
            'product_code'      => $request->product_code,

            'product_qty'       => $request->product_qty,
            'product_tags_en'   => $request->product_tags_en,
            'product_tags_hin'  => $request->product_tags_hin,
            'product_size_en'   => $request->product_size_en,
            'product_size_hin'  => $request->product_size_hin,
            'product_color_en'  => $request->product_color_en,
            'product_color_hin' => $request->product_color_hin,

            'selling_price'     => $request->selling_price,
            'discount_price'    => $request->discount_price,
            'short_descp_en'    => $request->short_descp_en,
            'short_descp_hin'   => $request->short_descp_hin,
            'long_descp_en'     => $request->long_descp_en,
            'long_descp_hin'    => $request->long_descp_hin,

            'hot_deals'     => $request->hot_deals,
            'featured'      => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,

            'product_thambnail' => $save_url,
            'digital_file'      => $digitalItemPath,
            'status'            => 1,
            'created_at'        => Carbon::now(),

        ]);


        ////////// Multiple Image Upload Start ///////////
        $images = $request->file('multi_img');
        foreach ( $images as $img ) {
            $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();

            $uploadPath = 'upload/products/multi-image/'. $make_name;
            Image::make($img)->resize(300,500)->save($uploadPath);

            MultiImg::insert([
                'product_id' => $product_id,
                'photo_name' => $uploadPath,
                'created_at' => Carbon::now(),
            ]);
        }

        ////////// Een Multiple Image Upload Start ///////////
        $notification = array(
            'message'    => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('product.manage')->with($notification);

    }

    /**
    * Function of editing product
    *
    * @param $id
    * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    */
    public function EditProduct($id){

        $products = Product::findOrFail($id);
        $multiImgs      = MultiImg::where('product_id', $id)->get();

        $categories     = Category::where('category_id', 0)->get();
        $subcategory    = Category::where('category_id', $products->category_id )->get();
        $subsubcategory = Category::where('category_id', $products->category_id )->where('subcategory_id', $products->subcategory_id)->get();

        $brands   = Brand::latest()->get();
        $settings = $this->settings;

        return view('backend.product.product_edit',compact( 'settings', 'categories','brands','subcategory','subsubcategory', 'products', 'multiImgs'));

    }

    /**
     * Function updating product
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ProductDataUpdate(Request $request){

        $product_id = $request->id;

        Product::findOrFail($product_id)->update([
            'brand_id'          => $request->brand_id,
            'category_id'       => $request->category_id,
            'subcategory_id'    => $request->subcategory_id,
            'subsubcategory_id' => $request->subsubcategory_id,
            'product_name_en'   => $request->product_name_en,
            'product_name_hin'  => $request->product_name_hin,
            'product_slug_en'   => strtolower(str_replace(' ', '-', $request->product_name_en)),
            'product_slug_hin'  => strtolower( str_replace(' ', '-', $request->product_name_hin)),
            'product_code'      => $request->product_code,

            'product_qty'       => $request->product_qty,
            'product_tags_en'   => $request->product_tags_en,
            'product_tags_hin'  => $request->product_tags_hin,
            'product_size_en'   => $request->product_size_en,
            'product_size_hin'  => $request->product_size_hin,
            'product_color_en'  => $request->product_color_en,
            'product_color_hin' => $request->product_color_hin,

            'selling_price'   => $request->selling_price,
            'discount_price'  => $request->discount_price,
            'short_descp_en'  => $request->short_descp_en,
            'short_descp_hin' => $request->short_descp_hin,
            'long_descp_en'   => $request->long_descp_en,
            'long_descp_hin'  => $request->long_descp_hin,

            'hot_deals'     => $request->hot_deals,
            'featured'      => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,
            'status'        => 1,
            'created_at'    => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Product Updated Without Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('product.manage')->with($notification);

    }


    /**
     * Function updating multiple image
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function MultiImageUpdate(Request $request){
        $imgs = $request->multi_img;

        foreach ($imgs as $id => $img) {
            $imgDel = MultiImg::findOrFail($id);
            unlink($imgDel->photo_name);

            $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
            Image::make($img)->resize(500,900)->save('upload/products/multi-image/'.$make_name);
            $uploadPath = 'upload/products/multi-image/'.$make_name;

            MultiImg::where('id',$id)->update([
                'photo_name' => $uploadPath,
                'updated_at' => Carbon::now(),
            ]);

        }

        $notification = array(
            'message' => 'Product Image Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

    }

    /**
     * Function of updating thumbnail in edit page
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ThambnailImageUpdate(Request $request){
        $pro_id = $request->id;
        $oldImage = $request->old_img;
        unlink($oldImage);

        $image = $request->file('product_thambnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(917,1000)->save('upload/products/thambnail/'.$name_gen);
        $save_url = 'upload/products/thambnail/'.$name_gen;

        Product::findOrFail( $pro_id )->update([
            'product_thambnail' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message'    => 'Product Image Thambnail Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

    }

    //// Multi Image Delete ////
    /**
     * Function of deleting multi image from edit product page
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function MultiImageDelete( $id ) {
        $oldimg = MultiImg::findOrFail( $id );
        unlink( $oldimg->photo_name );

        MultiImg::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Product Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }

    /**
     * Function of inactivate product by id
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ProductInactive($id){

        Product::findOrFail( $id )->update(['status' => 0]);
        $notification = array(
            'message'    => 'Product is inactived',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    /**
     * Function of activation for product
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ProductActive($id){

        Product::findOrFail( $id )->update( ['status' => 1] );
        $notification = array(
            'message' => 'Product Active',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Function deleting product
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ProductDelete($id){
        $product = Product::findOrFail($id);
        if ( file_exists( $product->product_thambnail ) ){
            unlink($product->product_thambnail);
        }
        if ( file_exists( $product->digital_file ) ){
            unlink($product->digital_file);
        }
        Product::findOrFail($id)->delete();

        $images = MultiImg::where('product_id',$id)->get();
        foreach ($images as $img) {
            if ( file_exists( $img->photo_name ) ){
                unlink($img->photo_name);
            }
        }
        MultiImg::where('product_id',$id)->delete();

        $notification = array(
            'message'    => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }

    /**
     * Function product stock
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ProductStock(){

        $products = Product::latest()->get();
        $settings = $this->settings;
        return view('backend.product.product_stock',compact('products', 'settings'));

    }

}
