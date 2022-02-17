<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Brand;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{

    /**
     * Function for view page for brands
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function BrandView(){

        $brands = Brand::latest()->get();
        return view('backend.brand.brand_view',compact('brands'));

    }

    /**
     * Function of creating brand process
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function BrandStore(Request $request){

        $request->validate([
            'brand_name_en'  => 'required',
            'brand_name_hin' => 'required',
            'brand_image'    => 'required',
        ],[
            'brand_name_en.required' => 'Input Brand English Name',
            'brand_name_hin.required' => 'Input Brand Hindi Name',
        ]);

        $image = $request->file('brand_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/brands/'.$name_gen);
        $save_url = 'upload/brands/'.$name_gen;

        Brand::insert([
            'brand_name_en' => $request->brand_name_en,
            'brand_name_hin' => $request->brand_name_hin,
            'brand_slug_en' =>  strtolower(str_replace([' ', ','], ['-', '-'], $request->brand_name_en)),
            'brand_slug_hin' => strtolower( str_replace([' ', ','], ['-', '-'], $request->brand_name_hin)),
            'brand_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Brand Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // end method

    /**
     * Function for editing page
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function BrandEdit($id){
        $brand = Brand::findOrFail($id);
        return view('backend.brand.brand_edit',compact('brand'));
    }

    /**
     * Function of updating brand
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function BrandUpdate(Request $request){

        $brand_id = $request->id;
        $old_img = $request->old_image;

        if ( $request->file('brand_image') ) {

            if ( file_exists($old_img) ) {
                unlink($old_img);
            }

            $image = $request->file('brand_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/brands/'.$name_gen);
            $save_url = 'upload/brands/'.$name_gen;

            Brand::findOrFail($brand_id)->update([
                'brand_name_en' => $request->brand_name_en,
                'brand_name_hin' => $request->brand_name_hin,
                'brand_slug_en' => strtolower(str_replace(' ', '-',$request->brand_name_en)),
                'brand_slug_hin' => strtolower(str_replace(' ', '-',$request->brand_name_hin)),
                'brand_image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Brand Updated Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('brands.all')->with($notification);

        }else{

            Brand::findOrFail( $brand_id )->update([
                'brand_name_en'  => $request->brand_name_en,
                'brand_name_hin' => $request->brand_name_hin,
                'brand_slug_en'  => strtolower(str_replace(' ', '-',$request->brand_name_en)),
                'brand_slug_hin' => str_replace(' ', '-',$request->brand_name_hin),
            ]);

            $notification = array(
                'message' => 'Brand Updated Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('brands.all')->with($notification);

        } // end else
    } // end method


    /**
     * Function for brands deletions
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function BrandDelete($id){

        $brand = Brand::findOrFail($id);
        $img = $brand->brand_image;
        if ( file_exists( $img ) ) {
            unlink($img);
        }

        Brand::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

    } // end method

}
