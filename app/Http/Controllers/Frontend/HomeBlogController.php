<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\BlogPostCategory;
use App\Models\Blog\BlogPost;

class HomeBlogController extends Controller
{

    /**
     * Function adding blog posts
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function BlogList(){

    	$blogcategory = BlogPostCategory::latest()->get();
    	$blogpost = BlogPost::latest()->get();
    	return view('frontend.blog.blog_list',compact('blogpost','blogcategory'));

    }

    /**
     * Function details page for blog
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function DetailsBlogPost($id){
        $blogcategory = BlogPostCategory::latest()->get();
    	$blogpost     = BlogPost::findOrFail($id);
    	return view('frontend.blog.blog_details',compact('blogpost','blogcategory'));
    }


    /**
     * Function for categorie's blogs
     *
     * @param $category_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function HomeBlogCatPost($category_id){

    	$blogcategory = BlogPostCategory::latest()->get();
    	$blogpost     = BlogPost::where('category_id', $category_id)->orderBy('id','DESC')->get();
    	return view('frontend.blog.blog_cat_list',compact('blogpost','blogcategory'));

    }

}
