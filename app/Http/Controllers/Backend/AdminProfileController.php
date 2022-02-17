<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Order;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Route;

class AdminProfileController extends Controller
{

    public function __construct () {
       $this->middleware('admin');
    }

    /**
     * Admin profile page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function AdminProfile(){

        $id = Auth::user()->id;
        $adminData = User::find( $id );
        $settings  = SiteSetting::find(1);

        $date  = date('d-m-y');
        $today = Order::where('order_date',$date)->sum('amount');

        $month = date('F');
        $month = Order::where('order_month',$month)->sum('amount');

        $year  = date('Y');
        $year  = Order::where('order_year',$year)->sum('amount');

        $pending = Order::where('status','pending')->get();
        $orders = App\Models\Order::where('status','pending')->orderBy('id','DESC')->get();

        return view( 'admin.admin_profile_view', compact( 'adminData', 'settings', 'today',
            'month', 'year', 'pending', 'orders' ) );

    }

    /**
     * Function for editing admin profile
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function AdminProfileEdit(){

        $id = Auth::user()->id;
        $editData = User::find($id);
        return view('admin.admin_profile_edit', compact('editData'));

    }

    /**
     * Function for editing store data
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function AdminProfileStore(Request $request){

        $id   = Auth::user()->id;
        $data = User::find($id);
        $data->name  = $request->name;
        $data->email = $request->email;

        if ( $request->file('profile_photo_path' ) ) {
            $file = $request->file('profile_photo_path');
            @unlink(public_path('upload/user_images/'.$data->profile_photo_path));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);
            $data['profile_photo_path'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.profile')->with($notification);

    }


    /**
     * Function for view of change password page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function AdminChangePassword(){
        return view('admin.admin_change_password');
    }

    /**
     * Function of updating password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function AdminUpdateChangePassword(Request $request){

        $request->validate([
            'oldpassword'   => ['required', new MatchOldPassword],
            'password'      => ['required'],
            'password_confirmation' => ['same:password'],
        ]);

        $hashedPassword = Auth::user()->password;
        if ( Hash::check($request->oldpassword,$hashedPassword ) ) {
            $admin = User::find(Auth::id());
            $admin->password = Hash::make($request->password);
            $admin->save();
            Auth::logout();
            return redirect()->route('admin.logout');
        } else {
            $errors = array(
                'message' => 'Wrong current password',
                'alert-type' => 'success'
            );
            return redirect()->back()->with( $errors );
        }

    }

    /**
     * Function getting all users
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function AllUsers(){
        $users = User::latest()->get();
        return view('backend.user.all_user',compact('users'));
    }

}
