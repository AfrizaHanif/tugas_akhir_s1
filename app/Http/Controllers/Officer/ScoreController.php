<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('progress_status', 'Voting')->orWhere('progress_status', 'Finished')->get();
        $scores = Score::with('officer')->orderBy('final_score', 'DESC')->get();
        $officers = Officer::with('position')->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})->get();
        $check = Score::orderBy('final_score', 'DESC')->offset(0)->limit(3)->get();

        return view('Pages.Officer.score', compact('periods', 'scores', 'officers', 'check'));
    }
}
