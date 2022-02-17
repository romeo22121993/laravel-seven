<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Seo;
use Image;

class SiteSettingController extends Controller
{

    public $settings = [];
    public function __construct()
    {
        $settings   = $this->settings;
    }

    /**
     * Function site settings
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function SiteSetting(){
    	$settings = SiteSetting::find(1);

    	return view('backend.settings.setting_update', compact('settings'));
    }

    /**
     * Function updating of site settings
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SiteSettingUpdate( Request $request ){

    	$setting_id = $request->id;

        $settings  = SiteSetting::find(1);

        $save_url  = ( !empty( $settings->logo) ) ? $settings->logo : '';

        if ( $request->file('logo') ) {
            $image    = $request->file('logo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(139,36)->save('upload/logo/'.$name_gen);
            $save_url = 'upload/logo/'.$name_gen;
        }

        SiteSetting::findOrFail($setting_id)->update([
            'phone_one'        => $request->phone_one,
            'phone_two'        => $request->phone_two,
            'email'            => $request->email,
            'company_name'     => $request->company_name,
            'company_address'  => $request->company_address,
            'facebook'         => $request->facebook,
            'twitter'          => $request->twitter,
            'linkedin'         => $request->linkedin,
            'youtube'          => $request->youtube,
            'meta_title'       => $request->meta_title,
            'meta_author'      => $request->meta_author,
            'meta_keyword'     => $request->meta_keyword,
            'meta_description' => $request->meta_description,
            'google_analytics' => $request->google_analytics,
            'logo'             => $save_url,
        ]);

        $notification = array(
            'message'    => 'Setting Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);
    }

    public function SeoSettingUpdate(Request $request){

    	$seo_id = $request->id;

    	Seo::findOrFail($seo_id)->update([
		'meta_title' => $request->meta_title,
		'meta_author' => $request->meta_author,
		'meta_keyword' => $request->meta_keyword,
		'meta_description' => $request->meta_description,
		'google_analytics' => $request->google_analytics,

    	]);

	    $notification = array(
			'message' => 'Seo Updated Successfully',
			'alert-type' => 'info'
		);

		return redirect()->back()->with($notification);

    } // end mehtod

}
