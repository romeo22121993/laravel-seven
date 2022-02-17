<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Route;

class UserProfileController extends Controller
{

    /**
     * Function construct
     *
     */
    public function __construct () {
       $this->middleware('auth');
    }

    /**
     * Function for getting user profile page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function UserProfile(){
        $id = Auth::user()->id;
        $user = User::find($id);
        return view('frontend.profile.user_profile', compact('user'));
    }

    /**
     * Function updating profile
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function UserProfileStore(Request $request){
        $data = User::find(Auth::user()->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ( $request->file('profile_photo_path') ) {
            $file = $request->file('profile_photo_path');
            @unlink(public_path('upload/user_images/'.$data->profile_photo_path));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);
            $data['profile_photo_path'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('dashboard')->with($notification);

    }


    /**
     * Function view for change password
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function UserChangePassword(){
        $id = Auth::user()->id;
        $user = User::find($id);
        return view('frontend.profile.change_password',compact('user'));
    }

    /**
     * Function updating password for user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function UserPasswordUpdate(Request $request){

        $request->validate([
            'oldpassword'   => ['required', new MatchOldPassword],
            'password'      => ['required'],
            'password_confirmation' => ['same:password'],
        ]);


        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->oldpassword,$hashedPassword)) {
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();
            return redirect()->route('logout');
        } else {
            return redirect()->back();
        }

    }

}
