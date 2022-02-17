<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;


class ShopController extends Controller
{

    /**
     * Function shop page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ShopPage(){

        $products = Product::query();

        if ( !empty($_GET['category']) && empty($_GET['brand'])  ) {
            $slugs    = explode(',',$_GET['category']);
            $catIds   = Category::select('id')->whereIn('category_slug_en', $slugs)->pluck('id')->toArray();
            $products = $products->whereIn('category_id', $catIds)->paginate(2);
        }
        elseif ( !empty($_GET['brand']) && empty($_GET['category']) ) {
            $slugs    = explode(',',$_GET['brand']);
            $brandIds = Brand::select('id')->whereIn('brand_slug_en', $slugs)->pluck('id')->toArray();
            $products = $products->whereIn('brand_id',$brandIds)->paginate(2);
        }
        elseif ( !empty($_GET['category']) && !empty($_GET['brand'])  ) {
            $slugs    = explode(',',$_GET['category']);
            $catIds   = Category::select('id')->whereIn('category_slug_en', $slugs)->pluck('id')->toArray();
            $slugs1   = explode(',',$_GET['brand']);
            $brandIds = Brand::select('id')->whereIn('brand_slug_en', $slugs1)->pluck('id')->toArray();
            $products = $products->whereIn('category_id', $catIds)->orWhereIn('brand_id',$brandIds)->paginate(2);
        }
        else {
            $products = Product::where('status', 1)->orderBy('id','DESC')->paginate(2);
        }

        $index_controller = new IndexController();

        $brands     = Brand::orderBy('brand_name_en','ASC')->get();
        $categories = Category::where('category_id', 0)->orderBy('category_name_en', 'ASC')->get();

        $tags_en  = Product::groupBy('product_tags_en')->select('product_tags_en')->get();
        $tags_hin = Product::groupBy('product_tags_hin')->select('product_tags_hin')->get();

        $tags_hin =  $index_controller->getDistinctTags( $tags_hin, 'hin' );
        $tags_en  =  $index_controller->getDistinctTags( $tags_en, 'en' );
        $chosen_tag  = '';

        return view('frontend.shop.shop_page',compact('products','categories','brands',
            'chosen_tag', 'tags_hin', 'tags_en'));

    }


    /**
     * Function shop filtering
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ShopFilter(Request $request){

        $data = $request->all();

        // Filter Category
        $catUrl = "";
        if (!empty($data['category'])) {
           foreach ($data['category'] as $category) {
              if ( empty( $catUrl ) ) {
                 $catUrl .= '&category='.$category;
              } else {
                $catUrl .= ','.$category;
              }
           }
        }

         // Filter Brand
        $brandUrl = "";
        if (!empty($data['brand'])) {
           foreach ($data['brand'] as $brand) {
              if (empty($brandUrl)) {
                 $brandUrl .= '&brand='.$brand;
              } else {
                $brandUrl .= ','.$brand;
              }
           }
        }

        return redirect()->route('shop',$catUrl.$brandUrl);

    }

}
