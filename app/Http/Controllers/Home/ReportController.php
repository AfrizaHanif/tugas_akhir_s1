<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Result;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //GET DATA
        $periods = Period::orderBy('id_period', 'ASC')->whereIn('status', ['Voting', 'Finished'])->get();
        $per_years = Period::orderBy('id_period', 'ASC')->select('year')->groupBy('year')->get();
        $officers = Officer::with('position', 'user')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('user', function($query){$query->whereIn('part', ['KBU', 'KTT', 'KBPS']);})
        ->get();

        //RETURN TO VIEW
        return view('Pages.Home.report', compact('periods','per_years','officers'));
    }
}
