<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\HistoryResult;
use App\Models\HistoryScore;
use App\Models\Input;
use App\Models\Message;
use App\Models\Officer;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\Result;
use App\Models\Score;
use App\Models\SubCriteria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function admin(){
        //AUTO CREATE PERIOD (DISABLE IF NOT NEEDED)
        //Artisan::call('app:create-period');

        //GET DATA
        $periods = Period::get();
        $inputs = Input::get();
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query)
        {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');})
        //->where('is_lead', 'No')
        ->get();
        $input_lists = Input::with('officer')
        ->select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->whereHas('officer', function($query)
        {$query->where('is_lead', 'No');})->get();
        $scores = Score::with('officer')
        ->select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->whereHas('officer', function($query)
        {$query->where('is_lead', 'No');})->get();
        $count = Input::with('officer')
        ->select('id_period', 'id_officer', 'status')
        ->groupBy('id_period', 'id_officer', 'status')
        ->get();
        $countsub = Criteria::count();
        $subcriterias = Criteria::get();

        //GET DATA PER PART OF ACCOUNT
        if(Auth::user()->part == 'Admin'){
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('position')
            ->whereDoesntHave('position', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');})
            ->where('is_lead', 'No')
            ->get();
            //dd($input_off);
        }elseif(Auth::user()->part == 'KBPS'){
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('position')
            ->whereDoesntHave('position', function($query)
            {$query->where('name', 'Developer');})
            ->where('is_lead', 'No')
            ->get();
        }

        //GET DATA FOR CARDS
        $reject_offs = Officer::with('position', 'score')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('score', function($query){
            $query->whereIn('status', ['Rejected', 'Revised']);
        })
        ->where('is_lead', 'No')
        ->get();
        $progress_offs = Officer::with('position', 'input')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('input', function($query){
            $query->whereIn('status', ['Pending', 'Fixed']);
        })
        ->where('is_lead', 'No')
        ->get();
        $acc_offs = Officer::with('position', 'input')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('input', function($query){
            $query->whereIn('status', ['Pending', 'In Review', 'Fixed']);
        })
        ->where('is_lead', 'No')
        ->get();
        $check_score = Score::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->first('status');
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        $latest_best = HistoryResult::orderBy('id', 'DESC')->latest()->first();
        $latest_top3 = HistoryScore::orderBy('final_score', 'DESC')->latest()->get();
        $history_prd = HistoryScore::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'DESC')->first();
        $voteresults = HistoryResult::orderBy('id_period', 'ASC')->get();
        $scoreresults = HistoryScore::orderBy('final_score', 'DESC')->get();
        //dd($scoreresults);

        //RETURN TO VIEW
        return view('Pages.Admin.dashboard', compact('officers', 'reject_offs', 'progress_offs', 'acc_offs', 'input_off', 'count', 'inputs', 'scores', 'check_score', 'latest_per', 'latest_best', 'latest_top3', 'history_prd', 'countsub', 'subcriterias', 'periods', 'voteresults', 'scoreresults', 'input_lists'));
    }

    //OPTIONAL: DELETE
    public function officer(){
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
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
        //GET DATA
        $officers = Officer::get();
        $users = User::get();
        $messages = Message::get();

        //RETURN TO VIEW
        return view('Pages.Developer.dashboard', compact('officers', 'users', 'messages'));
    }
}
