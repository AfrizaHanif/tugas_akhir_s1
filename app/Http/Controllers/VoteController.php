<?php

namespace App\Http\Controllers;

use App\Models\HistoryVote;
use App\Models\HistoryVoteCheck;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
use App\Models\SubCriteria;
use App\Models\Vote;
use App\Models\VoteCheck;
use App\Models\VoteCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('status', 'Finished')->get();
        $latest_per = Period::where('status', 'Voting')->latest()->first();

        $criterias = VoteCriteria::get();
        $votes = Vote::with('officer')
        ->whereNot('id_officer', Auth::user()->officer->id_officer)
        ->get();
        $checks = VoteCheck::with('officer')->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();

        if(Auth::user()->part == 'KBU'){
            $fil_offs = Officer::with('department')
            ->whereHas('department', function($query)
            {
                $query->with('part')->whereHas('part', function($query)
                {
                    $query->where('name', 'Bagian Umum');
                });
            })
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereNot('name', Auth::user()->officer->name)
            ->get();
        }elseif(Auth::user()->part == 'KTT'){
            $fil_offs = Officer::with('department')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereNot('name', Auth::user()->officer->name)
            ->get();
        }elseif(Auth::user()->part == 'Admin'){
            $fil_offs = Officer::with('department')
            //->whereHas('part', function($query){$query->where('name', 'LIKE', 'Kepegawaian');})
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%Kepegawaian%');})
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereNot('name', Auth::user()->officer->name)
            ->get();
        }else{
            $fil_offs = Officer::with('department')
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereNot('name', Auth::user()->officer->name)
            ->get();
        }

        if(Auth::user()->part != "Pegawai"){
            return view('Pages.Admin.vote', compact('periods', 'votes', 'officers', 'checks', 'fil_offs', 'criterias', 'latest_per'));
        }else{
            return view('Pages.Officer.vote', compact('periods', 'votes', 'officers', 'checks', 'fil_offs', 'criterias', 'latest_per'));
        }
    }

    public function history($period)
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('status', 'Finished')->get();
        //$prd_select = Period::where('id_period', $period)->orderBy('id_period', 'ASC')->where('status', 'Finished')->first();
        $prd_select = HistoryVote::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->where('id_period', $period)->first();
        //dd($prd_select);

        $hcriterias = HistoryVote::select('id_vote_criteria', 'vote_criteria_name')->groupBy('id_vote_criteria', 'vote_criteria_name')->get();
        $hvotes = HistoryVote::get();
        $hchecks = HistoryVoteCheck::get();

        if(Auth::user()->part != "Pegawai"){
            return view('Pages.Admin.vote', compact('periods', 'hvotes', 'hchecks', 'hcriterias', 'prd_select'));
        }else{
            return view('Pages.Officer.vote', compact('periods', 'hvotes', 'hchecks', 'hcriterias', 'prd_select'));
        }
    }

    public function select($period, $officer, $criteria)
    {
        //GET DATA FOR REDIRECT
        $redirect = '';
        $latest = VoteCriteria::latest()->first();
        //dd($criteria != $latest->id_vote_criteria);
        if($criteria != $latest->id_vote_criteria){
            $redirect = VoteCriteria::where('id_vote_criteria', '>', $criteria)->min('id_vote_criteria');
            //dd($redirect);
        }else{
            $redirect = $criteria;
            //dd($redirect);
        }

        //INCREMENT DATA
        Vote::where('id_period', $period)->where('id_officer', $officer)->where('id_vote_criteria', $criteria)->increment('votes');

        //INSERT VOTE CHECK
        $str_officer = substr(Auth::user()->officer->id_officer, 4);
        $str_year = substr($period, -5);
        $id_vote_check = "CHK-".$str_year.'-'.$str_officer;

        VoteCheck::insert([
            //'id_vote_check'=>$id_vote_check,
            'id_officer'=>Auth::user()->officer->id_officer,
            'id_period'=>$period,
            'id_vote_criteria'=>$criteria,
            'officer_selected'=>$officer,
        ]);

        //RETURN TO VIEW
        if(Auth::user()->part != "Pegawai"){
            return redirect()->route('admin.inputs.votes.index', $period)->withInput(['tab_redirect'=>'pills-'.$redirect])->with('success','Voting Berhasil');
        }else{
            return redirect()->route('officer.votes.index', $period)->withInput(['tab_redirect'=>'pills-'.$redirect])->with('success','Voting Berhasil');
        }
    }
}
