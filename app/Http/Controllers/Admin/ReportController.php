<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Position;
use App\Models\HistoryInput;
use App\Models\HistoryPerformance;
use App\Models\HistoryPresence;
use App\Models\HistoryScore;
use App\Models\Input;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\Result;
use App\Models\SubCriteria;
use App\Models\SubTeam;
use App\Models\Team;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        //GET DATA
        $h_periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        $h_years = HistoryInput::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->get();
        $h_team_years = HistoryInput::select('id_sub_team', 'officer_team', 'period_year')->groupBy('id_sub_team', 'officer_team', 'period_year')->orderBy('period_year', 'ASC')->get();
        $h_months = HistoryInput::select('period_year', 'period_month', 'period_num_month')->groupBy('period_year', 'period_month', 'period_num_month')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'ASC')->get();
        $h_subteams = HistoryScore::select('id_sub_team', 'officer_team')->groupBy('id_sub_team', 'officer_team')->whereNotIn('officer_team', ['Pimpinan BPS', 'Developer'])->get();
        $h_officers = HistoryInput::select('id_officer', 'officer_nip', 'officer_name')->groupBy('id_officer', 'officer_nip', 'officer_name')->get();
        $h_scores = HistoryScore::select('id_period', 'period_name', 'period_year', 'period_month', 'period_num_month', 'id_sub_team', 'officer_team')->groupBy('id_period', 'period_name', 'period_year', 'period_month', 'period_num_month', 'id_sub_team', 'officer_team')->orderBy('period_year', 'DESC')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'ASC')->get();
        /*
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();
        */
        //$check_teams = HistoryScore::get();

        //UNUSED CODE
        if(Auth::user()->part != "Pegawai"){

        }else{

        }

        //RETURN TO VIEW
        return view('Pages.Admin.report', compact('h_periods', 'h_years', 'h_team_years', 'h_months', 'h_subteams', 'h_officers', 'h_scores'));
    }
}
