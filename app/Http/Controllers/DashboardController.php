<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(){
        return view('Pages.Admin.dashboard');
    }

    public function officer(){
        return view('Pages.Officer.dashboard');
    }
}
