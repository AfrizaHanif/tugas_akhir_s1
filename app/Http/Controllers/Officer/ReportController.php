<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\HistoryInput;
use App\Models\HistoryScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        //GET DATA
        $h_periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        $h_years = HistoryInput::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->get();
        $h_team_years = HistoryInput::select('id_sub_team', 'sub_team_1_name', 'period_year')->groupBy('id_sub_team', 'sub_team_1_name', 'period_year')->orderBy('period_year', 'ASC')->get();
        $h_months = HistoryInput::select('period_year', 'period_month', 'period_num_month')->groupBy('period_year', 'period_month', 'period_num_month')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'ASC')->get();
        $h_subteams = HistoryScore::select('id_sub_team', 'sub_team_1_name')->groupBy('id_sub_team', 'sub_team_1_name')->whereNotIn('sub_team_1_name', ['Pimpinan BPS', 'Developer'])->get();
        $h_officers = HistoryInput::select('id_officer', 'officer_name')->groupBy('id_officer', 'officer_name')->get();
        $h_scores = HistoryScore::select('id_period', 'period_name', 'period_year', 'period_month', 'period_num_month', 'id_sub_team', 'sub_team_1_name')->groupBy('id_period', 'period_name', 'period_year', 'period_month', 'period_num_month', 'id_sub_team', 'sub_team_1_name')->orderBy('period_year', 'DESC')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'ASC')->get();
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
        return view('Pages.Officer.report', compact('h_periods', 'h_years', 'h_team_years', 'h_months', 'h_subteams', 'h_officers', 'h_scores'));
    }

    public function score($month, $year)
    {
        //GET DATA
        $periods = HistoryScore::select('id_period', 'period_name', 'period_month', 'period_year')->groupBy('id_period', 'period_name', 'period_month', 'period_year')->orderBy('id_period', 'ASC')->where('period_month', $month)->where('period_year', $year)->first();
        $inputs = HistoryInput::where('id_officer', Auth::user()->nip)->where('id_period', $periods->id_period)->get();
        $detail = Auth::user();

        //CREATE A REPORT
        $file = 'RPT-Score-'.$periods->id_period.'-'.Auth::user()->nip.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.score', compact('periods','inputs', 'detail'))
        ->setPaper('a4')
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }
}
