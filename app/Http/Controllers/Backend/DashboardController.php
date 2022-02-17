<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;


class DashboardController extends Controller
{

    public function __construct(StatefulGuard $guard)
    {
        $this->middleware('auth');
    }

    public function index() {
        $id   = Auth::user()->id;
        $user = User::find($id);

        if ( $user->user_role == 'admin' ) {
            return redirect('/admin/profile');
        }
        else {
            return redirect('/user/profile');
        }
    }

}
