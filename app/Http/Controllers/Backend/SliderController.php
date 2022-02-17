<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;
use Carbon\Carbon;
use Image;

class SliderController extends Controller
{

    /**
     * Function for view all sliders page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function SliderView(){
		$sliders = Slider::latest()->get();
		return view('backend.slider.slider_view',compact('sliders'));
	}


    /**
     * Function of adding new slider
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
     public function SliderStore(Request $request){

    	$request->validate([
    		'slider_img' => 'required',
    	],[
    		'slider_img.required' => 'Plz Select One Image',
    	]);

    	$image = $request->file('slider_img');
    	$name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
    	Image::make($image)->resize(500,370)->save('upload/sliders/'.$name_gen);
    	$save_url = 'upload/sliders/'.$name_gen;

        Slider::insert([
            'title'       => $request->title,
            'description' => $request->description,
            'slider_img'  => $save_url,
        ]);

	    $notification = array(
			'message' => 'Slider Inserted Successfully',
			'alert-type' => 'success'
		);

		return redirect()->back()->with($notification);

    } // end method


    /**
     * Functuin view for edit page
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function SliderEdit($id){
        $slider = Slider::findOrFail($id);
		return view('backend.slider.slider_edit',compact('slider'));
    }


    /**
     * Function of updating slider
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SliderUpdate(Request $request){

    	$slider_id = $request->id;
    	$old_img   = $request->old_image;

    	if ( $request->file('slider_img') ) {

    	    if ( file_exists( $old_img )) {
                unlink( $old_img );
            }

            $image = $request->file('slider_img');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(570,370)->save('upload/sliders/'.$name_gen);
            $save_url = 'upload/sliders/'.$name_gen;

            Slider::findOrFail($slider_id)->update([
                'title'       => $request->title,
                'description' => $request->description,
                'slider_img'  => $save_url,
            ]);

            $notification = array(
                'message' => 'Slider Updated Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('slider.manage')->with($notification);

    	}  else {

            Slider::findOrFail($slider_id)->update([
                'title'       => $request->title,
                'description' => $request->description,
            ]);

            $notification = array(
                'message'    => 'Slider Updated Without Image Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('slider.manage')->with($notification);

    	} // end else

    } // end method


    /**
     * Function for deleting slider
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SliderDelete($id){
    	$slider = Slider::findOrFail($id);
    	$img = $slider->slider_img;
    	unlink($img);
    	Slider::findOrFail($id)->delete();

    	$notification = array(
			'message' => 'Slider Delectd Successfully',
			'alert-type' => 'info'
		);

		return redirect()->back()->with($notification);

    } // end method


    /**
     * Function for inactive of slider
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SliderInactive($id){
    	Slider::findOrFail($id)->update(['status' => 0]);

    	$notification = array(
			'message'    => 'Slider Inactive Successfully',
			'alert-type' => 'info'
		);

		return redirect()->back()->with($notification);

    } // end method


    /**
     * Function for activation of slider
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SliderActive($id){
    	Slider::findOrFail($id)->update(['status' => 1]);

    	$notification = array(
			'message'    => 'Slider Active Successfully',
			'alert-type' => 'info'
		);

		return redirect()->back()->with($notification);

    } // end method


}
