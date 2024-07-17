<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Department;
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
        $periods = Period::orderBy('id_period', 'ASC')->whereIn('status', ['Voting', 'Finished'])->get();
        $per_years = Period::orderBy('id_period', 'ASC')->select('year')->groupBy('year')->get();
        $officers = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('user', function($query){$query->whereIn('part', ['KBU', 'KTT', 'KBPS']);})
        ->get();

        //UNUSED CODE
        if(Auth::user()->part != "Pegawai"){

        }else{

        }

        //RETURN TO VIEW
        return view('Pages.Admin.report', compact('periods','per_years','officers'));
    }
}
