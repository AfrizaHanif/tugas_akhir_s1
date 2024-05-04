<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
use App\Models\Vote;
use App\Models\VoteCheck;
use App\Models\VoteCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('status', 'Voting')->orWhere('status', 'Finished')->get();
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();

        return view('Pages.Officer.vote', compact('periods', 'latest_per'));
    }

    public function vote($period)
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('status', 'Voting')->orWhere('status', 'Finished')->get();
        $prd_select = Period::where('id_period', $period)->orderBy('id_period', 'ASC')->where('status', 'Voting')->orWhere('status', 'Finished')->first();
        //dd($prd_select);
        $criterias = VoteCriteria::get();
        $votes = Vote::with('officer')
        ->whereNot('id_officer', Auth::user()->officer->id_officer)
        ->get();
        $checks = VoteCheck::with('officer')->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();
        $fil_offs = Officer::with('department', 'part')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
        ->get();
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();

        return view('Pages.Officer.vote', compact('periods', 'votes', 'officers', 'checks', 'criterias', 'prd_select', 'fil_offs', 'latest_per'));
    }

    public function select($period, $officer, $criteria)
    {
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
        return redirect()->route('officer.votes.vote', $period)->withInput(['tab_redirect'=>'pills-'.$criteria])->with('success','Voting Berhasil');
    }
}
