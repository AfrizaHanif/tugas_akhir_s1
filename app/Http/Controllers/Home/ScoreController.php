<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\HistoryScore;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function index()
    {
        //GET DATA
        $periods = HistoryScore::select('id_period', 'period_name')->orderBy('period_year', 'ASC')->orderBy('period_num_month', 'ASC')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        $scores = HistoryScore::orderBy('final_score', 'DESC')->get();
        $officers = HistoryScore::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_position')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_position')->get();
        //$check = Score::orderBy('final_score', 'DESC')->offset(0)->limit(3)->get();

        //RETURN TO VIEW
        return view('Pages.Home.score', compact('periods', 'scores', 'officers'));
    }
}
