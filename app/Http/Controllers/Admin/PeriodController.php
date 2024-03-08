<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryResult;
use App\Models\HistoryVote;
use App\Models\HistoryVoteResult;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Result;
use App\Models\SubCriteria;
use App\Models\Vote;
use App\Models\VoteCheck;
use App\Models\VoteCriteria;
use App\Models\VoteResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->get();
        return view('Pages.Admin.period', compact('periods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $str_month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
        $str_year = substr($request->year, -2);
        $id_period = "PRD-".$str_month.'-'.$str_year;

        //CONVERT MONTH
        $name_month = '';
        if($request->month == 1){
            $name_month = 'Januari';
        }elseif($request->month == 2){
            $name_month = 'Februari';
        }elseif($request->month == 3){
            $name_month = 'Maret';
        }elseif($request->month == 4){
            $name_month = 'April';
        }elseif($request->month == 5){
            $name_month = 'Mei';
        }elseif($request->month == 6){
            $name_month = 'Juni';
        }elseif($request->month == 7){
            $name_month = 'Juli';
        }elseif($request->month == 8){
            $name_month = 'Agustus';
        }elseif($request->month == 9){
            $name_month = 'September';
        }elseif($request->month == 10){
            $name_month = 'Oktober';
        }elseif($request->month == 11){
            $name_month = 'November';
        }elseif($request->month == 12){
            $name_month = 'Desember';
        }

        //VALIDATE DATA
        $input = [
            'id_period' => $id_period,
            'name' => $name_month.' '.$request->year,
        ];

        $validator = Validator::make($input, [
            'id_period' => 'unique:periods',
            'name' => 'unique:periods',
        ], [
            'id_period.unique' => 'ID telah terdaftar sebelumnya',
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('masters.periods.index')->withErrors($validator)->with('modal_redirect', 'modal-per-create');
        }

        //STORE DATA
        Period::insert([
            'id_period'=>$id_period,
            'name'=>$name_month.' '.$request->year,
            'month'=>$name_month,
            'year'=>$request->year,
            'status'=>'Pending',
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.periods.index')->with('success','Tambah Periode Berhasil')->with('code_alert', 1);
    }

    public function start($period)
    {
        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Scoring',
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.periods.index')->with('success','Proses Pemilihan Karyawan Terbaik Dimulai')->with('code_alert', 1);
    }

    public function skip($period)
    {
        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Skipped',
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.periods.index')->with('success','Proses Lewat Berhasil')->with('code_alert', 1);
    }

    public function finish($period)
    {
        //GET MOST COUNT
        $votecriterias = VoteCriteria::get();
        foreach($votecriterias as $criteria){
            $votes = Vote::with('officer')->where('id_period', $period)->orderBy('votes', 'DESC')->where('id_vote_criteria', $criteria->id_vote_criteria)->offset(0)->limit(1)->get();

            foreach($votes as $vote){
                VoteResult::insert([
                    //'id_vote_result'=>$id_vote_result,
                    'id_officer'=>$vote->id_officer,
                    'id_period'=>$vote->id_period,
                    'id_vote_criteria'=>$vote->id_vote_criteria,
                    'final_vote'=>$vote->votes,
                ]);
            }
        }

        $voteresults = VoteResult::where('id_period', $period)->select('id_officer')->groupby('id_officer')->get();
        foreach($voteresults as $result){
            $count = VoteResult::where('id_period', $period)->where('id_officer', $result->id_officer)->count();

            Result::insert([
                //'id_result'=>$id_result,
                'id_officer'=>$result->id_officer,
                'id_period'=>$period,
                'count'=>$count,
            ]);
        }

        //BACKUP TO HISTORY
        $votes1 = Vote::where('id_period', $period)->get();
        foreach($votes1 as $vote){
            $getperiod1 = Period::where('id_period', $vote->id_period)->first();
            $getofficer1 = Officer::where('id_officer', $vote->id_officer)->first();
            HistoryVote::insert([
                'period_name'=>$getperiod1->name,
                'officer_name'=>$getofficer1->name,
                'votes'=>$vote->votes,
            ]);
        }

        $votechecks = VoteCheck::where('id_period', $period)->get();
        foreach($votechecks as $check){
            $getperiod2 = Period::where('id_period', $check->id_period)->first();
            $getofficer2 = Officer::where('id_officer', $check->id_officer)->first();
            $getoffselect2 = Officer::where('id_officer', $check->officer_selected)->first();
            HistoryVoteResult::insert([
                'period_name'=>$getperiod2->name,
                'officer_name'=>$getofficer2->name,
                'officer_selected'=>$getoffselect2->name,
            ]);
        }

        $voteresults1 = VoteResult::where('id_period', $period)->get();
        foreach($voteresults1 as $result){
            $getperiod3 = Period::where('id_period', $result->id_period)->first();
            $getofficer3 = Officer::where('id_officer', $result->id_officer)->first();
            $getsubcriteria3 = SubCriteria::where('id_sub_criteria', $result->id_sub_criteria)->first();
            HistoryVoteResult::insert([
                'period_name'=>$getperiod3->name,
                'officer_name'=>$getofficer3->name,
                'vote_criteria_name'=>$getsubcriteria3->name,
                'final_vote'=>$result->final_vote,
            ]);
        }

        $result1 = Result::where('id_period', $period)->orderBy('count', 'DESC')->offset(0)->limit(1)->get();
        $getperiod4 = Period::where('id_period', $result1->id_period)->first();
        $getofficer4 = Officer::where('id_officer', $result1->id_officer)->first();
        HistoryResult::insert([
            'period_name'=>$getperiod4->name,
            'officer_name'=>$getofficer4->name,
        ]);

        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Finished',
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.periods.index')->with('success','Pemilihan Karyawan Terbaik selesai.')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period)
    {
        //DELETE DATA
        $period->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.periods.index')->with('success','Hapus Periode Berhasil')->with('code_alert', 1);
    }
}
