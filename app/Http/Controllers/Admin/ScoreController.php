<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
use App\Models\SubCriteria;
use App\Models\Vote;
use App\Models\VoteCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $scores = Score::with('officer')->orderBy('final_score', 'DESC')->get();
        $officers = Officer::with('department', 'part')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
        ->get();
        $performances = Performance::get();
        $presences = Presence::get();
        $status = Presence::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $criterias = Criteria::with('subcriteria')->get();
        $allsubcriterias = SubCriteria::with('criteria')->get();
        $subcritprs = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprf = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $countprs = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->count();
        $countprf = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->count();
        return view('Pages.Admin.score', compact('periods', 'scores', 'officers', 'performances', 'presences', 'status', 'criterias', 'allsubcriterias', 'countprs', 'countprf', 'subcritprs', 'subcritprf'));
    }

    public function get($period)
    {
        $periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $subcriterias = SubCriteria::with('criteria')->get();
        $officers = Officer::with('department', 'part')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
        ->get();

        //VERIFICATION
        if(Presence::where('id_period', $period)->count() == 0 || Performance::where('id_period', $period)->count() == 0){
            return redirect()->route('inputs.scores.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
        }else{
            foreach ($officers as $officer) {
                if(Presence::where('id_officer', $officer->id_officer)->count() == 0 && Performance::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('inputs.scores.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan lihat di halaman input pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(Presence::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('inputs.scores.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Kehadiran. Silahkan lihat di halaman input Data Kehadiran pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(Performance::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('inputs.scores.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Prestasi Kerja. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }else{
                    foreach ($subcriterias as $subcriteria) {
                        if(Presence::where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0 && Performance::where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0) {
                            return redirect()->route('inputs.scores.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang hanya dinilai sebagian. ('.$officer->id_officer.') ('.$subcriteria->id_sub_criteria.')');
                        }else{
                            //CLEAR
                        }
                    }
                }
            }
        }

        $check = DB::table('scores')->where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Rejected');
        if($check->exists()){
            $check->delete();
        }

        $first_alt = DB::table("performances")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'performances.id_sub_criteria')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya');
        $last_alt = DB::table("presences")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'presences.id_sub_criteria')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya')
        ->union($first_alt)
        ->get();
        $alternatives = $last_alt;

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

        $first_inp = DB::table("performances")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'performances.id_sub_criteria')
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya');
        $last_inp = DB::table("presences")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'presences.id_sub_criteria')
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya')
        ->union($first_inp)
        ->get();
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
                        $normal[$value1->id_officer][$value2->id_sub_criteria] = $value1->input / max($minmax[$value2->id_sub_criteria]);
                    }elseif($value2->attribute == 'Cost'){
                        $normal[$value1->id_officer][$value2->id_sub_criteria] = min($minmax[$value2->id_sub_criteria]) / $value1->input;
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
            }else{
                //SKIP
            }
        }
        arsort($matrix);

        Presence::where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Need Fix')->update([
            'status'=>'In Review'
        ]);

        Performance::where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Need Fix')->update([
            'status'=>'In Review'
        ]);

        return redirect()->route('inputs.scores.index')->with('success','Ambil Data Berhasil');
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

        return redirect()->route('inputs.scores.index')->with('success','Persetujuan Berhasil. Data dari pegawai ('. $name .') telah disetujui');
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

        return redirect()->route('inputs.scores.index')->with('success','Penolakan Berhasil. Data dari pegawai ('. $name .') telah dikembalikan');
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
                ]);
            }
        }

        Period::where('id_period', $period)->update([
            'status'=>'Voting',
        ]);

        return redirect()->route('inputs.scores.index')->with('success','Data berhasil dikunci');
    }
}
