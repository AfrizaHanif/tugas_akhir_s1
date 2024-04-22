<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Result;
use App\Models\Officer;
use App\Models\Period;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();

        return view('Pages.Admin.Analysis.analysis', compact('periods'));
    }

    public function saw($period)
    {
        $periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $subcriterias = SubCriteria::with('criteria')->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
        ->get();

        //VERIFICATION
        if(Presence::where('id_period', $period)->count() == 0 || Performance::where('id_period', $period)->count() == 0){
            return redirect()->route('admin.analysis.saw.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
        }else{
            foreach ($officers as $officer) {
                if(Presence::where('id_officer', $officer->id_officer)->count() == 0 && Performance::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan lihat di halaman input pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(Presence::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Kehadiran. Silahkan lihat di halaman input Data Kehadiran pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(Performance::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Prestasi Kerja. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }else{
                    foreach ($subcriterias as $subcriteria) {
                        if(Presence::where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0 && Performance::where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0) {
                            return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang hanya dinilai sebagian. ('.$officer->id_officer.') ('.$subcriteria->id_sub_criteria.')');
                        }else{
                            //CLEAR
                        }
                    }
                }
            }
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
                        $normal[$value1->id_officer][$value2->id_sub_criteria] = $value1->input / (max($minmax[$value2->id_sub_criteria]) ?: 1);
                    }elseif($value2->attribute == 'Cost'){
                        $normal[$value1->id_officer][$value2->id_sub_criteria] = (min($minmax[$value2->id_sub_criteria]) ?: 1) / $value1->input;
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
            /*
            DB::table('hasil_saw_desa')->insert([
                'id_officer'=>$n,
                'matrix'=>$matrix[$n],
            ]);
            */
        }
        arsort($matrix);
        //dd($mx_hasil);

        //return view('Pages.Admin.Analysis.saw', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix', 'periods'));

        return view('Pages.Admin.Analysis.analysis', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix', 'periods'));
    }

    public function wp($period)
    {
        $periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $subcriterias = SubCriteria::with('criteria')->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
        ->get();

        //VERIFICATION
        if(Presence::where('id_period', $period)->count() == 0 || Performance::where('id_period', $period)->count() == 0){
            return redirect()->route('admin.analysis.saw.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
        }else{
            foreach ($officers as $officer) {
                if(Presence::where('id_officer', $officer->id_officer)->count() == 0 && Performance::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan lihat di halaman input pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(Presence::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Kehadiran. Silahkan lihat di halaman input Data Kehadiran pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }elseif(Performance::where('id_officer', $officer->id_officer)->count() == 0){
                    return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang belum dinilai di Data Prestasi Kerja. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang datanya belum terisi. ('.$officer->id_officer.')');
                }else{
                    foreach ($subcriterias as $subcriteria) {
                        if(Presence::where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0 && Performance::where('id_officer', $officer->id_officer)->where('id_sub_criteria', $subcriteria->id_sub_criteria)->count() == 0) {
                            return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan lihat di halaman input Data Prestasi Kerja pegawai mana yang hanya dinilai sebagian. ('.$officer->id_officer.') ('.$subcriteria->id_sub_criteria.')');
                        }else{
                            //CLEAR
                        }
                    }
                }
            }
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

        $critcount = SubCriteria::select('level')->sum('level');

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

        //PERSENTASE BOBOT
        /*
        //$persen = $criterias->level->count();
        foreach($criterias as $crit => $value1){
            $persen[$value1->id_sub_criteria] = $value1->level / $critcount;
        }
        //dd($persen);
        */

        //PERPANGKATAN

        foreach($criterias as $crit => $value1){
            if($value1->attribute == 'Benefit'){
                $pangkat[$value1->id_sub_criteria] = $value1->weight;
            }elseif($value1->attribute == 'Cost'){
                $pangkat[$value1->id_sub_criteria] = -1 * $value1->weight;
            }
        }

        /*
        foreach($criterias as $crit => $value1){
            if($value1->attribute == 'Benefit'){
                $pangkat[$value1->id_sub_criteria] = $persen[$value1->id_sub_criteria];
            }elseif($value1->attribute == 'Cost'){
                $pangkat[$value1->id_sub_criteria] = -1 * $persen[$value1->id_sub_criteria];
            }
        }
        */

        //PERHITUNGAN
        foreach($inputs as $input => $value1){
            foreach($criterias as $crit => $value2){
                if($value1->id_sub_criteria == $value2->id_sub_criteria){
                    $square[$value1->id_officer][$value2->id_sub_criteria] = pow($value1->input , $pangkat[$value1->id_sub_criteria]);
                }
            }
        }

        //S
        $v_hasil = $square;
        foreach($square as $sqrt1 => $value1){
            $v_hasil[$sqrt1][] = array_product($value1);
        }

        //V
        foreach($square as $sqrt1 => $value1){
            $s[$sqrt1] = array_product($value1);
        }
        foreach($square as $sqrt2 => $value1){
            $v_hasil[$sqrt2][] = $s[$sqrt2]/array_sum($s);
        }
        foreach($square as $sqrt2 => $value1){
            $v[$sqrt2] = $s[$sqrt2]/array_sum($s);
        }
        arsort($v);
        //dd($v_hasil);

        //return view('Pages.Admin.Analysis.wp', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'pangkat', 'square', 'v_hasil', 'v', 'periods'));

        return view('Pages.Admin.Analysis.analysis', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'pangkat', 'square', 'v_hasil', 'v', 'periods'));
    }
}
