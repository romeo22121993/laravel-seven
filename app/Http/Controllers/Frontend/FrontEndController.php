<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MultiImg;
use App\Models\Product;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Review;
use App\Models\Slider;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class FrontEndController extends Controller
{

    public $index_controller;

    public function __construct( )
    {
        $this->index_controller = new IndexController();
    }

    /**
     * Function showing products by tags
     *
     * @param $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function TagWiseProduct($chosen_tag){

        if ( ( session()->get('language') == 'hindi' ) ) {
            $products = Product::where('status', 1)->where('product_tags_hin', 'LIKE' , '%' .$chosen_tag .'%')->orderBy('id', 'DESC')->paginate(1);
        }
        else {
            $products = Product::where('status',1)->where('product_tags_en', 'LIKE' , '%' .$chosen_tag .'%')->orderBy('id','DESC')->paginate(1);
        }

        $categories = Category::where('category_id', 0)->orderBy('category_name_en', 'ASC')->get();

        $tags_en  = Product::groupBy('product_tags_en')->select('product_tags_en')->get();
        $tags_hin = Product::groupBy('product_tags_hin')->select('product_tags_hin')->get();

        $tags_hin =  $this->index_controller->getDistinctTags( $tags_hin, 'hin' );
        $tags_en  =  $this->index_controller->getDistinctTags( $tags_en, 'en' );

        return view('frontend.product.tags_view',compact('products','categories', 'tags_en', 'tags_hin', 'chosen_tag'));
    }


    /**
     * Category page for products functionality
     *
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function SubCatWiseProduct(Request $request, $subcat_id, $slug){

        $products = Product::where('status',1)->where('subcategory_id', $subcat_id)->orderBy('id','DESC')->paginate(1);
        $categories = Category::where('category_id', 0)->orderBy('category_name_en', 'ASC')->get();

        $tags_en  = Product::groupBy('product_tags_en')->select('product_tags_en')->get();
        $tags_hin = Product::groupBy('product_tags_hin')->select('product_tags_hin')->get();

        $tags_hin =  $this->index_controller->getDistinctTags( $tags_hin, 'hin' );
        $tags_en  =  $this->index_controller->getDistinctTags( $tags_en, 'en' );
        $chosen_tag  = '';

        $breadsubcat = 'ff';

        ///  Load More Product with Ajax
        if ($request->ajax()) {
            $grid_view = view('frontend.product.grid_view_product',compact('products'))->render();
            $list_view = view('frontend.product.list_view_product',compact('products'))->render();
            return response()->json(['grid_view' => $grid_view, 'list_view' => $list_view]);
        }

        ///  End Load More Product with Ajax

        return view('frontend.product.subcategory_view',compact('products','categories','breadsubcat', 'tags_en', 'tags_hin', 'chosen_tag'));

    }

    /**
     * Function subsubcategories product list
     *
     * @param $subsubcat_id
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function SubSubCatWiseProduct( Request $request, $subsubcat_id, $slug){
        $products = Product::where('status',1)->where('subsubcategory_id',$subsubcat_id)->orderBy('id','DESC')->paginate(1);

        $categories = Category::where('category_id', 0)->orderBy('category_name_en', 'ASC')->get();

        $tags_en  = Product::groupBy('product_tags_en')->select('product_tags_en')->get();
        $tags_hin = Product::groupBy('product_tags_hin')->select('product_tags_hin')->get();

        $tags_hin =  $this->index_controller->getDistinctTags( $tags_hin, 'hin' );
        $tags_en  =  $this->index_controller->getDistinctTags( $tags_en, 'en' );
        $chosen_tag  = '';

        $breadsubsubcat = 'gg';

        return view('frontend.product.subcategory_view',compact('products','categories','breadsubsubcat', 'tags_en', 'tags_hin', 'chosen_tag' ));

    }

    /**
     * Function for product detail page
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ProductDetails($id){
        $product = Product::findOrFail($id);

        $color_en         = $product->product_color_en;
        $product_color_en = explode(',', $color_en);

        $color_hin         = $product->product_color_hin;
        $product_color_hin = explode(',', $color_hin);

        $size_en         = $product->product_size_en;
        $product_size_en = explode(',', $size_en);

        $size_hin         = $product->product_size_hin;
        $product_size_hin = explode(',', $size_hin);

        $multiImag      = MultiImg::where('product_id',$id)->get();

        $cat_id         = $product->category_id;
        $relatedProduct = Product::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->get();

        $featured      = Product::where('featured', 1)->orderBy('id', 'DESC')->limit(6)->get();
        $hot_deals     = Product::where('hot_deals', 1)->where('discount_price', '!=', NULL)->orderBy('id', 'DESC')->limit(3)->get();
        $special_offer = Product::where('special_offer', 1)->orderBy('id', 'DESC')->limit(6)->get();
        $special_deals = Product::where('special_deals', 1)->orderBy('id', 'DESC')->limit(3)->get();

        $tags_en  = Product::groupBy('product_tags_en')->select('product_tags_en')->get();
        $tags_hin = Product::groupBy('product_tags_hin')->select('product_tags_hin')->get();

        $tags_hin = $this->index_controller->getDistinctTags( $tags_hin, 'hin' );
        $tags_en  = $this->index_controller->getDistinctTags( $tags_en, 'en' );

        $reviews  = Review::where('product_id',$id)->latest()->limit(5)->get();


        return view('frontend.product.product_details',
            compact('product','multiImag','product_color_en','product_color_hin','product_size_en','product_size_hin',
                'relatedProduct',  'featured', 'hot_deals', 'special_offer', 'special_deals', 'tags_en', 'tags_hin', 'reviews' )
        );
    }

    /**
     * Function getting ajax data by product id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ProductViewAjax($id){

        $product = Product::with('category','brand')->findOrFail($id);

        $color         = $product->product_color_en;
        $product_color = explode(',', $color);

        $size         = $product->product_size_en;
        $product_size = explode(',', $size);

        return response()->json(array(
            'product' => $product,
            'color'   => $product_color,
            'size'    => $product_size,
        ));

    }


    /**
     * Function product searching
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ProductSearch(Request $request){

        $request->validate(["search" => "required"]);
        $item = $request->search;

        $categories = Category::orderBy('category_name_en','ASC')->get();
        $products   = Product::where('product_name_en','LIKE',"%$item%")->get();

        $featured      = Product::where('featured', 1)->orderBy('id', 'DESC')->limit(6)->get();
        $hot_deals     = Product::where('hot_deals', 1)->where('discount_price', '!=', NULL)->orderBy('id', 'DESC')->limit(3)->get();
        $special_offer = Product::where('special_offer', 1)->orderBy('id', 'DESC')->limit(6)->get();
        $special_deals = Product::where('special_deals', 1)->orderBy('id', 'DESC')->limit(3)->get();

        $tags_en  = Product::groupBy('product_tags_en')->select('product_tags_en')->get();
        $tags_hin = Product::groupBy('product_tags_hin')->select('product_tags_hin')->get();

        $tags_hin = $this->index_controller->getDistinctTags( $tags_hin, 'hin' );
        $tags_en  = $this->index_controller->getDistinctTags( $tags_en, 'en' );
        $chosen_tag  = '';

        return view('frontend.product.search',compact('products','categories', 'tags_hin',
        'tags_en', 'special_offer', 'special_deals', 'featured', 'hot_deals', 'chosen_tag' ));

    }


    /**
     * Advance Search Options
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function SearchProduct(Request $request){

        $request->validate(["search" => "required"]);

        $item = $request->search;

        $products = Product::where('product_name_en','LIKE',"%$item%")->select('product_name_en','product_thambnail','selling_price','id','product_slug_en')->limit(5)->get();
        return view('frontend.product.search_product', compact('products'));

    }

}
