<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\HistoryInput;
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
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin(){
        //AUTO CREATE PERIOD (DISABLE IF NOT NEEDED)
        //Artisan::call('app:create-period');

        //GET DATA
        $periods = Period::get();
        $inputs = Input::get();
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        //->where('is_lead', 'No')
        ->get();
        $input_lists = Input::with('officer')
        ->select('id_period', 'id_officer', 'status')
        ->groupBy('id_period', 'id_officer', 'status')
        ->whereHas('officer', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->get();
        $scores = Score::with('officer')
        ->select('id_period', 'id_officer', 'status')
        ->groupBy('id_period', 'id_officer', 'status')
        ->whereHas('officer', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->get();
        $count = Input::with('officer')
        ->select('id_period', 'id_officer', 'status')
        ->groupBy('id_period', 'id_officer', 'status')
        ->get();
        $countsub = Criteria::count();
        $subcriterias = Criteria::get();
        $status = Input::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();

        //GET DATA PER PART OF ACCOUNT
        if(Auth::user()->part == 'Admin'){
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('position')
            ->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            })
            //->where('is_lead', 'No')
            ->get();
            //dd($input_off);
        }elseif(Auth::user()->part == 'KBPS'){
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('position')
            ->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            })
            //->where('is_lead', 'No')
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
        //->where('is_lead', 'No')
        ->get();
        $progress_offs = Officer::with('position', 'input')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('input', function($query){
            $query->whereIn('status', ['Pending', 'Fixed']);
        })
        //->where('is_lead', 'No')
        ->get();
        $acc_offs = Officer::with('position', 'input')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('input', function($query){
            $query->whereIn('status', ['Pending', 'In Review', 'Fixed']);
        })
        //->where('is_lead', 'No')
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
        return view('Pages.Admin.dashboard', compact('officers', 'reject_offs', 'progress_offs', 'acc_offs', 'input_off', 'count', 'inputs', 'scores', 'check_score', 'latest_per', 'latest_best', 'latest_top3', 'history_prd', 'countsub', 'subcriterias', 'periods', 'voteresults', 'scoreresults', 'input_lists', 'status'));
    }

    public function officer(Request $request){
        //GET PERIODS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        $history_per = HistoryInput::select('id_period', 'period_name', 'period_month', 'period_year')->groupBy('id_period', 'period_name', 'period_month', 'period_year')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'DESC')->get();
        $hper_latest = HistoryInput::select('id_period', 'period_name', 'period_month', 'period_year')->groupBy('id_period', 'period_name', 'period_month', 'period_year')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'DESC')->latest()->first();
        $hper_year = HistoryInput::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->orderBy('period_year', 'DESC')->latest()->first();
        $hscore_year = HistoryScore::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->orderBy('period_year', 'DESC')->get();

        //GET LATEST DATA
        $periods = Period::get();
        $criterias = Criteria::get();
        $inputs = Input::get();

        //GET HISTORY DATA
        $hcriterias = HistoryInput::select('id_criteria', 'criteria_name', 'id_period', 'unit')->groupBy('id_criteria', 'criteria_name', 'id_period', 'unit')->get();
        $histories = HistoryInput::get();
        $hscores = HistoryScore::orderBy('id_period', 'ASC')->get();
        //dd($hresults);

        //TEST DATA FOR CHART
        $search = $request->year;
        $chart = HistoryScore::where('id_officer', Auth::user()->nip)->where('period_year','like',"%".$search."%")->select('period_name', 'final_score')->groupBy('period_name', 'final_score')->orderBy('period_year', 'ASC')->orderBy('period_num_month', 'ASC')->pluck('final_score', 'period_name');

        $c_labels = $chart->keys();
        $c_datas = $chart->values();

        /*
        $results = Result::with('officer', 'period')
        ->orderBy('count', 'DESC')
        ->whereHas('officer', function ($query) {
            $query->with('score')
            ->whereHas('score', function ($query) {
                $query->orderBy('final_score', 'DESC');
            });
        })
        ->offset(0)->limit(1)->get();
        */

        return view('Pages.Officer.dashboard', compact('latest_per', 'history_per', 'periods', 'criterias', 'inputs', 'histories', 'hcriterias', 'hscores', 'hper_latest', 'hper_year', 'c_labels', 'c_datas', 'hscore_year'));
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
