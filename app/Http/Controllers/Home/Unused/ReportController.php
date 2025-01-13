<?php

namespace App\Http\Controllers\Home\Unused;

use App\Http\Controllers\Controller;
use App\Models\HistoryInput;
use App\Models\HistoryScore;
use App\Models\Employee;
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
        $h_periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        $h_years = HistoryInput::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->get();
        $h_team_years = HistoryInput::select('id_sub_team', 'sub_team_1_name', 'period_year')->groupBy('id_sub_team', 'sub_team_1_name', 'period_year')->orderBy('period_year', 'ASC')->get();
        $h_months = HistoryInput::select('period_year', 'period_month', 'period_num_month')->groupBy('period_year', 'period_month', 'period_num_month')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'ASC')->get();
        $h_subteams = HistoryScore::select('id_sub_team', 'sub_team_1_name')->groupBy('id_sub_team', 'sub_team_1_name')->whereNotIn('sub_team_1_name', ['Pimpinan BPS', 'Developer'])->get();
        $h_employees = HistoryInput::select('id_employee', 'employee_name')->groupBy('id_employee',  'employee_name')->get();
        $h_scores = HistoryScore::select('id_period', 'period_name', 'period_year', 'period_month', 'period_num_month', 'id_sub_team', 'sub_team_1_name')->groupBy('id_period', 'period_name', 'period_year', 'period_month', 'period_num_month', 'id_sub_team', 'sub_team_1_name')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'ASC')->get();

        //RETURN TO VIEW
        return view('Pages.Home.report', compact('h_periods', 'h_years', 'h_team_years', 'h_months', 'h_subteams', 'h_employees', 'h_scores'));
    }
}
