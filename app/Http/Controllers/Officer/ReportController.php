<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\HistoryInput;
use App\Models\HistoryScore;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        //GET DATA
        $h_periods = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.id_period', 'periods.name')->groupBy('periods.id_period', 'periods.name')->orderBy('periods.id_period', 'ASC')->get();
        $h_years = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.year')->groupBy('periods.year')->orderBy('periods.year', 'ASC')->get();
        $h_team_years = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('history_inputs.id_sub_team', 'history_inputs.sub_team_1_name', 'periods.year')->groupBy('history_inputs.id_sub_team', 'history_inputs.sub_team_1_name', 'periods.year')->orderBy('periods.year', 'ASC')->get();
        $h_months = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.year', 'periods.month', 'periods.num_month')->groupBy('periods.year', 'periods.month', 'periods.num_month')->orderBy('periods.year', 'DESC')->orderBy('periods.num_month', 'ASC')->get();
        $h_subteams = HistoryScore::select('id_sub_team', 'sub_team_1_name')->groupBy('id_sub_team', 'sub_team_1_name')->whereNotIn('sub_team_1_name', ['Pimpinan BPS', 'Developer'])->get();
        $h_employees = HistoryInput::select('id_employee', 'employee_name')->groupBy('id_employee', 'employee_name')->get();
        $h_scores = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'periods.year', 'periods.month', 'periods.num_month', 'history_scores.id_sub_team', 'history_scores.sub_team_1_name')->groupBy('periods.id_period', 'periods.name', 'periods.year', 'periods.month', 'periods.num_month', 'history_scores.id_sub_team', 'history_scores.sub_team_1_name')->orderBy('periods.year', 'DESC')->orderBy('periods.year', 'DESC')->orderBy('periods.num_month', 'ASC')->get();
        /*
        $employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('status', 'Active')
        ->get();
        */
        //$check_teams = HistoryScore::get();

        //UNUSED CODE
        if(Auth::user()->part != "Karyawan"){

        }else{

        }

        //RETURN TO VIEW
        return view('Pages.Employee.report', compact('h_periods', 'h_years', 'h_team_years', 'h_months', 'h_subteams', 'h_employees', 'h_scores'));
    }

    public function score($month, $year)
    {
        //GET DATA
        $periods = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->groupBy('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->orderBy('periods.id_period', 'ASC')->where('periods.month', $month)->where('periods.year', $year)->first();
        $inputs = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->where('history_inputs.id_employee', Auth::user()->id_employee)->where('periods.id_period', $periods->id_period)->get();
        $detail = Auth::user();
        $summary = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->where('history_scores.id_employee', Auth::user()->id_employee)->where('periods.id_period', $periods->id_period)->first();

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Laporan',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Laporan Hasil Analisis SAW Berhasil Dibuat ('.$periods->period->name.') ('.Auth::user()->employee->name.')',
        ]);

        //CREATE A REPORT
        $file = 'RPT-Score-'.$periods->id_period.'-'.Auth::user()->id_employee.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.score', compact('periods','inputs', 'detail', 'summary'))
        ->setPaper('a4')
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }
}
