<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\Result;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('status', 'Finish')->get();
        $per_years = Period::orderBy('id_period', 'ASC')->select('year')->groupBy('year')->get();

        return view('Pages.Admin.report', compact('periods','per_years'));
    }

    public function officers()
    {
        $parts = Part::get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();
        $file = 'RPT-Pegawai.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.officer', compact('parts','officers'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function input($period)
    {
        $year = Period::where('id_period', $period)->first()->year;
        $first_input = Performance::with('subcriteria')->where('id_period', $period);
        $last_input = Presence::with('subcriteria')->where('id_period', $period)->union($first_input)->get();
        $inputs = $last_input;
        $file = 'RPT-Input-'.$year.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.input', compact('year','inputs'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function analysis($period)
    {
        $year = Period::where('id_period', $period)->first()->year;

        $file = 'RPT-Analysis-'.$year.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.analysis', compact('year'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function result($period)
    {
        $year = Period::where('id_period', $period)->first()->year;
        $results = Result::with('officer')->where('id_period', $period)->orderBy('final_score', 'DESC')->get();
        $file = 'RPT-Result-'.$year.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.input', compact('year','results'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }
}
