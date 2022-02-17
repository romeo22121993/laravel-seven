<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    //

    /**
     * Function for Hindi language
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Hindi(){
        session()->get('language');
        session()->forget('language');
        Session::put('language', 'hindi');
        return redirect()->back();
    }

    /**
     * Function for English Language
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function English(){
        session()->get('language');
        session()->forget('language');
        Session::put('language','english');
        return redirect()->back();
    }

}
