<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\HistoryResult;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $latest_best = HistoryResult::orderBy('id', 'DESC')->latest()->first();

        return view('Pages.Home.index', compact('latest_best'));
    }
}
