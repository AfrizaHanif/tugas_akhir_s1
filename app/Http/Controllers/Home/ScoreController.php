<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('status', 'Voting')->orWhere('status', 'Finished')->get();
        $scores = Score::with('officer')->orderBy('final_score', 'DESC')->get();
        $officers = Officer::with('department')->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})->get();
        $check = Score::orderBy('final_score', 'DESC')->offset(0)->limit(3)->get();
        
        return view('Pages.Home.score', compact('periods', 'scores', 'officers', 'check'));
    }
}
