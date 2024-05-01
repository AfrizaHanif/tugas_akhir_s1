<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\Score;
use App\Models\VoteCheck;
use App\Models\VoteCriteria;
use App\Models\VoteResult;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(){
        $input_off = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
        ->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();
        $performances = Performance::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $presences = Presence::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $scores = Score::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $periods = Period::get();
        $latest = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();
        $vote_criterias = VoteCriteria::get();
        $check = VoteCheck::select('id_period', 'id_officer')->groupBy('id_period', 'id_officer')->get();
        //dd($check);
        $latest_best = VoteResult::with('officer', 'period')->latest()->first();

        return view('Pages.Admin.dashboard', compact('input_off', 'officers', 'performances', 'presences', 'scores', 'latest', 'vote_criterias', 'check', 'latest_best'));
    }

    public function officer(){
        return view('Pages.Officer.dashboard');
    }
}
