<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index()
    {
        //GET DATA
        $logs = Log::get();

        //RETURN TO VIEW
<<<<<<< HEAD
        if(Auth::user()->part == "Admin" || Auth::user()->part == "KBPS"){
=======
        if(Auth::user()->part == "Admin"){
>>>>>>> 72a5ec8aae76a27a257191e2b80824d87045dc00
            return view('Pages.Admin.log', compact('logs'));
        }elseif(Auth::user()->part == "Dev"){
            return view('Pages.Developer.log', compact('logs'));
        }else{
            return view('Pages.Officer.log', compact('logs'));
        }
    }
}
