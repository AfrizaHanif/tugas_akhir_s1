<?php

namespace App\Http\Controllers\Admin\Beta;

use App\Http\Controllers\Controller;
use App\Models\BetaPerformance;
use App\Models\BetaPresence;
use App\Models\BetaResult;
use App\Models\Officer;
use App\Models\Period;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->get();
        $results = BetaResult::with('officer')->orderBy('final_score', 'DESC')->get();
        $officers = Officer::with('department')->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})->get();
        $performances = BetaPerformance::get();
        $presences = BetaPresence::get();
        $status = BetaPresence::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
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
        return view('Pages.Admin.result', compact('periods', 'results', 'officers', 'performances', 'presences', 'status', 'countprs', 'countprf', 'subcritprs', 'subcritprf'));
    }

    public function get($period)
    {
        $periods = Period::orderBy('id_period', 'ASC')->get();
        $subcriterias = SubCriteria::with('criteria')->get();
        $officers = Officer::with('department')->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})->get();

        //VERIFICATION
        if(BetaPresence::where('id_period', $period)->count() == 0 || BetaPerformance::where('id_period', $period)->count() == 0){
            return redirect()->route('results.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
        }else{
            foreach ($officers as $officer) {
                if(BetaPresence::where('id_officer', $officer->id_officer)->count() == 0 && BetaPerformance::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('results.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan lihat di halaman input pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(BetaPresence::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('results.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Kehadiran. Silahkan lihat di halaman input Data Kehadiran pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(BetaPerformance::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('results.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Prestasi Kerja. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }else{
                    foreach ($subcriterias as $subcriteria) {
                        if(BetaPresence::where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0 && BetaPerformance::where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0) {
                            return redirect()->route('results.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang hanya dinilai sebagian. ('.$officer->id_officer.') ('.$subcriteria->id_sub_criteria.')');
                        }else{
                            //CLEAR
                        }
                    }
                }
            }
        }

        $check = DB::table('beta_results')->where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Rejected');
        if($check->exists()){
            $check->delete();
        }

        $first_alt = DB::table("beta_performances")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'beta_performances.id_sub_criteria')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya');
        $last_alt = DB::table("beta_presences")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'beta_presences.id_sub_criteria')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya')
        ->union($first_alt)
        ->get();
        $alternatives = $last_alt;

        $first_cri = DB::table("beta_performances")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'beta_performances.id_sub_criteria')
        ->select(
            'beta_performances.id_sub_criteria AS id_sub_criteria',
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
        $last_cri = DB::table("beta_presences")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'beta_presences.id_sub_criteria')
        ->select(
            'beta_presences.id_sub_criteria AS id_sub_criteria',
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

        $first_inp = DB::table("beta_performances")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'beta_performances.id_sub_criteria')
        ->where('id_period', $period)
        ->where('sub_criterias.need', 'Ya');
        $last_inp = DB::table("beta_presences")
        ->join('sub_criterias', 'sub_criterias.id_sub_criteria', '=', 'beta_presences.id_sub_criteria')
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

            if(DB::table('beta_results')->where('id_period', $period)->where('id_officer', $n)->count() == 0){
                DB::table('beta_results')->insert([
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

        BetaPresence::where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Need Fix')->update([
            'status'=>'In Review'
        ]);

        BetaPerformance::where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Need Fix')->update([
            'status'=>'In Review'
        ]);

        return redirect()->route('results.index')->with('success','Ambil Data Berhasil');
    }

    public function yes($id)
    {
        $result = BetaResult::where('id', $id)->first();
        $period = Period::where('id_period', $result->id_period)->first()->id_period;
        $officer = Officer::where('id_officer', $result->id_officer)->first()->id_officer;
        $name = Officer::where('id_officer', $result->id_officer)->first()->name;

        BetaResult::where('id', $id)->update([
            'status'=> 'Accepted'
        ]);

        BetaPresence::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Final'
        ]);

        BetaPerformance::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Final'
        ]);

        return redirect()->route('results.index')->with('success','Persetujuan Berhasil. Data dari pegawai ('. $name .') telah disetujui');
    }

    public function no($id)
    {
        $result = BetaResult::where('id', $id)->first();
        $period = Period::where('id_period', $result->id_period)->first()->id_period;
        $officer = Officer::where('id_officer', $result->id_officer)->first()->id_officer;
        $name = Officer::where('id_officer', $result->id_officer)->first()->name;

        BetaResult::where('id', $id)->update([
            'status'=> 'Rejected'
        ]);

        BetaPresence::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Need Fix'
        ]);

        BetaPerformance::where('id_period', $period)->where('id_officer', $officer)->update([
            'status'=>'Need Fix'
        ]);

        return redirect()->route('results.index')->with('success','Penolakan Berhasil. Data dari pegawai ('. $name .') telah dikembalikan');
    }
}
