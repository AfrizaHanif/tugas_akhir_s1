<?php

namespace App\Http\Controllers\Employee;

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
        $periods = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name')->orderBy('periods.year', 'ASC')->orderBy('periods.num_month', 'ASC')->groupBy('periods.id_period', 'periods.name')->orderBy('periods.id_period', 'ASC')->get();
        $scores = HistoryScore::orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->get();
        $employees = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'history_scores.id_employee', 'history_scores.employee_name', 'history_scores.employee_position')->groupBy('periods.id_period', 'periods.name', 'history_scores.id_employee', 'history_scores.employee_name', 'history_scores.employee_position')->get();

        return view('Pages.Employee.score', compact('periods', 'scores', 'employees'));
    }
}
