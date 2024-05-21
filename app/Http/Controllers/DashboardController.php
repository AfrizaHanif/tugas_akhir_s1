<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\Result;
use App\Models\Score;
use App\Models\SubCriteria;
use App\Models\VoteCheck;
use App\Models\VoteCriteria;
use App\Models\VoteResult;
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
        if(Auth::user()->part == 'KBU'){
            //LIST OF OFFICERS
            $officers = Officer::with('department')
            ->whereHas('department', function($query)
            {
                $query->with('part')->whereHas('part', function($query)
                {
                    $query->where('name', 'Bagian Umum');
                });
            })
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala%');})
            ->get();
            //OFFICERS WITH REJECTED SCORE
            $reject_offs = Officer::with('department')
            ->whereHas('department', function($query)
            {
                $query->with('part')->whereHas('part', function($query)
                {
                    $query->where('name', 'Bagian Umum');
                });
            })
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala%');})
            ->whereHas('score', function($query){$query->whereIn('status', ['Rejected', 'Revised']);})
            ->get();
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('department', 'user')
            ->whereHas('department', function($query)
            {
                $query->with('part')->whereHas('part', function($query)
                {
                    $query->where('name', 'Bagian Umum');
                });
            })
            ->whereDoesntHave('user', function($query)
            {
                $query->whereIn('part', ['KBU', 'KTT', 'KBPS']);
            })
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala%');})
            ->get();
            //COUNT OFFICER FROM PERFORMANCE (INPUT)
            $count_per = Performance::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query)
                {
                    $query->with('part')->whereHas('part', function($query)
                    {
                        $query->where('name', 'Bagian Umum');
                    });
                });
            })
            ->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            //COUNT OFFICER FROM PRESENCE (INPUT)
            $count_pre = Presence::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query)
                {
                    $query->with('part')->whereHas('part', function($query)
                    {
                        $query->where('name', 'Bagian Umum');
                    });
                });
            })
            ->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            //PERFORMANCE DATA
            $performances = Performance::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query)
                {
                    $query->with('part')->whereHas('part', function($query)
                    {
                        $query->where('name', 'Bagian Umum');
                    });
                });
            })
            ->get();
            //PRESENCE DATA
            $presences = Presence::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query)
                {
                    $query->with('part')->whereHas('part', function($query)
                    {
                        $query->where('name', 'Bagian Umum');
                    });
                });
            })
            ->get();
        }elseif(Auth::user()->part == 'KTT'){
            //LIST OF OFFICERS
            $officers = Officer::with('department')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala%');})
            ->whereNot('name', Auth::user()->officer->name)
            ->get();
            //OFFICERS WITH REJECTED SCORE
            $reject_offs = Officer::with('department', 'score')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala%');})
            ->whereNot('name', Auth::user()->officer->name)
            ->whereHas('score', function($query){$query->whereIn('status', ['Rejected', 'Revised']);})
            ->get();
            //LIST OF OFFICERS FOR INPUT
            $input_off = Officer::with('department', 'user')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer');})
            ->whereDoesntHave('user', function($query)
            {
                $query->whereIn('part', ['KBU', 'KTT', 'KBPS']);
            })
            ->whereNot('name', Auth::user()->officer->name)
            ->get();
            //COUNT OFFICER FROM PERFORMANCE (INPUT)
            $count_per = Performance::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query){
                    $query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');
                });
            })
            ->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            //COUNT OFFICER FROM PRESENCE (INPUT)
            $count_pre = Presence::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query){
                    $query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');
                });
            })
            ->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            //PERFORMANCE DATA
            $performances = Performance::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query){
                    $query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');
                });
            })
            ->get();
            //PRESENCE DATA
            $presences = Presence::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query){
                    $query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');
                });
            })
            ->get();
        }else{
            //LIST OF OFFICERS
            $officers = Officer::with('department')
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');})
            ->get();
            //OFFICERS WITH REJECTED SCORE
            $reject_offs = Officer::with('department', 'score')
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');})
            ->whereHas('score', function($query){$query->whereIn('status', ['Rejected', 'Revised']);})
            ->get();
            //LIST OF OFFICERS FOR INPUT
            if(Auth::user()->part == 'Admin'){
                $input_off = Officer::with('department', 'user')
                ->whereDoesntHave('department', function($query)
                {$query->where('name', 'Developer');})
                ->whereDoesntHave('user', function($query)
                {$query->whereIn('part', ['KBU', 'KTT', 'KBPS']);})
                ->get();
            }elseif(Auth::user()->part == 'KBPS'){
                $input_off = Officer::with('department', 'user')
                ->whereDoesntHave('department', function($query)
                {$query->where('name', 'Developer');})
                ->whereDoesntHave('user', function($query)
                {$query->whereIn('part', ['KBU', 'KTT', 'KBPS']);})
                ->get();
            }
            //COUNT OFFICER FROM PERFORMANCE (INPUT)
            $count_per = Performance::select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            //COUNT OFFICER FROM PRESENCE (INPUT)
            $count_pre = Presence::with('officer')->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->whereDoesntHave('officer', function($query)
            {
                $query->with('user')->whereHas('user', function($query)
                {
                    $query->whereIn('part', ['KBU', 'KTT', 'KBPS']);
                });
            })
            ->get();
            //PERFORMANCE DATA
            $performances = Performance::get();
            //PRESENCE DATA
            $presences = Presence::get();
        }
        if(Auth::user()->part == 'KBU' || Auth::user()->part == 'KTT'){
            //COUNT SUBCRITERIA FOR INPUT STATUS
            $countsub = SubCriteria::with('criteria')
            ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
            ->count();
            //LIST OF SUB CRITERIA
            $subcriterias = SubCriteria::with('criteria')
            ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
            ->get();
        }else{
            //COUNT SUBCRITERIA FOR INPUT STATUS
            $countsub = SubCriteria::with('criteria')
            ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
            ->count();
            //LIST OF SUB CRITERIA
            $subcriterias = SubCriteria::with('criteria')
            ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
            ->get();
        }
        $vote_officer = Officer::with('department') //BETA
        ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer');})
        ->get();
        $scores = Score::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $check_score = Score::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->first('status');
        $periods = Period::get();
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();
        $vote_criterias = VoteCriteria::get();
        $check = VoteCheck::select('id_period', 'id_officer')->groupBy('id_period', 'id_officer')->get();
        $vote_check = VoteCheck::get();
        $latest_best = VoteResult::with('officer', 'period')->latest()->first();
        $latest_top3 = Score::with('officer', 'period')
        ->whereDoesntHave('period', function($query)
            {$query->where('status', 'Scoring');})
        ->orderBy('final_score', 'DESC')
        ->offset(0)->limit(3)->get();
        $voteresults = Result::with('officer', 'period')
        ->orderBy('count', 'DESC')
        ->whereHas('officer', function ($query) {
            $query->with('score')
            ->whereHas('score', function ($query) {$query->orderBy('final_score', 'DESC');});
        })
        ->offset(0)->limit(1)->get();
        $scoreresults = Score::with('officer', 'period')->orderBy('final_score', 'DESC')->offset(0)->limit(3)->get();
        //dd($scoreresults);

        return view('Pages.Admin.dashboard', compact('officers', 'reject_offs', 'input_off', 'vote_officer', 'count_per', 'count_pre', 'performances', 'presences', 'scores', 'check_score', 'latest_per', 'vote_criterias', 'check', 'vote_check', 'latest_best', 'latest_top3', 'countsub', 'subcriterias', 'periods', 'voteresults', 'scoreresults'));
    }

    public function officer(){
        $latest_best = VoteResult::with('officer', 'period')->latest()->first();
        $vote_criterias = VoteCriteria::get();
        $vote_check = VoteCheck::get();
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();
        $periods = Period::get();
        $voteresults = Result::with('officer', 'period')
        ->orderBy('count', 'DESC')
        ->whereHas('officer', function ($query) {
            $query->with('score')
            ->whereHas('score', function ($query) {
                $query->orderBy('final_score', 'DESC');
            });
        })
        ->offset(0)->limit(1)->get();

        return view('Pages.Officer.dashboard', compact('latest_best', 'vote_criterias', 'vote_check', 'latest_per', 'periods', 'voteresults'));
    }
}
