<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\HistoryInput;
use App\Models\HistoryScore;
use App\Models\Input;
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
        //GET DATA
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        //$latest = Period::whereNot('status', 'Skipped')->whereNot('status', 'Pending')->latest()->first();
        $latest_per = Period::where('status', 'Scoring')->latest()->first();

        //RETURN TO VIEW
        return view('Pages.Admin.analysis', compact('periods', 'latest_per'));
    }

    public function saw()
    {
        //GET DATA
        //$periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        $subcriterias = Criteria::with('category')->get();
        $officers = Officer::where('is_lead', 'No')->get();

        //LATEST PERIODE
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();

        //VERIFICATION
        //CHECK EMPTY DATA
        if(!empty($latest_per)){
            if(Input::where('id_period', $latest_per->id_period)->count() == 0){
                return redirect()->route('admin.analysis.saw.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
            }else{
                foreach ($officers as $officer) {
                    if(Input::where('id_period', $latest_per->id_period)->where('id_officer', $officer->id_officer)->count() == 0){ //IF OFFICER HAS NO DATA
                        return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan cek kembali di halaman Input Data. ('.$officer->id_officer.')');
                    }else{ //IF OFFICER HAS A FEW DATA
                        foreach ($subcriterias as $subcriteria) {
                            if(Input::where('id_period', $latest_per->id_period)->where('id_officer', $officer->id_officer)->where('id_criteria', $subcriteria->id_criteria)->count() == 0) {
                                return redirect()->route('admin.analysis.saw.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan cek kembali di halaman Input Data. ('.$officer->id_officer.') ('.$subcriteria->id_criteria.')');
                            }else{
                                //CLEAR
                            }
                        }
                    }
                }
            }
        }else{
            return redirect()->route('admin.analysis.saw.index')->with('fail','Tidak ada periode yang sedang berjalan.');
        }

        //SAW ANALYSIS
        //GET ALTERNATIVE
        $alternatives = Input::with('criteria', 'officer')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $latest_per->id_period)
        ->whereHas('criteria', function($query){
            $query->where('need', 'Ya');
        })
        ->whereDoesntHave('officer', function($query){
            $query->with('user')->whereHas('user', function($query){
                $query->where('part', 'KBPS');
            });
        })
        ->getQuery()->get();

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
        ->where('id_period', $latest_per->id_period)
        ->where('criterias.need', 'Ya')
        ->get();
        //$criterias = Criteria::get();

        //GET INPUT
        $inputs = Input::with('criteria')
        ->where('id_period', $latest_per->id_period)
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

        //RETURN TO VIEW
        return view('Pages.Admin.analysis', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix', 'periods', 'latest_per'));
    }

    public function history_saw($period)
    {
        //GET DATA
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        $subcriterias = HistoryInput::select('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->where('id_period', $period)->get();
        $officers = HistoryInput::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->where('id_period', $period)->get();

        //LATEST PERIODE
        $latest_per = Period::where('status', 'Scoring')->latest()->first();

        //SAW ANALYSIS
        //GET ALTERNATIVE
        $alternatives = HistoryInput::with('criteria', 'officer')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('is_lead', 'No')
        ->getQuery()->get();

        //GET CRITERIA
        $criterias = DB::table("history_inputs")
        ->select(
            'id_criteria',
            'weight',
            'attribute',
            )
        ->groupBy(
            'id_criteria',
            'weight',
            'attribute'
            )
        ->where('id_period', $period)
        ->get();
        //$criterias = Criteria::get();

        //GET INPUT
        $inputs = HistoryInput::with('criteria')
        ->where('id_period', $period)
        ->where('is_lead', 'No')
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

        //RETURN TO VIEW
        return view('Pages.Admin.analysis', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix', 'periods', 'latest_per'));
    }

    public function wp()
    {
        //GET DATA
        //$periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        $subcriterias = Criteria::with('category')->get();
        $critcount = Criteria::select('level')->sum('level');
        $officers = Officer::where('is_lead', 'No')->get();

        //LATEST PERIODE
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Voting')->latest()->first();

        //VERIFICATION
        //CHECK EMPTY DATA
        if(!empty($latest_per)){
            if(Input::where('id_period', $latest_per->id_period)->count() == 0){
                return redirect()->route('admin.analysis.wp.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
            }else{
                foreach ($officers as $officer) {
                    if(Input::where('id_period', $latest_per->id_period)->where('id_officer', $officer->id_officer)->count() == 0){ //IF OFFICER HAS NO DATA
                        return redirect()->route('admin.analysis.wp.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan cek kembali di halaman Input Data. ('.$officer->id_officer.')');
                    }else{ //IF OFFICER HAS A FEW DATA
                        foreach ($subcriterias as $subcriteria) {
                            if(Input::where('id_period', $latest_per->id_period)->where('id_officer', $officer->id_officer)->where('id_criteria', $subcriteria->id_criteria)->count() == 0) {
                                return redirect()->route('admin.analysis.wp.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan cek kembali di halaman Input Data. ('.$officer->id_officer.') ('.$subcriteria->id_criteria.')');
                            }else{
                                //CLEAR
                            }
                        }
                    }
                }
            }
        }else{
            return redirect()->route('admin.analysis.wp.index')->with('fail','Tidak ada periode yang sedang berjalan.');
        }

        //WP ANALYSIS
        //GET ALTERNATIVE
        $alternatives = Input::with('criteria', 'officer')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $latest_per->id_period)
        ->whereHas('criteria', function($query){
            $query->where('need', 'Ya');
        })
        ->whereDoesntHave('officer', function($query){
            $query->with('user')->whereHas('user', function($query){
                $query->where('part', 'KBPS');
            });
        })
        ->getQuery()->get();

        //GET CRITERIA
        $criterias = DB::table("inputs")
        ->join('criterias', 'criterias.id_criteria', '=', 'inputs.id_criteria')
        ->select(
            'inputs.id_criteria AS id_criteria',
            'criterias.weight AS weight',
            'criterias.attribute AS attribute',
            'criterias.level AS level',
            )
        ->groupBy(
            'id_criteria',
            'weight',
            'attribute',
            'level'
            )
        ->where('id_period', $latest_per->id_period)
        ->where('criterias.need', 'Ya')
        ->get();
        //$criterias = Criteria::get();

        //GET INPUT
        $inputs = Input::with('criteria')
        ->where('id_period', $latest_per->id_period)
        ->whereHas('criteria', function($query){
            $query->where('need', 'Ya');
        })
        ->whereDoesntHave('officer', function($query){
            $query->with('user')->whereHas('user', function($query){
                $query->where('part', 'KBPS');
            });
        })
        ->getQuery()->get();

        //PERSENTASE BOBOT (DISABLE IF USING WEIGHT DIRECTLY)
        //$persen = $criterias->level->count();
        foreach($criterias as $crit => $value1){
            $persen[$value1->id_criteria] = $value1->level / $critcount;
        }
        //dd($persen);

        //PERPANGKATAN
        //WEIGHT (DISABLE IF USING PERCENT)
        /*
        foreach($criterias as $crit => $value1){
            if($value1->attribute == 'Benefit'){
                $pangkat[$value1->id_criteria] = $value1->weight;
            }elseif($value1->attribute == 'Cost'){
                $pangkat[$value1->id_criteria] = -1 * $value1->weight;
            }
        }
        */
        //PERCENT (DISABLE IF USING WEIGHT)
        foreach($criterias as $crit => $value1){
            if($value1->attribute == 'Benefit'){
                $pangkat[$value1->id_criteria] = $persen[$value1->id_criteria];
            }elseif($value1->attribute == 'Cost'){
                $pangkat[$value1->id_criteria] = -1 * $persen[$value1->id_criteria];
            }
        }

        //PERHITUNGAN
        foreach($inputs as $input => $value1){
            foreach($criterias as $crit => $value2){
                if($value1->id_criteria == $value2->id_criteria){
                    if($value1->input == 0){
                        $square[$value1->id_officer][$value2->id_criteria] = (pow(0.5 , $pangkat[$value1->id_criteria]) ?: 1);
                    }else{
                        $square[$value1->id_officer][$value2->id_criteria] = (pow($value1->input , $pangkat[$value1->id_criteria]) ?: 1);
                    }
                    //$square[$value1->id_officer][$value2->id_criteria] = (pow($value1->input , $pangkat[$value1->id_criteria]) ?: 1);
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

        //RETURN TO VIEW
        return view('Pages.Admin.analysis', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'pangkat', 'square', 'v_hasil', 'v', 'periods', 'latest_per', 'critcount'));
    }

    public function history_wp($period)
    {
        //GET DATA
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get();
        $subcriterias = HistoryInput::select('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->where('id_period', $period)->get();
        $critcount = HistoryInput::select('id_officer', 'id_period', 'level')->groupBy('id_officer', 'id_period')->where('id_period', $period)->sum('level');
        $officers = HistoryInput::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->where('id_period', $period)->get();

        //LATEST PERIODE
        $latest_per = Period::where('status', 'Scoring')->latest()->first();

        //WP ANALYSIS
        //GET ALTERNATIVE
        $alternatives = HistoryInput::with('criteria', 'officer')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('is_lead', 'No')
        ->getQuery()->get();

        //GET CRITERIA
        $criterias = DB::table("history_inputs")
        ->select(
            'id_criteria',
            'weight',
            'attribute',
            'level',
            )
        ->groupBy(
            'id_criteria',
            'weight',
            'attribute',
            'level',
            )
        ->where('id_period', $period)
        ->get();
        //$criterias = Criteria::get();

        //GET INPUT
        $inputs = HistoryInput::where('id_period', $period)
        ->getQuery()->get();

        //PERSENTASE BOBOT (DISABLE IF USING WEIGHT DIRECTLY)
        //$persen = $criterias->level->count();
        foreach($criterias as $crit => $value1){
            $persen[$value1->id_criteria] = $value1->level / $critcount;
        }
        //dd($persen);

        //PERPANGKATAN
        //WEIGHT (DISABLE IF USING PERCENT)
        /*
        foreach($criterias as $crit => $value1){
            if($value1->attribute == 'Benefit'){
                $pangkat[$value1->id_criteria] = $value1->weight;
            }elseif($value1->attribute == 'Cost'){
                $pangkat[$value1->id_criteria] = -1 * $value1->weight;
            }
        }
        */
        //PERCENT (DISABLE IF USING WEIGHT)
        foreach($criterias as $crit => $value1){
            if($value1->attribute == 'Benefit'){
                $pangkat[$value1->id_criteria] = $persen[$value1->id_criteria];
            }elseif($value1->attribute == 'Cost'){
                $pangkat[$value1->id_criteria] = -1 * $persen[$value1->id_criteria];
            }
        }

        //PERHITUNGAN
        foreach($inputs as $input => $value1){
            foreach($criterias as $crit => $value2){
                if($value1->id_criteria == $value2->id_criteria){
                    if($value1->input == 0){
                        $square[$value1->id_officer][$value2->id_criteria] = (pow(0.5 , $pangkat[$value1->id_criteria]) ?: 1);
                    }else{
                        $square[$value1->id_officer][$value2->id_criteria] = (pow($value1->input , $pangkat[$value1->id_criteria]) ?: 1);
                    }
                    //$square[$value1->id_officer][$value2->id_criteria] = (pow($value1->input , $pangkat[$value1->id_criteria]) ?: 1);
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

        //RETURN TO VIEW
        return view('Pages.Admin.analysis', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'pangkat', 'square', 'v_hasil', 'v', 'periods', 'latest_per', 'critcount'));
    }
}
