<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Department;
use App\Models\HistoryPerformance;
use App\Models\HistoryPresence;
use App\Models\HistoryScore;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Period;
use App\Models\Score;
use App\Models\SubCriteria;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function index()
    {
        //GET DATA
        $periods = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending'])->get();
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending', 'Finished'])->latest()->first();
        $history_per = HistoryScore::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->get();
        $scores = Score::with('officer')->orderBy('final_score', 'DESC')->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->where('is_lead', 'No')
        ->get();
        $performances = Performance::get();
        $presences = Presence::get();
        $status = Presence::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $criterias = Criteria::with('subcriteria')->get();
        $allsubcriterias = SubCriteria::with('criteria')->get();
        $subcritprs = SubCriteria::with('criteria')
        ->whereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprf = SubCriteria::with('criteria')
        ->whereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $countprs = SubCriteria::with('criteria')
        ->whereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->count();
        $countprf = SubCriteria::with('criteria')
        ->whereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->count();
        $hscore = HistoryScore::orderBy('final_score', 'DESC')->get();
        $hofficer = HistoryScore::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->get();

        return view('Pages.Admin.score', compact('periods', 'latest_per', 'history_per', 'scores', 'officers', 'performances', 'presences', 'status', 'criterias', 'allsubcriterias', 'countprs', 'countprf', 'subcritprs', 'subcritprf', 'hscore', 'hofficer'));
    }

    public function get($period)
    {
        //GET DATA
        //$periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $subcriterias = SubCriteria::with('criteria')->get();
        $officers = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->where('is_lead', 'No')
        ->get();

        //VERIFICATION
        //CHECK IF LEADER HAS EMPTY DATA (DISABLE ONLY FOR TESTING PURPOSE)
        $check_lead_pre = Presence::with('officer')
        ->where('id_period', $period)
        ->whereHas('officer', function($query)
        {
            $query->where('is_lead', 'Yes');
        });
        $check_lead_per = Performance::with('officer')
        ->where('id_period', $period)
        ->whereHas('officer', function($query)
        {
            $query->where('is_lead', 'Yes');
        });
        if($check_lead_per->count() == 0 || $check_lead_pre->count() == 0){
            return redirect()->route('admin.inputs.scores.index')->with('fail','Mohon untuk mengisi nilai untuk kepemimpinan (Kepala Bagian Umum dan Kepala Tim Teknis) untuk kebutuhan rekap.');
        }

        //CHECK AVAILABILITY DATA
        if(Presence::where('id_period', $period)->count() == 0 || Performance::where('id_period', $period)->count() == 0){
            return redirect()->route('admin.inputs.scores.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
        }else{
            foreach ($officers as $officer) {
                if(Presence::where('id_period', $period)->where('id_officer', $officer->id_officer)->count() == 0 && Performance::where('id_period', $period)->where('id_officer', $officer->id_officer)->count() == 0){ //IF OFFICER HAS NO DATA IN BOTH TABLE
                    return redirect()->route('admin.inputs.scores.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan lihat di halaman input pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(Presence::where('id_period', $period)->where('id_officer', $officer->id_officer)->count() == 0){ //IF OFFICER HAS NO DATA IN PRESENCES TABLE
                    return redirect()->route('admin.inputs.scores.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Kehadiran. Silahkan lihat di halaman input Data Kehadiran pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(Performance::where('id_period', $period)->where('id_officer', $officer->id_officer)->count() == 0){ //IF OFFICER HAS NO DATA IN PERFORMANCES TABLE
                    return redirect()->route('admin.inputs.scores.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Prestasi Kerja. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }else{ //IF OFFICER HAS A FEW DATA IN BOTH TABLE
                    foreach ($subcriterias as $subcriteria) {
                        if(Presence::where('id_period', $period)->where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0 && Performance::where('id_period', $period)->where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0) {
                            return redirect()->route('admin.inputs.scores.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang hanya dinilai sebagian. ('.$officer->id_officer.') ('.$subcriteria->id_sub_criteria.')');
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

        $first_cri = DB::table("performances")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'performances.id_sub_criteria')
        ->select(
            'performances.id_sub_criteria AS id_sub_criteria',
            'sub_criterias.weight AS weight',
            'sub_criterias.attribute AS attribute',
            )
        ->groupBy(
            'id_sub_criteria',
            'weight',
            'attribute'
            )
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya');
        $last_cri = DB::table("presences")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'presences.id_sub_criteria')
        ->select(
            'presences.id_sub_criteria AS id_sub_criteria',
            'sub_criterias.weight AS weight',
            'sub_criterias.attribute AS attribute',
            )
        ->groupBy(
            'id_sub_criteria',
            'weight',
            'attribute'
            )
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya')
        ->union($first_cri)
        ->get();
        $criterias = $last_cri;
        //$criterias = SubCriteria::get();

        $first_inp = Performance::with('subcriteria', 'officer')
        ->where('id_period', $period)
        ->whereHas('subcriteria', function($query){
            $query->where('need', 'Ya');
        })
        ->whereDoesntHave('officer', function($query){
            $query->where('is_lead', 'Yes');
        });
        $last_inp = Presence::with('subcriteria')
        ->where('id_period', $period)
        ->whereHas('subcriteria', function($query){
            $query->where('need', 'Ya');
        })
        ->whereDoesntHave('officer', function($query){
            $query->where('is_lead', 'Yes');
        })
        ->union($first_inp)
        ->getQuery()->get();
        $inputs = $last_inp;

        //FIND MIN DAN MAX
        foreach($criterias as $crit => $value1){
            foreach($inputs as $input => $value2){
                if($value1->id_sub_criteria == $value2->id_sub_criteria){
                    if($value1->attribute == 'Benefit'){
                        $minmax[$value1->id_sub_criteria][] = $value2->input;
                    }elseif($value1->attribute == 'Cost'){
                        $minmax[$value1->id_sub_criteria][] = $value2->input;
                    }
                }
            }
        }
        //dd($minmax);

        //NORMALIZATION
        foreach($inputs as $input => $value1){
            foreach($criterias as $crit => $value2){
                if($value2->id_sub_criteria == $value1->id_sub_criteria){
                    if($value2->attribute == 'Benefit'){
                        $normal[$value1->id_officer][$value2->id_sub_criteria] = $value1->input / (max($minmax[$value2->id_sub_criteria]) ?: 1);
                    }elseif($value2->attribute == 'Cost'){
                        if(min($minmax[$value2->id_sub_criteria]) == 0){
                            if($value1->input == 0){
                                $normal[$value1->id_officer][$value2->id_sub_criteria] = 0.5 / 0.5;
                            }else{
                                $normal[$value1->id_officer][$value2->id_sub_criteria] = 0.5 / ($value1->input ?: 1);
                            }
                        }else{
                            $normal[$value1->id_officer][$value2->id_sub_criteria] = (min($minmax[$value2->id_sub_criteria]) ?: 1) / ($value1->input ?: 1);
                        }
                        //$normal[$value1->id_officer][$value2->id_sub_criteria] = (min($minmax[$value2->id_sub_criteria]) ?: 1) / $value1->input;
                    }
                }
            }
        }
        //dd($normal);

        //MATRIX
        foreach($inputs as $input => $value1){
            foreach($criterias as $crit => $value2){
                if($value2->id_sub_criteria == $value1->id_sub_criteria){
                    $mxin[$value1->id_officer][$value2->id_sub_criteria] = $normal[$value1->id_officer][$value2->id_sub_criteria] * $value2->weight;
                }
            }
        }
        //dd($mxin);

        $mx_hasil = $mxin; //$ranking = $normal;

        foreach($normal as $n => $value1){
            $mx_hasil[$n][] = array_sum($mxin[$n]); //$normal[$n][] = array_sum($rank[$n]);
            $matrix[$n] = array_sum($mxin[$n]); //$normal[$n][] = array_sum($rank[$n]);

            if(DB::table('scores')->where('id_period', $period)->where('id_officer', $n)->count() == 0){
                $str_officer = substr($n, 4);
                $str_year = substr($period, -5);
                $id_score = "SCR-".$str_year.'-'.$str_officer;

                DB::table('scores')->insert([
                    //'id_score'=>$id_score,
                    'id_officer'=>$n,
                    'id_period'=>$period,
                    'final_score'=>$matrix[$n],
                    'status'=>'Pending'
                ]);


                $score_bag = Score::with('officer')->whereHas('officer', function($query)
                {
                    $query->with('department')->whereHas('department', function($query)
                    {
                        $query->with('part')->whereHas('part', function($query)
                        {
                            $query->where('name', 'Bagian Umum');
                        });
                    });
                });
                if($score_bag->count() > 4){
                    $score_bag->orderBy('final_score', 'DESC')->take($score_bag->count())->skip(4)->get()->each(function($row){ $row->delete();});
                }

                $dep_teknis = Department::with('part')->whereHas('part', function($query)
                {
                    $query->where('name', 'Tim Teknis');
                })->get();
                foreach($dep_teknis as $dep){
                    $score_tek = Score::with('officer')->whereHas('officer', function($query) use($dep)
                    {
                        $query->with('department')->whereHas('department', function($query) use($dep){
                            $query->where('id_department', $dep->id_department);
                        });
                    });
                    if($score_tek->count() > 2){
                        $score_tek->orderBy('final_score', 'DESC')->take($score_tek->count())->skip(2)->get()->each(function($row){ $row->delete();});
                    }
                }
            }else{
                //SKIP
            }
        }
        arsort($matrix);

        Presence::with('officer')
        ->where('id_period', $period)
        ->whereIn('status', ['Pending', 'Need Fix'])
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'In Review'
        ]);

        Presence::with('officer')
        ->where('id_period', $period)
        ->whereIn('status', ['Pending', 'Need Fix'])
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'Yes');
        })
        ->update([
            'status'=>'Not Included'
        ]);

        Performance::with('officer')
        ->where('id_period', $period)
        ->whereIn('status', ['Pending', 'Need Fix'])
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'In Review'
        ]);

        Performance::with('officer')
        ->where('id_period', $period)
        ->whereIn('status', ['Pending', 'Need Fix'])
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'Yes');
        })
        ->update([
            'status'=>'Not Included'
        ]);

        return redirect()->route('admin.inputs.scores.index')->with('success','Ambil Data Berhasil')->with('code_alert', 1);
    }

    public function yes($id)
    {
        $result = Score::where('id', $id)->first();
        $period = Period::where('id_period', $result->id_period)->first()->id_period;
        $officer = Officer::where('id_officer', $result->id_officer)->first()->id_officer;
        $name = Officer::where('id_officer', $result->id_officer)->first()->name;

        Score::where('id', $id)->update([
            'status'=>'Accepted'
        ]);

        Presence::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Final'
        ]);

        Performance::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Final'
        ]);

        return redirect()->route('admin.inputs.scores.index')->with('success','Persetujuan Berhasil. Data dari pegawai ('. $name .') telah disetujui')->with('code_alert', 1);
    }

    public function yesall($id)
    {
        Score::where('id_period', $id)->update([
            'status'=>'Accepted'
        ]);

        Presence::with('officer')->where('id_period', $id)
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'Final'
        ]);

        Performance::with('officer')->where('id_period', $id)
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'Final'
        ]);

        return redirect()->route('admin.inputs.scores.index')->with('success','Persetujuan Berhasil. Data dari seluruh pegawai telah disetujui')->with('code_alert', 1);
    }

    public function no($id)
    {
        $result = Score::where('id', $id)->first();
        $period = Period::where('id_period', $result->id_period)->first()->id_period;
        $officer = Officer::where('id_officer', $result->id_officer)->first()->id_officer;
        $name = Officer::where('id_officer', $result->id_officer)->first()->name;

        Score::where('id', $id)->update([
            'status'=>'Rejected'
        ]);

        Presence::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Need Fix'
        ]);

        Performance::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Need Fix'
        ]);

        return redirect()->route('admin.inputs.scores.index')->with('success','Penolakan Berhasil. Data dari pegawai ('. $name .') telah dikembalikan')->with('code_alert', 1);
    }

    public function noall($id)
    {
        Score::where('id_period', $id)->update([
            'status'=>'Rejected'
        ]);

        Presence::with('officer')->where('id_period', $id)
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'Need Fix'
        ]);

        Performance::with('officer')->where('id_period', $id)
        ->whereHas('officer', function($query){
            $query->where('is_lead', 'No');
        })
        ->update([
            'status'=>'Need Fix'
        ]);

        return redirect()->route('admin.inputs.scores.index')->with('success','Penolakan Berhasil. Data dari seluruh pegawai telah dikembalikan')->with('code_alert', 1);
    }

    public function finish($period)
    {
        $scores = Score::with('officer')->where('id_period', $period)->orderBy('final_score', 'DESC')->offset(0)->limit(3)->get();

        foreach($scores as $score){
            $criterias = VoteCriteria::get();
            $str_officer = substr($score->id_officer, 4);
            $str_year = substr($score->id_period, -5);
            $id_vote = "VTE-".$str_year.'-'.$str_officer;

            foreach($criterias as $criteria){
                Vote::insert([
                    //'id_vote'=>$id_vote,
                    'id_officer'=>$score->id_officer,
                    'id_period'=>$score->id_period,
                    'id_vote_criteria'=>$criteria->id_vote_criteria,
                    'votes'=>'0',
                    //'final_score'=>$score->final_score,
                ]);
            }
        }

        $scores1 = Score::where('id_period', $period)->get();
        foreach($scores1 as $score){
            $getperiod1 = Period::where('id_period', $score->id_period)->first();
            $getofficer1 = Officer::where('id_officer', $score->id_officer)->first();
            $getdepartment1 = Department::with('officer')->whereHas('officer', function($query) use($score){
                $query->where('id_officer', $score->id_officer);
            })->first();
            HistoryScore::insert([
                'id_period'=>$getperiod1->id_period,
                'period_name'=>$getperiod1->name,
                'id_officer'=>$getofficer1->id_officer,
                'officer_name'=>$getofficer1->name,
                'officer_department'=>$getdepartment1->name,
                'final_score'=>$score->final_score,
            ]);
        }

        $presences = Presence::where('id_period', $period)->get();
        foreach($presences as $presence){
            $getuser2 = User::where('id_officer', $presence->id_officer)->first();
            $getperiod2 = Period::where('id_period', $presence->id_period)->first();
            $getofficer2 = Officer::where('id_officer', $presence->id_officer)->first();
            $getdepartment2 = Department::with('officer')->whereHas('officer', function($query) use($presence){
                $query->where('id_officer', $presence->id_officer);
            })->first();
            $getcriteria2 = Criteria::with('subcriteria')->whereHas('subcriteria', function($query) use($presence){
                $query->where('id_sub_criteria', $presence->id_sub_criteria);
            })->first();
            $getsubcriteria2 = SubCriteria::where('id_sub_criteria', $presence->id_sub_criteria)->first();

            if(empty($getuser2->part) || $getuser2->part == 'Admin'){
                $is_lead = 'No';
            }else{
                $is_lead = 'Yes';
            }

            HistoryPresence::insert([
                'id_period'=>$getperiod2->id_period,
                'period_name'=>$getperiod2->name,
                'id_officer'=>$getofficer2->id_officer,
                'officer_name'=>$getofficer2->name,
                'officer_department'=>$getdepartment2->name,
                'id_criteria'=>$getcriteria2->id_criteria,
                'criteria_name'=>$getcriteria2->name,
                'id_sub_criteria'=>$getsubcriteria2->id_sub_criteria,
                'sub_criteria_name'=>$getsubcriteria2->name,
                'weight'=>$getsubcriteria2->weight,
                'attribute'=>$getsubcriteria2->attribute,
                'is_lead'=>$is_lead,
                'input'=>$presence->input,
            ]);
        }

        $performances = Performance::where('id_period', $period)->get();
        foreach($performances as $performance){
            $getuser3 = User::where('id_officer', $performance->id_officer)->first();
            $getperiod3 = Period::where('id_period', $performance->id_period)->first();
            $getofficer3 = Officer::where('id_officer', $performance->id_officer)->first();
            $getdepartment3 = Department::with('officer')->whereHas('officer', function($query) use($performance){
                $query->where('id_officer', $performance->id_officer);
            })->first();
            $getcriteria3 = Criteria::with('subcriteria')->whereHas('subcriteria', function($query) use($performance){
                $query->where('id_sub_criteria', $performance->id_sub_criteria);
            })->first();
            $getsubcriteria3 = SubCriteria::where('id_sub_criteria', $performance->id_sub_criteria)->first();

            if(empty($getuser3->part) || $getuser3->part == 'Admin'){
                $is_lead = 'No';
            }else{
                $is_lead = 'Yes';
            }

            HistoryPerformance::insert([
                'id_period'=>$getperiod3->id_period,
                'period_name'=>$getperiod3->name,
                'id_officer'=>$getofficer3->id_officer,
                'officer_name'=>$getofficer3->name,
                'officer_department'=>$getdepartment3->name,
                'id_criteria'=>$getcriteria3->id_criteria,
                'criteria_name'=>$getcriteria3->name,
                'id_sub_criteria'=>$getsubcriteria3->id_sub_criteria,
                'sub_criteria_name'=>$getsubcriteria3->name,
                'weight'=>$getsubcriteria3->weight,
                'attribute'=>$getsubcriteria3->attribute,
                'is_lead'=>$is_lead,
                'input'=>$performance->input,
            ]);
        }

        Period::where('id_period', $period)->update([
            'status'=>'Voting',
        ]);

        return redirect()->route('admin.inputs.scores.index')->with('success','Data berhasil dikunci')->with('code_alert', 1);
    }
}
