<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\HistoryResult;
use App\Models\HistoryScore;
use App\Models\Input;
use App\Models\Officer;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\Result;
use App\Models\Score;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function admin(){
        //Artisan::call('app:create-period');
        //GET DATA
        //LIST OF OFFICERS
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query)
        {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');})
        //->where('is_lead', 'No')
        ->get();
        //OFFICERS WITH REJECTED SCORE
        $reject_offs = Officer::with('department', 'score')
        ->whereDoesntHave('department', function($query)
        {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');})
        ->whereHas('score', function($query){$query->whereIn('status', ['Rejected', 'Revised']);})
        ->where('is_lead', 'No')
        ->get();
        if(Auth::user()->part == 'Admin'){
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('department', 'user')
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');})
            ->where('is_lead', 'No')
            ->get();
        }elseif(Auth::user()->part == 'KBPS'){
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('department', 'user')
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer');})
            ->where('is_lead', 'No')
            ->get();
        }
        //COUNT OFFICER FROM PRESENCE (INPUT)
        $count = Input::with('officer')
        ->select('id_period', 'id_officer', 'status')
        ->groupBy('id_period', 'id_officer', 'status')
        ->get();
        //INPUT DATA
        $inputs = Input::get();

        //COUNT SUBCRITERIA FOR INPUT STATUS
        $countsub = Criteria::count();
        //LIST OF SUB CRITERIA
        $subcriterias = Criteria::get();

        $scores = Score::with('officer')
        ->select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->whereHas('officer', function($query)
        {$query->where('is_lead', 'No');})->get();
        $check_score = Score::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->first('status');
        $periods = Period::get();
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();
        $latest_best = HistoryResult::orderBy('id', 'DESC')->latest()->first();
        $latest_top3 = HistoryScore::orderBy('id', 'ASC')->latest()->get();
        $history_prd = HistoryScore::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'DESC')->first();
        $voteresults = HistoryResult::orderBy('id_period', 'ASC')->get();
        $scoreresults = HistoryScore::orderBy('final_score', 'DESC')->get();
       //dd($scoreresults);

        return view('Pages.Admin.dashboard', compact('officers', 'reject_offs', 'input_off', 'count', 'inputs', 'scores', 'check_score', 'latest_per', 'latest_best', 'latest_top3', 'history_prd', 'countsub', 'subcriterias', 'periods', 'voteresults', 'scoreresults'));
    }

    public function officer(){
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();
        $periods = Period::get();
        $results = Result::with('officer', 'period')
        ->orderBy('count', 'DESC')
        ->whereHas('officer', function ($query) {
            $query->with('score')
            ->whereHas('score', function ($query) {
                $query->orderBy('final_score', 'DESC');
            });
        })
        ->offset(0)->limit(1)->get();

        return view('Pages.Officer.dashboard', compact('latest_per', 'periods', 'results'));
    }

    public function developer(){
        return view('Pages.Developer.dashboard');
    }
}
