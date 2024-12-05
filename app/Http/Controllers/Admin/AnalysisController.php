<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\HistoryInput;
use App\Models\HistoryInputRAW;
use App\Models\HistoryScore;
use App\Models\Input;
use App\Models\InputRAW;
use App\Models\Log;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Result;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Setting;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        //GET DATA
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get(); //GET PERIODS
        //$latest = Period::whereNot('progress_status', 'Skipped')->whereNot('progress_status', 'Pending')->latest()->first();
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET RUNNING PERIOD

        //GET DATA FOR PERIOD PICKER
        $h_years = HistoryInput::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->get(); //GET PREVIOUS PERIOD IN YEAR
        $h_months = HistoryInput::select('id_period', 'period_year', 'period_name')->groupBy('id_period', 'period_year', 'period_name')->orderBy('id_period', 'ASC')->get(); //GET PREVIOUS PERIOD

        //RETURN TO VIEW
        return view('Pages.Admin.analysis', compact('periods', 'latest_per', 'h_years', 'h_months'));
    }

    public function saw()
    {
        //GET DATA
        //$periods = Period::orderBy('id_period', 'ASC')->whereNot('progress_status', 'Skipped')->whereNot('progress_status', 'Pending')->get();
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get(); //GET PERIODS
        $subcriterias = Criteria::with('category')->get(); //GET SUB CRITERIAS
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })->get(); //GET OFFICERS (WITHOUT KEPALA BPS AND DEVELOPER)

        //GET DATA FOR PERIOD PICKER
        $h_years = HistoryInput::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->get(); //GET PREVIOUS PERIOD IN YEAR
        $h_months = HistoryInput::select('id_period', 'period_year', 'period_name')->groupBy('id_period', 'period_year', 'period_name')->orderBy('id_period', 'ASC')->get(); //GET PREVIOUS PERIOD

        //LATEST PERIOD
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET RUNNING PERIOD

        //GET DATA FOR SORT
        $setting = Setting::where('id_setting', 'STG-002')->first()->value; //GET CURRENT VALUE SETTING
        $set_crit = Criteria::where('id_criteria', $setting)->first(); //SET CRITERIA SETTING FOR INFO ONLY
        //$ckp = InputRAW::where('id_period', $latest_per->id_period)->where('id_criteria', $crit_ckp->id_criteria)->get();

        //VERIFICATION
        //CHECK EMPTY DATA
        if(!empty($latest_per)){
            if(Input::where('id_period', $latest_per->id_period)->count() == 0){ //IF NO DATA
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Analisis',
                    'progress'=>'View',
                    'result'=>'Error',
                    'descriptions'=>'Analisis Gagal (Tidak Ada Data) ('.$latest_per->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()->route('admin.analysis.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
            }else{
                if($latest_per->import_status == 'Not Clear'){ //IF NOT CONVERTED
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Analisis',
                        'progress'=>'View',
                        'result'=>'Error',
                        'descriptions'=>'Analisis Gagal (Belum Dikonversi) ('.$latest_per->name.')',
                    ]);

                    //RETURN TO VIEW
                    return redirect()->route('admin.analysis.index')->with('fail','Terdapat nilai yang belum dikonversi. Silahkan lakukan konversi data nilai terlebih dahulu.');
                }elseif($latest_per->import_status == 'Few Clear'){ //IF FEW CONVERTED
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Analisis',
                        'progress'=>'View',
                        'result'=>'Error',
                        'descriptions'=>'Analisis Gagal (Sebagian Dikonversi) ('.$latest_per->name.')',
                    ]);

                    //RETURN TO VIEW
                    return redirect()->route('admin.analysis.index')->with('fail','Terdapat beberapa nilai tidak dapat dikonversi. Silahkan cek kembali Data Crips di masing-masing Kriteria.');
                }elseif($subcriterias->sum('weight')*100 > 100){ //IF WEIGHT MORE THAN 100%
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Analisis',
                        'progress'=>'View',
                        'result'=>'Error',
                        'descriptions'=>'Analisis Gagal (Bobot Melebihi 100%) ('.$latest_per->name.')',
                    ]);

                    //RETURN TO VIEW
                    return redirect()->route('admin.analysis.index')->with('fail','Total Bobot melebihi 100%. Silahkan cek kembali Kriteria yang terdaftar.');
                }elseif($subcriterias->sum('weight')*100 <= 99){ //IF WEIGHT LESS THAN 100%
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Analisis',
                        'progress'=>'View',
                        'result'=>'Error',
                        'descriptions'=>'Analisis Gagal (Bobot Belum Mencapai 100%) ('.$latest_per->name.')',
                    ]);

                    //RETURN TO VIEW
                    return redirect()->route('admin.analysis.index')->with('fail','Total Bobot belum mencapai 100%. Silahkan cek kembali Kriteria yang terdaftar.');
                }else{ //CHECK OFFICER HAS DATA OR NOT
                    foreach ($officers as $officer) {
                        if(Input::where('id_period', $latest_per->id_period)->where('id_officer', $officer->id_officer)->count() == 0){ //IF OFFICER HAS NO DATA
                            //CREATE A LOG
                            Log::create([
                                'id_user'=>Auth::user()->id_user,
                                'activity'=>'Analisis',
                                'progress'=>'View',
                                'result'=>'Error',
                                'descriptions'=>'Analisis Gagal (Belum Dinilai Sepenuhnya ('.$officer->name.') ('.$latest_per->name.'))',
                            ]);

                            //RETURN TO VIEW
                            return redirect()->route('admin.analysis.index')->with('fail','Terdapat pegawai yang belum dinilai sepenuhnya. Silahkan cek kembali di halaman Input Data. ('.$officer->id_officer.')');
                        }else{ //IF OFFICER HAS A FEW DATA
                            foreach ($subcriterias as $subcriteria) {
                                if(Input::where('id_period', $latest_per->id_period)->where('id_officer', $officer->id_officer)->where('id_criteria', $subcriteria->id_criteria)->count() == 0) {
                                    //CREATE A LOG
                                    Log::create([
                                        'id_user'=>Auth::user()->id_user,
                                        'activity'=>'Analisis',
                                        'progress'=>'View',
                                        'result'=>'Error',
                                        'descriptions'=>'Analisis Gagal (Hanya Dinilai Sebagian ('.$officer->name.') ('.$subcriteria->name.'))',
                                    ]);

                                    //RETURN TO VIEW
                                    return redirect()->route('admin.analysis.index')->with('fail','Terdapat pegawai yang hanya dinilai sebagian. Silahkan cek kembali di halaman Input Data. ('.$officer->id_officer.') ('.$subcriteria->id_criteria.')');
                                }else{ //ALL CLEAR
                                    //CLEAR
                                }
                            }
                        }
                    }
                }
            }
        }else{ //IF NO RUNNING PERIOD
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Analisis',
                'progress'=>'View',
                'result'=>'Error',
                'descriptions'=>'Analisis Gagal (Periode Belum Jalan)',
            ]);

            //RETURN TO VIEW
            return redirect()->route('admin.analysis.index')->with('fail','Tidak ada periode yang sedang berjalan.');
        }

        //SAW ANALYSIS
        //GET ALTERNATIVE
        $alternatives = Input::with('criteria', 'officer')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $latest_per->id_period)
        ->whereHas('officer', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->getQuery()->get(); //GET OFFICERS AS ALTERNATIVES

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
        //->where('criterias.need', 'Ya')
        ->get(); //GET CRITERIAS FROM INPUTS
        //$criterias = Criteria::get();

        //GET INPUT
        $inputs = Input::with('criteria', 'officer')
        ->where('id_period', $latest_per->id_period)
        ->whereHas('officer', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->getQuery()->get(); //GET INPUTS

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

        //SUM ALL MATRIX
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

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Analisis',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Analisis Berhasil ('.$latest_per->name.')',
        ]);

        //return view('Pages.Admin.Analysis.saw', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix', 'periods'));

        //RETURN TO VIEW
        return view('Pages.Admin.analysis', compact('subcriterias', 'officers', 'alternatives', 'set_crit','criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix', 'periods', 'latest_per', 'h_years', 'h_months'));
    }

    public function history_saw($period)
    {
        //GET DATA
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->get(); //GET PREVIOUS PERIODS
        $subcriterias = HistoryInput::select('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->where('id_period', $period)->get(); //GET PREVIOUS SUB CRITERIAS
        $officers = HistoryInput::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_position')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_position')->where('id_period', $period)->get();  //GET PREVIOUS OFFICERS

        //SELECTED PERIOD
        $select_period = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->where('id_period', $period)->orderBy('id_period', 'ASC')->first(); //GET SELECTED OLD PERIOD

        //LATEST PERIOD
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD

        //GET DATA FOR PERIOD PICKER
        $h_years = HistoryInput::select('period_year')->groupBy('period_year')->orderBy('period_year', 'ASC')->get(); //GET PREVIOUS PERIODS IN YEAR
        $h_months = HistoryInput::select('id_period', 'period_year', 'period_name')->groupBy('id_period', 'period_year', 'period_name')->orderBy('id_period', 'ASC')->get(); //GET PREVIOUS PERIODS

        //GET DATA FOR SORT
        $setting = Setting::where('id_setting', 'STG-002')->first()->value; //GET CURRENT VALUE SETTING
        $h_set_crit = HistoryInput::select('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->where('id_criteria', $setting)->first(); //SET CRITERIA SETTING FOR INFO ONLY
        //$history_ckp = HistoryInputRAW::where('id_period', $period)->where('id_criteria', $h_crit_ckp->id_criteria)->get();

        //SAW ANALYSIS
        //GET ALTERNATIVE
        $alternatives = HistoryInput::with('criteria', 'officer')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        //->where('is_lead', 'No')
        ->getQuery()->get(); //GET PREVIOUS OFFICERS AS ALTERNATIVES

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
        ->get(); //GET CRITERIAS FROM OLD INPUTS
        //$criterias = Criteria::get();

        //GET INPUT
        $inputs = HistoryInput::with('criteria')
        ->where('id_period', $period)
        //->where('is_lead', 'No')
        ->getQuery()->get(); //GET OLD INPUTS

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

        //SUM ALL MATRIX
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

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Analisis',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Analisis Berhasil ('.$select_period->name.')',
        ]);

        //return view('Pages.Admin.Analysis.saw', compact('subcriterias', 'officers', 'alternatives', 'criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix', 'periods'));

        //RETURN TO VIEW
        return view('Pages.Admin.analysis', compact('select_period', 'subcriterias', 'officers', 'h_set_crit', 'alternatives', 'criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix', 'periods', 'latest_per', 'h_years', 'h_months'));
    }
}
