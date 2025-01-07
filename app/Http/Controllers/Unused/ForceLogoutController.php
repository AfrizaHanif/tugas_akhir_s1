<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ForceLogoutController extends Controller
{
    static function check(){
        //dd(Auth::check() && Auth::user()->force_logout == true);
        User::where('id_user', Auth::user()->id_user)->update(['force_logout' => false]);
        Session::flush();
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
