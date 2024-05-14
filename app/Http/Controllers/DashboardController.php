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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function admin(){
        //Artisan::call('app:create-period');

        if(Auth::user()->part == 'KBU'){
            $officers = Officer::with('department', 'part')
            ->whereHas('department', function($query)
            {
                $query->where('name', 'LIKE', '%Bagian Umum%')
                ->orWhere('name', 'LIKE', '%Anggaran%')
                ->orWhere('name', 'LIKE', '%Keuangan%')
                ->orWhere('name', 'LIKE', '%Arsip%')
                ->orWhere('name', 'LIKE', '%Pengadaan%');
            })
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
            $reject_offs = Officer::with('department', 'part', 'score')
            ->whereHas('department', function($query)
            {
                $query->where('name', 'LIKE', '%Bagian Umum%')
                ->orWhere('name', 'LIKE', '%Anggaran%')
                ->orWhere('name', 'LIKE', '%Keuangan%')
                ->orWhere('name', 'LIKE', '%Arsip%')
                ->orWhere('name', 'LIKE', '%Pengadaan%');
            })
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->whereHas('score', function($query){$query->whereIn('status', ['Rejected', 'Revised']);})
            ->get();
            $input_off = Officer::with('department', 'part')
            ->whereHas('department', function($query)
            {
                $query->where('name', 'LIKE', '%Bagian Umum%')
                ->orWhere('name', 'LIKE', '%Anggaran%')
                ->orWhere('name', 'LIKE', '%Keuangan%')
                ->orWhere('name', 'LIKE', '%Arsip%')
                ->orWhere('name', 'LIKE', '%Pengadaan%');
            })
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
            $count_per = Performance::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query)
                {
                    $query->where('name', 'LIKE', '%Bagian Umum%')
                    ->orWhere('name', 'LIKE', '%Anggaran%')
                    ->orWhere('name', 'LIKE', '%Keuangan%')
                    ->orWhere('name', 'LIKE', '%Arsip%')
                    ->orWhere('name', 'LIKE', '%Pengadaan%');
                });
            })
            ->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            $count_pre = Presence::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query)
                {
                    $query->where('name', 'LIKE', '%Bagian Umum%')
                    ->orWhere('name', 'LIKE', '%Anggaran%')
                    ->orWhere('name', 'LIKE', '%Keuangan%')
                    ->orWhere('name', 'LIKE', '%Arsip%')
                    ->orWhere('name', 'LIKE', '%Pengadaan%');
                });
            })
            ->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            $performances = Performance::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query)
                {
                    $query->where('name', 'LIKE', '%Bagian Umum%')
                    ->orWhere('name', 'LIKE', '%Anggaran%')
                    ->orWhere('name', 'LIKE', '%Keuangan%')
                    ->orWhere('name', 'LIKE', '%Arsip%')
                    ->orWhere('name', 'LIKE', '%Pengadaan%');
                });
            })
            ->get();
            $presences = Presence::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query)
                {
                    $query->where('name', 'LIKE', '%Bagian Umum%')
                    ->orWhere('name', 'LIKE', '%Anggaran%')
                    ->orWhere('name', 'LIKE', '%Keuangan%')
                    ->orWhere('name', 'LIKE', '%Arsip%')
                    ->orWhere('name', 'LIKE', '%Pengadaan%');
                });
            })
            ->get();
        }elseif(Auth::user()->part == 'KTT'){
            $officers = Officer::with('department', 'part')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
            $reject_offs = Officer::with('department', 'part', 'score')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->whereHas('score', function($query){$query->whereIn('status', ['Rejected', 'Revised']);})
            ->get();
            $input_off = Officer::with('department', 'part')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
            $count_per = Performance::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query){
                    $query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');
                });
            })
            ->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            $count_pre = Presence::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query){
                    $query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');
                });
            })
            ->select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            $performances = Performance::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query){
                    $query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');
                });
            })
            ->get();
            $presences = Presence::with('officer')
            ->whereHas('officer', function($query){
                $query->with('department')->whereHas('department', function($query){
                    $query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');
                });
            })
            ->get();
        }else{
            $officers = Officer::with('department', 'part')
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
            $reject_offs = Officer::with('department', 'part', 'score')
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->whereHas('score', function($query){$query->whereIn('status', ['Rejected', 'Revised']);})
            ->get();
            $input_off = Officer::with('department', 'part')
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
            $count_per = Performance::select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            $count_pre = Presence::select('id_period', 'id_officer', 'status')
            ->groupBy('id_period', 'id_officer', 'status')
            ->get();
            $performances = Performance::get();
            $presences = Presence::get();
        }
        if(Auth::user()->part == 'KBU' || Auth::user()->part == 'KTT'){
            $countsub = SubCriteria::with('criteria')
            ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
            ->count();
            $subcriterias = SubCriteria::with('criteria')
            ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
            ->get();
        }else{
            $countsub = SubCriteria::with('criteria')
            ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
            ->count();
            $subcriterias = SubCriteria::with('criteria')
            ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
            ->get();
        }
        $vote_officer = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();
        $scores = Score::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $check_score = Score::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->first('status');
        $periods = Period::get();
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();
        $vote_criterias = VoteCriteria::get();
        $check = VoteCheck::select('id_period', 'id_officer')->groupBy('id_period', 'id_officer')->get();
        $vote_check = VoteCheck::get();
        //dd($check);
        $latest_best = VoteResult::with('officer', 'period')->latest()->first();
        $latest_top3 = Score::with('officer', 'period')->offset(0)->limit(3)->get();
        $voteresults = Result::with('officer', 'period')
        ->orderBy('count', 'DESC')
        ->whereHas('officer', function ($query) {
            $query->with('score')
            ->whereHas('score', function ($query) {
                $query->orderBy('final_score', 'DESC');
            });
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
