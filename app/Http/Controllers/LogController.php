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
        $logs = Log::orderBy('created_at', 'DESC')->get();

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin" || Auth::user()->part == "KBPS"){
            return view('Pages.Admin.log', compact('logs'));
        }elseif(Auth::user()->part == "Dev"){
            return view('Pages.Developer.log', compact('logs'));
        }else{
            return view('Pages.Officer.log', compact('logs'));
        }
    }
}
