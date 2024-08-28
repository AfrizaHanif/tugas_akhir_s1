<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Criteria;
use App\Models\Department;
use App\Models\HistoryInput;
use App\Models\HistoryInputRAW;
use App\Models\HistoryPerformance;
use App\Models\HistoryPresence;
use App\Models\HistoryResult;
use App\Models\HistoryScore;
use App\Models\Input;
use App\Models\InputRAW;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Period;
use App\Models\Score;
use App\Models\Setting;
use App\Models\SubCriteria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ScoreController extends Controller
{
    public function index()
    {
        //GET DATA
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->where('is_lead', 'No')
        ->get();
        $periods = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending'])->get();
        $scores = Score::with('officer')->orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->get();
        $inputs = Input::get();
        //$input_raws = InputRAW::get();
        $status = Input::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $categories = Category::with('criteria')->get();
        $allcriterias = Criteria::with('category')->get();
        $criterias = Criteria::get();
        $countsub = Criteria::count();

        //GET PERIODS FOR LIST
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending', 'Finished'])->latest()->first();
        $history_per = HistoryScore::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->get();

        //dd($scores->where('id_period', $latest_per->id_period)->where('status', 'Accepted')->count() != $officers->count());

        //GET HISTORY DATA
        $hscore = HistoryScore::orderBy('final_score', 'DESC')->get();
        $hofficer = HistoryScore::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->get();

        //RETURN TO VIEW
        return view('Pages.Admin.score', compact('officers', 'periods', 'latest_per', 'history_per', 'scores', 'inputs', 'status', 'categories', 'allcriterias', 'criterias', 'countsub', 'hscore', 'hofficer'));
    }

    public function get($period)
    {
        //GET DATA
        //$periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $subcriterias = Criteria::with('category')->get();
        $officers = Officer::where('is_lead', 'No')->get();

        //VERIFICATION
        //CHECK EMPTY DATA
        if(Input::where('id_period', $period)->count() == 0){
            return redirect()->route('admin.inputs.validate.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
        }else{
            foreach ($officers as $officer) {
                if(Input::where('id_period', $period)->where('id_officer', $officer->id_officer)->count() == 0){ //IF OFFICER HAS NO DATA IN BOTH TABLE
                    return redirect()->route('admin.inputs.validate.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan cek kembali di halaman Input Data. ('.$officer->id_officer.')');
                }else{ //IF OFFICER HAS A FEW DATA IN BOTH TABLE
                    foreach ($subcriterias as $subcriteria) {
                        if(Input::where('id_period', $period)->where('id_officer', $officer->id_officer)->where('id_criteria', $subcriteria->id_criteria)->count() == 0) {
                            return redirect()->route('admin.inputs.validate.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan cek kembali di halaman Input Data. ('.$officer->id_officer.') ('.$subcriteria->id_criteria.')');
                        }else{
                            //CLEAR
                        }
                    }
                }
            }
        }

        //DELETE EXISTING DATA
        //$check = DB::table('scores')->where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Rejected')->orWhere('status', 'Revised');
        $check = DB::table('scores')->where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Revised');
        if($check->exists()){
            $check->delete();
        }

        //GET CRITERIA
        $criterias = DB::table("inputs")
        ->join('criterias', 'criterias.id_criteria', '=', 'inputs.id_criteria')
        ->select(
            'inputs.id_criteria AS id_criteria',
            'criterias.weight AS weight',
            'criterias.attribute AS attribute',
            )
        ->groupBy(
            'id_criteria',
            'weight',
            'attribute'
            )
        ->where('id_period', $period)
        ->where('criterias.need', 'Ya')
        ->get();
        //$criterias = Criteria::get();

        //GET INPUT
        $inputs = Input::with('criteria')
        ->where('id_period', $period)
        ->whereHas('criteria', function($query){
            $query->where('need', 'Ya');
        })
        ->whereDoesntHave('officer', function($query){
            $query->with('user')->whereHas('user', function($query){
                $query->where('part', 'KBPS');
            });
        })
        ->getQuery()->get();

        //FIND MIN DAN MAX
        foreach($criterias as $crit => $value1){
            foreach($inputs as $input => $value2){
                if($value1->id_criteria == $value2->id_criteria){
                    if($value1->attribute == 'Benefit'){
                        $minmax[$value1->id_criteria][] = $value2->input;
                    }elseif($value1->attribute == 'Cost'){
                        $minmax[$value1->id_criteria][] = $value2->input;
                    }
                }
            }
        }
        //dd($minmax);

        //NORMALIZATION
        foreach($inputs as $input => $value1){
            foreach($criterias as $crit => $value2){
                if($value2->id_criteria == $value1->id_criteria){
                    if($value2->attribute == 'Benefit'){
                        $normal[$value1->id_officer][$value2->id_criteria] = $value1->input / (max($minmax[$value2->id_criteria]) ?: 1);
                    }elseif($value2->attribute == 'Cost'){
                        if(min($minmax[$value2->id_criteria]) == 0){
                            if($value1->input == 0){
                                $normal[$value1->id_officer][$value2->id_criteria] = 0.5 / 0.5;
                            }else{
                                $normal[$value1->id_officer][$value2->id_criteria] = 0.5 / ($value1->input ?: 1);
                            }
                        }else{
                            $normal[$value1->id_officer][$value2->id_criteria] = (min($minmax[$value2->id_criteria]) ?: 1) / ($value1->input ?: 1);
                        }
                        //$normal[$value1->id_officer][$value2->id_criteria] = (min($minmax[$value2->id_criteria]) ?: 1) / $value1->input;
                    }
                }
            }
        }
        //dd($normal);

        //MATRIX
        foreach($inputs as $input => $value1){
            foreach($criterias as $crit => $value2){
                if($value2->id_criteria == $value1->id_criteria){
                    $mxin[$value1->id_officer][$value2->id_criteria] = $normal[$value1->id_officer][$value2->id_criteria] * $value2->weight;
                }
            }
        }
        //dd($mxin);
        $mx_hasil = $mxin; //$ranking = $normal;

        //INSERT FOR VALIDATION
        foreach($normal as $n => $value1){
            //GET MATRIX
            $mx_hasil[$n][] = array_sum($mxin[$n]); //$normal[$n][] = array_sum($rank[$n]);
            $matrix[$n] = array_sum($mxin[$n]); //$normal[$n][] = array_sum($rank[$n]);

            //INSERT DATA
            if(DB::table('scores')->where('id_period', $period)->where('id_officer', $n)->count() == 0){
                //GET DATA FOR SECOND SORT
                $setting = Setting::where('id_setting', 'STG-002')->first()->value;
                $set_second = Criteria::where('id_criteria', $setting)->first();
                $second_score = Input::where('id_period', $period)->where('id_officer', $n)->where('id_criteria', $set_second->id_criteria)->first()->input_raw;

                //INSERT DATA SCORES
                $str_officer = substr($n, 4);
                $str_year = substr($period, -5);
                $id_score = "SCR-".$str_year.'-'.$str_officer;
                DB::table('scores')->insert([
                    //'id_score'=>$id_score,
                    'id_officer'=>$n,
                    'id_period'=>$period,
                    'final_score'=>$matrix[$n],
                    'second_score'=>$second_score,
                    'status'=>'Pending'
                ]);
            }else{
                //SKIP
            }
        }
        arsort($matrix);

        //UPDATE STATUS
        Input::with('officer')
        ->where('id_period', $period)
        ->whereIn('status', ['Pending', 'Need Fix', 'Fixed'])
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'In Review'
        ]);
        /*
        InputRAW::with('officer')
        ->where('id_period', $period)
        ->whereIn('status', ['Pending', 'Need Fix', 'Fixed'])
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'In Review'
        ]);
        */
        Period::where('id_period', $period)
        ->update([
            'status'=>'Validating'
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Ambil Data Berhasil')->with('code_alert', 1);
    }

    public function yes($id)
    {
        //GET DATA
        $result = Score::where('id', $id)->first();
        $period = Period::where('id_period', $result->id_period)->first()->id_period;
        $officer = Officer::where('id_officer', $result->id_officer)->first()->id_officer;
        $name = Officer::where('id_officer', $result->id_officer)->first()->name;

        //UPDATE STATUS
        Score::where('id', $id)->update([
            'status'=>'Accepted'
        ]);
        Input::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Final'
        ]);
        /*
        InputRAW::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Final'
        ]);
        */

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Persetujuan Berhasil. Data dari pegawai ('. $name .') telah disetujui')->with('code_alert', 1);
    }

    public function yesall($id)
    {
        //UPDATE STATUS
        Score::where('id_period', $id)->update([
            'status'=>'Accepted'
        ]);
        Input::with('officer')->where('id_period', $id)
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'Final'
        ]);
        /*
        InputRAW::with('officer')->where('id_period', $id)
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'Final'
        ]);
        */

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Persetujuan Berhasil. Data dari seluruh pegawai telah disetujui')->with('code_alert', 1);
    }

    public function no($id)
    {
        //GET DATA
        $result = Score::where('id', $id)->first();
        $period = Period::where('id_period', $result->id_period)->first()->id_period;
        $officer = Officer::where('id_officer', $result->id_officer)->first()->id_officer;
        $name = Officer::where('id_officer', $result->id_officer)->first()->name;

        //UPDATE STATUS
        Score::where('id', $id)->update([
            'status'=>'Rejected'
        ]);
        Input::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Need Fix'
        ]);
        /*
        InputRAW::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Need Fix'
        ]);
        */

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Penolakan Berhasil. Data dari pegawai ('. $name .') telah dikembalikan')->with('code_alert', 1);
    }

    public function noall($id)
    {
        //UPDATE STATUS
        Score::where('id_period', $id)->update([
            'status'=>'Rejected'
        ]);
        Input::with('officer')->where('id_period', $id)
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'Need Fix'
        ]);
        /*
        InputRAW::with('officer')->where('id_period', $id)
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'Need Fix'
        ]);
        */

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Penolakan Berhasil. Data dari seluruh pegawai telah dikembalikan')->with('code_alert', 1);
    }

    public function finish($period)
    {
        //HISTORY SCORE
        //GET DATA (1/2)
        $scores1 = Score::where('id_period', $period)->get();
        foreach($scores1 as $score){
            //GET DATA (2/2)
            $getperiod1 = Period::where('id_period', $score->id_period)->first();
            $getofficer1 = Officer::where('id_officer', $score->id_officer)->first();
            $getdepartment1 = Department::with('officer')->whereHas('officer', function($query) use($score){
                $query->where('id_officer', $score->id_officer);
            })->first();

            //INSERT DATA
            HistoryScore::insert([
                'id_period'=>$getperiod1->id_period,
                'period_name'=>$getperiod1->name,
                'id_officer'=>$getofficer1->id_officer,
                'officer_nip'=>$getofficer1->nip,
                'officer_name'=>$getofficer1->name,
                'officer_department'=>$getdepartment1->name,
                'final_score'=>$score->final_score,
                'second_score'=>$score->second_score,
            ]);
        }

        //HISTORY INPUT (CONVERTED)
        //GET DATA (1/2)
        $inputs = Input::where('id_period', $period)->get();
        foreach($inputs as $input){
            //GET DATA (2/2)
            $getuser2 = User::where('id_officer', $input->id_officer)->first();
            $getperiod2 = Period::where('id_period', $input->id_period)->first();
            $getofficer2 = Officer::where('id_officer', $input->id_officer)->first();
            $getdepartment2 = Department::with('officer')->whereHas('officer', function($query) use($input){
                $query->where('id_officer', $input->id_officer);
            })->first();
            $getcriteria2 = Category::with('criteria')->whereHas('criteria', function($query) use($input){
                $query->where('id_criteria', $input->id_criteria);
            })->first();
            $getsubcriteria2 = Criteria::where('id_criteria', $input->id_criteria)->first();

            //CHECK IF ADMIN (WILL BE REMOVED)
            if(empty($getuser2->part) || $getuser2->part == 'Admin'){
                $is_lead = 'No';
            }else{
                $is_lead = 'Yes';
            }

            //INSERT DATA
            HistoryInput::insert([
                'id_period'=>$getperiod2->id_period,
                'period_name'=>$getperiod2->name,
                'id_officer'=>$getofficer2->id_officer,
                'officer_nip'=>$getofficer2->nip,
                'officer_name'=>$getofficer2->name,
                'officer_department'=>$getdepartment2->name,
                'id_category'=>$getcriteria2->id_category,
                'category_name'=>$getcriteria2->name,
                'id_criteria'=>$getsubcriteria2->id_criteria,
                'criteria_name'=>$getsubcriteria2->name,
                'weight'=>$getsubcriteria2->weight,
                'attribute'=>$getsubcriteria2->attribute,
                'level'=>$getsubcriteria2->level,
                'max'=>$getsubcriteria2->max,
                'is_lead'=>$is_lead,
                'input'=>$input->input,
                'input_raw'=>$input->input_raw,
            ]);
        }

        /*
        //HISTORY INPUT (RAW)
        //GET DATA (1/2)
        $input_raws = InputRAW::where('id_period', $period)->get();
        foreach($input_raws as $raw){
            //GET DATA (2/2)
            $getuser3 = User::where('id_officer', $raw->id_officer)->first();
            $getperiod3 = Period::where('id_period', $raw->id_period)->first();
            $getofficer3 = Officer::where('id_officer', $raw->id_officer)->first();
            $getdepartment3 = Department::with('officer')->whereHas('officer', function($query) use($raw){
                $query->where('id_officer', $raw->id_officer);
            })->first();
            $getcriteria3 = Category::with('criteria')->whereHas('criteria', function($query) use($raw){
                $query->where('id_criteria', $raw->id_criteria);
            })->first();
            $getsubcriteria3 = Criteria::where('id_criteria', $raw->id_criteria)->first();

            //CHECK IF ADMIN (WILL BE REMOVED)
            if(empty($getuser3->part) || $getuser3->part == 'Admin'){
                $is_lead = 'No';
            }else{
                $is_lead = 'Yes';
            }

            //INSERT DATA
            HistoryInputRAW::insert([
                'id_period'=>$getperiod3->id_period,
                'period_name'=>$getperiod3->name,
                'id_officer'=>$getofficer3->id_officer,
                'officer_nip'=>$getofficer3->nip,
                'officer_name'=>$getofficer3->name,
                'officer_department'=>$getdepartment3->name,
                'id_category'=>$getcriteria3->id_category,
                'category_name'=>$getcriteria3->name,
                'id_criteria'=>$getsubcriteria3->id_criteria,
                'criteria_name'=>$getsubcriteria3->name,
                'weight'=>$getsubcriteria3->weight,
                'attribute'=>$getsubcriteria3->attribute,
                'level'=>$getsubcriteria3->level,
                'max'=>$getsubcriteria3->max,
                'is_lead'=>$is_lead,
                'input'=>$raw->input,
            ]);
        }
        */

        //HISTORY RESULT
        //GET DATA (RESULT)
        $scores2 = Score::where('id_period', $period)->orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->offset(0)->limit(1)->first();
        $getperiod4 = Period::where('id_period', $scores2->id_period)->first();
        $getofficer4 = Officer::where('id_officer', $scores2->id_officer)->first();
        $getdepartment4 = Department::with('officer')->whereHas('officer', function($query) use($scores2){
            $query->where('id_officer', $scores2->id_officer);
        })->first();

        //GET PHOTO (RESULT)
        $photo = '';
        $id_officer = Officer::find($getofficer4->id_officer);
        $path_photo = public_path('Images/Portrait/'.$id_officer->photo);
        if(!empty($getofficer4->photo)){
            $extension = File::extension($path_photo);
            $photo = 'IMG-'.$getperiod1->id_period.'-'.$getofficer4->id_officer.'.'.$extension;
            $new_path = 'Images/History/Portrait/'.$photo;
            File::copy($path_photo , $new_path);
        }

        //INSERT DATA (RESULT)
        HistoryResult::insert([
            'id_period'=>$getperiod4->id_period,
            'period_name'=>$getperiod4->name,
            'id_officer'=>$getofficer4->id_officer,
            'officer_nip'=>$getofficer4->nip,
            'officer_name'=>$getofficer4->name,
            'officer_department'=>$getdepartment4->name,
            'officer_photo'=>$photo,
            'final_score'=>$scores2->final_score,
            //'second_score'=>$scores2->second_score,
        ]);

        //UPDATE STATUS
        Period::where('id_period', $period)->update([
            'status'=>'Finished',
        ]);

        //DELETE CURRENT DATA
        DB::table('inputs')->delete();
        //DB::table('input_raws')->delete();
        DB::table('scores')->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Proses Pemilihan Karyawan Terbaik Selesai. Hasil tersebut dapat dilihat pada halaman Dashboard dan Utama')->with('code_alert', 1);
    }
}
