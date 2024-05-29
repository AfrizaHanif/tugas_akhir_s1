<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\HistoryResult;
use App\Models\HistoryVote;
use App\Models\HistoryVoteCheck;
use App\Models\HistoryVoteResult;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Result;
use App\Models\Score;
use App\Models\SubCriteria;
use App\Models\Vote;
use App\Models\VoteCheck;
use App\Models\VoteCriteria;
use App\Models\VoteResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Database\Eloquent\Builder;

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
            return redirect()->route('admin.masters.periods.index')->withErrors($validator)->with('modal_redirect', 'modal-per-create');
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
        return redirect()->route('admin.masters.periods.index')->with('success','Tambah Periode Berhasil')->with('code_alert', 1);
    }

    public function refresh()
    {
        Artisan::call('app:create-period');
        return redirect()->route('admin.masters.periods.index')->with('success', 'Refresh Periode Berhasil. Jika tidak ada perubahan, maka hal ini normal. Baca Bantuan untuk mengetahui mengenai Refresh Periode.')->with('code_alert', 1);
    }

    public function start($period)
    {
        //CHECK RUNNING
        $count = Period::whereIn('status', ['Scoring', 'Voting'])->count();
        if($count != 0){
            return redirect()->route('admin.masters.periods.index')->with('fail','Tidak dapat memulai proses pada periode ini karena proses Pemilihan Karyawan Terbaik sedang berjalan.')->with('code_alert', 1);
        }

        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Scoring',
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Proses Pemilihan Karyawan Terbaik Dimulai')->with('code_alert', 1);
    }

    public function skip($period)
    {
        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Skipped',
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Proses Lewat Berhasil')->with('code_alert', 1);
    }

    public function finish($period)
    {
        //VERIFICATION (DISABLE ONLY FOR TESTING PURPOSE)
        /*
        $off_count = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->count();
        $votecheck0 = VoteCheck::where('id_period', $period)->select('id_officer')->groupby('id_officer')->count();
        if($votecheck0 != $off_count){
            return redirect()->route('admin.masters.periods.index')->with('fail','Ada beberapa pegawai yang belum melakukan voting. Mohon cek di Dashboard.')->with('code_alert', 1);
        }
        */

        //GET MOST COUNT
        $votecriterias = VoteCriteria::get();
        foreach($votecriterias as $criteria){
            $votes = Vote::with('officer')
            ->whereHas('officer', function ($query) {
                $query->with('score')
                ->whereHas('score', function ($query) {
                    $query->orderBy('final_score', 'DESC');
                });
            })
            ->where('id_period', $period)
            ->where('id_vote_criteria', $criteria->id_vote_criteria)
            ->orderBy('votes', 'DESC')
            ->offset(0)->limit(1)->get();
            //dd($votes);

            foreach($votes as $vote){
                /*
                VoteResult::insert([
                    //'id_vote_result'=>$id_vote_result,
                    'id_officer'=>$vote->id_officer,
                    'id_period'=>$vote->id_period,
                    'id_vote_criteria'=>$vote->id_vote_criteria,
                    'final_vote'=>$vote->votes,
                    //'final_score'=>$vote->final_score,
                ]);
                */

                HistoryVoteResult::insert([
                    'id_period'=>$vote->id_period,
                    'period_name'=>$vote->period->name,
                    'id_officer'=>$vote->id_officer,
                    'officer_name'=>$vote->officer->name,
                    'officer_department'=>$vote->officer->department->name,
                    'id_vote_criteria'=>$vote->id_vote_criteria,
                    'vote_criteria_name'=>$vote->votecriteria->name,
                    'final_vote'=>$vote->votes,
                ]);
            }
        }

        $voteresults = HistoryVoteResult::where('id_period', $period)->select('id_officer')->groupby('id_officer')->get();
        foreach($voteresults as $result){
            $count = HistoryVoteResult::where('id_period', $period)->where('id_officer', $result->id_officer)->count();
            $score = Score::where('id_period', $period)->where('id_officer', $result->id_officer)->first();
            $getofficer = Officer::where('id_officer', $result->id_officer)->first();
            $getperiod = Period::where('id_period', $period)->first();
            $gethvoteresult = HistoryVoteResult::where('id_officer', $result->id_officer)->first();

            $photo = '';
            $id_officer = Officer::find($getofficer->id_officer);
            $path_photo = public_path('Images/Portrait/'.$id_officer->photo);
            if(!empty($getofficer->photo)){
                $extension = File::extension($path_photo);
                $photo = 'IMG-'.$period.'-'.$getofficer->id_officer.'.'.$extension;
                $new_path = 'Images/History/Portrait/'.$photo;
                File::copy($path_photo , $new_path);
            }

            /*
            Result::insert([
                //'id_result'=>$id_result,
                'id_officer'=>$result->id_officer,
                'id_period'=>$period,
                'count'=>$count,
                'final_score'=>$score->final_score,
            ]);
            */

            HistoryResult::insert([
                'id_period'=>$period,
                'period_name'=>$getperiod->name,
                'id_officer'=>$gethvoteresult->id_officer,
                'officer_name'=>$gethvoteresult->officer_name,
                'officer_department'=>$gethvoteresult->officer_department,
                'officer_photo'=>$photo,
                'count'=>$count,
                'final_score'=>$score->final_score,
            ]);
        }

        //BACKUP TO HISTORY
        //VOTE
        $votes2 = Vote::where('id_period', $period)->get();
        foreach($votes2 as $vote){
            $getperiod1 = Period::where('id_period', $vote->id_period)->first();
            $getofficer1 = Officer::where('id_officer', $vote->id_officer)->first();
            $getdepartment1 = Department::with('officer')->whereHas('officer', function($query) use($vote){
                $query->where('id_officer', $vote->id_officer);
            })->first();
            $getvotecrit1 = VoteCriteria::where('id_vote_criteria', $vote->id_vote_criteria)->first();

            $photo = '';
            $id_officer = Officer::find($getofficer1->id_officer);
            $path_photo = public_path('Images/Portrait/'.$id_officer->photo);
            if(!empty($getofficer1->photo)){
                $extension = File::extension($path_photo);
                $photo = 'IMG-'.$getperiod1->id_period.'-'.$getofficer1->id_officer.'.'.$extension;
                $new_path = 'Images/History/Portrait/'.$photo;
                File::copy($path_photo , $new_path);
            }

            HistoryVote::insert([
                'id_period'=>$getperiod1->id_period,
                'period_name'=>$getperiod1->name,
                'id_officer'=>$getofficer1->id_officer,
                'officer_name'=>$getofficer1->name,
                'officer_department'=>$getdepartment1->name,
                'officer_photo'=>$photo,
                'id_vote_criteria'=>$getvotecrit1->id_vote_criteria,
                'vote_criteria_name'=>$getvotecrit1->name,
                'votes'=>$vote->votes,
            ]);
        }

        //VOTE CHECKER
        $votechecks = VoteCheck::where('id_period', $period)->get();
        foreach($votechecks as $check){
            $getperiod2 = Period::where('id_period', $check->id_period)->first();
            $getofficer2 = Officer::where('id_officer', $check->id_officer)->first();
            $getdepartment2 = Department::with('officer')->whereHas('officer', function($query) use($check){
                $query->where('id_officer', $check->id_officer);
            })->first();
            $getoffselect2 = Officer::where('id_officer', $check->officer_selected)->first();
            $getvotecrit2 = VoteCriteria::where('id_vote_criteria', $check->id_vote_criteria)->first();
            HistoryVoteCheck::insert([
                'id_period'=>$getperiod2->id_period,
                'period_name'=>$getperiod2->name,
                'id_officer'=>$getofficer2->id_officer,
                'officer_name'=>$getofficer2->name,
                'officer_department'=>$getdepartment2->name,
                'id_vote_criteria'=>$getvotecrit2->id_vote_criteria,
                'vote_criteria_name'=>$getvotecrit2->name,
                'officer_selected'=>$getoffselect2->id_officer,
                'officer_selected_name'=>$getoffselect2->name,
            ]);
        }

        /*
        //VOTE RESULTS
        $voteresults1 = VoteResult::where('id_period', $period)->get();
        foreach($voteresults1 as $result){
            $getperiod3 = Period::where('id_period', $result->id_period)->first();
            $getofficer3 = Officer::where('id_officer', $result->id_officer)->first();
            $getdepartment3 = Department::with('officer')->whereHas('officer', function($query) use($result){
                $query->where('id_officer', $result->id_officer);
            })->first();
            $getsubcriteria3 = VoteCriteria::where('id_vote_criteria', $result->id_vote_criteria)->first();

            HistoryVoteResult::insert([
                'id_period'=>$getperiod3->id_period,
                'period_name'=>$getperiod3->name,
                'id_officer'=>$getofficer3->id_officer,
                'officer_name'=>$getofficer3->name,
                'officer_department'=>$getdepartment3->name,
                'id_vote_criteria'=>$getsubcriteria3->id_vote_criteria,
                'vote_criteria_name'=>$getsubcriteria3->name,
                'final_vote'=>$result->final_vote,
            ]);
        }

        //RESULTS
        $result1 = Result::where('id_period', $period)->orderBy('count', 'DESC')->offset(0)->limit(1)->first();
        $getperiod4 = Period::where('id_period', $result1->id_period)->first();
        $getofficer4 = Officer::where('id_officer', $result1->id_officer)->first();
        $getdepartment4 = Department::with('officer')->whereHas('officer', function($query) use($result1){
            $query->where('id_officer', $result1->id_officer);
        })->first();

        $photo = '';
        $id_officer = Officer::find($getofficer4->id_officer);
        $path_photo = public_path('Images/Portrait/'.$id_officer->photo);
        if(!empty($getofficer4->photo)){
            $extension = File::extension($path_photo);
            $photo = 'IMG-'.$getperiod1->id_period.'-'.$getofficer4->id_officer.'.'.$extension;
            $new_path = 'Images/History/Portrait/'.$photo;
            File::copy($path_photo , $new_path);
        }

        HistoryResult::insert([
            'id_period'=>$getperiod4->id_period,
            'period_name'=>$getperiod4->name,
            'id_officer'=>$getofficer4->id_officer,
            'officer_name'=>$getofficer4->name,
            'officer_department'=>$getdepartment4->name,
            'officer_photo'=>$photo,
        ]);
        */

        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Finished',
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Pemilihan Karyawan Terbaik selesai.')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period)
    {
        //DELETE DATA
        $period->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Hapus Periode Berhasil')->with('code_alert', 1);
    }
}
