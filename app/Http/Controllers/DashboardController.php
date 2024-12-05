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
    public function admin(){ //KEPEGAWAIAN ONLY
        //AUTO CREATE PERIOD (DISABLE IF NOT NEEDED)
        //Artisan::call('app:create-period');

        //GET DATA
        $periods = Period::get(); //GET PERIODS
        $inputs = Input::get(); //GET INPUTS
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        //->where('is_lead', 'No')
        ->get(); //GET OFFICERS WITHOUT KEPALA BPS AND DEVELOPER
        $input_lists = Input::with('officer')
        ->select('id_period', 'id_officer', 'status')
        ->groupBy('id_period', 'id_officer', 'status')
        ->whereHas('officer', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->get(); //GET INPUT LISTS FOR CHECK CONVERT STATUS
        $scores = Score::with('officer')
        ->select('id_period', 'id_officer', 'status')
        ->groupBy('id_period', 'id_officer', 'status')
        ->whereHas('officer', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->get(); //GET SCORES
        $count = Input::with('officer')
        ->select('id_period', 'id_officer', 'status')
        ->groupBy('id_period', 'id_officer', 'status')
        ->get(); //COUNT INPUTS
        $countsub = Criteria::count(); //COUNT CRITERIAS
        $subcriterias = Criteria::get(); //GET CRITERIAS
        $status = Input::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get(); //GET STATUS IN INPUTS

        //GET DATA PER PART OF ACCOUNT
        if(Auth::user()->part == 'Admin'){
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('position')
            ->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            })
            //->where('is_lead', 'No')
            ->get(); //GET OFFICERS FOR INPUT CARD
            //dd($input_off);
        }elseif(Auth::user()->part == 'KBPS'){
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('position')
            ->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            })
            //->where('is_lead', 'No')
            ->get(); //GET OFFICERS FOR INPUT CARD
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
        ->get(); //GET OFFICERS WHO HAS REJECTED SCORES
        $progress_offs = Officer::with('position', 'input')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('input', function($query){
            $query->whereIn('status', ['Pending', 'Fixed', 'Not Converted']);
        })
        //->where('is_lead', 'No')
        ->get(); //GET OFFICERS TO PREPARE FOR VERIFYING
        $acc_offs = Officer::with('position', 'input')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('input', function($query){
            $query->whereIn('status', ['Pending', 'In Review', 'Fixed']);
        })
        //->where('is_lead', 'No')
        ->get(); //GET OFFICERS TO LOOK WHICH OFFICER THAT ARE NOT BEING VERIFIED
        $check_score = Score::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->first('status'); //NOT USED (OPT: DELETE)
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD
        $latest_best = HistoryResult::orderBy('id', 'DESC')->latest()->first(); //CURRENT WINNER
        $latest_top3 = HistoryScore::orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->latest()->get(); //CURRENT TOP 3 BEST SCORES
        $history_prd = HistoryScore::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'DESC')->first(); //GET FINISHED PERIOD
        $voteresults = HistoryResult::orderBy('id_period', 'ASC')->get(); //GET PREVIOUS RESULTS
        $scoreresults = HistoryScore::orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->get(); //GET OLD SCORES RESULT
        //dd($scoreresults);

        //RETURN TO VIEW
        return view('Pages.Admin.dashboard', compact('officers', 'reject_offs', 'progress_offs', 'acc_offs', 'input_off', 'count', 'inputs', 'scores', 'check_score', 'latest_per', 'latest_best', 'latest_top3', 'history_prd', 'countsub', 'subcriterias', 'periods', 'voteresults', 'scoreresults', 'input_lists', 'status'));
    }

    public function officer(Request $request){
        //GET PERIODS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD
        $history_per = HistoryInput::select('id_period', 'period_name', 'period_month', 'period_year')->groupBy('id_period', 'period_name', 'period_month', 'period_year')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'DESC')->get(); //GET PREVIOUS PERIOD
        $hper_latest = HistoryInput::select('id_period', 'period_name', 'period_month', 'period_year')->groupBy('id_period', 'period_name', 'period_month', 'period_year')->orderBy('period_year', 'DESC')->orderBy('period_num_month', 'DESC')->latest()->first(); //GET FINISHED PERIOD
        $hper_year = HistoryInput::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->orderBy('period_year', 'DESC')->latest()->first(); //GET PREVIOUS PERIOD IN YEAR
        $hscore_year = HistoryScore::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->orderBy('period_year', 'DESC')->get(); //GET OLD SCORE FROM PREVIOUS PERIOD IN YEAR

        //GET LATEST DATA
        $periods = Period::get(); //GET PERIODS
        $criterias = Criteria::get(); //GET CRITERIAS
        $inputs = Input::get(); //GET INPUTS

        //GET HISTORY DATA
        $hcriterias = HistoryInput::select('id_criteria', 'criteria_name', 'id_period', 'unit')->groupBy('id_criteria', 'criteria_name', 'id_period', 'unit')->get(); //GET PREVIOUS CRITERIAS FROM OLD INPUTS
        $histories = HistoryInput::get(); //GET OLD INPUTS
        $hscores = HistoryScore::orderBy('id_period', 'ASC')->get(); //GET OLD SCORES
        //dd($hresults);

        //TEST DATA FOR CHART
        $search = $request->year; //GET SELECTED YEAR
        $chart = HistoryScore::where('id_officer', Auth::user()->nip)->where('period_year','like',"%".$search."%")->select('period_name', 'final_score')->groupBy('period_name', 'final_score')->orderBy('period_year', 'ASC')->orderBy('period_num_month', 'ASC')->pluck('final_score', 'period_name'); //GET FINAL SCORE AND PERIOD NAME FOR CHART

        $c_labels = $chart->keys(); //FOR PERIOD NAME
        $c_datas = $chart->values(); //FOR FINAL SCORE

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
        $officers = Officer::get(); //GET OFFICERS
        $users = User::get(); //GET USERS
        $messages = Message::get(); //GET MESSAGES

        //RETURN TO VIEW
        return view('Pages.Developer.dashboard', compact('officers', 'users', 'messages'));
    }
}
