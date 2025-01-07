<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\HistoryScore;
use App\Models\Employee;
use App\Models\Period;
use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function index()
    {
        //GET DATA
        $periods = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name')->orderBy('periods.year', 'ASC')->orderBy('periods.num_month', 'ASC')->groupBy('periods.id_period', 'periods.name')->orderBy('periods.id_period', 'ASC')->get();
        $scores = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->orderBy('final_score', 'DESC')->orderBy('history_scores.second_score', 'DESC')->get();
        $employees = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'history_scores.id_employee', 'history_scores.employee_name', 'history_scores.employee_position')->groupBy('periods.id_period', 'periods.name', 'history_scores.id_employee', 'history_scores.employee_name', 'history_scores.employee_position')->get();
        //$check = Score::orderBy('final_score', 'DESC')->offset(0)->limit(3)->get();

        //RETURN TO VIEW
        return view('Pages.Home.score', compact('periods', 'scores', 'employees'));
    }
}
