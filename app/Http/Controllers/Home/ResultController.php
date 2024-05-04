<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Result;
use App\Models\Vote;
use App\Models\VoteCriteria;
use App\Models\VoteResult;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('status', 'Finished')->get();
        $results = Result::with('officer')->orderBy('count', 'DESC')->orderBy('final_score')->offset(0)->limit(1)->get();
        $votes = Vote::get();
        $votecriterias = VoteCriteria::get();
        $voteresults = VoteResult::with('officer')->get();
        $officers = Officer::with('department')->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})->get();

        return view('Pages.Home.result', compact('periods', 'results', 'officers', 'votecriterias', 'votes', 'voteresults'));
    }
}
