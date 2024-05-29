<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Department;
use App\Models\HistoryPerformance;
use App\Models\HistoryPresence;
use App\Models\HistoryScore;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\Result;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->whereIn('status', ['Voting', 'Finished'])->get();
        $per_years = Period::orderBy('id_period', 'ASC')->select('year')->groupBy('year')->get();
        $officers = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('user', function($query){$query->whereIn('part', ['KBU', 'KTT', 'KBPS']);})
        ->get();

        return view('Pages.Admin.report', compact('periods','per_years','officers'));
    }

    public function officers()
    {
        $parts = Part::whereNot('name', 'Developer')->get();
        $departments = Department::whereNot('name', 'Developer')->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();
        $file = 'RPT-Pegawai.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.officer', compact('parts', 'departments', 'officers'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function inpcompact($period)
    {
        $month = Period::where('id_period', $period)->first()->month;
        $year = Period::where('id_period', $period)->first()->year;
        $officers = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('user', function($query){$query->whereIn('part', ['KBU', 'KTT', 'KBPS']);})
        ->get();
        $performances = Performance::with('subcriteria')->where('id_period', $period)->get();
        $presences = Presence::with('subcriteria')->where('id_period', $period)->get();
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
        $file = 'RPT-Input-Comp-'.$month.'-'.$year.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.inpcomp', compact('month','year','officers','performances','presences','subcritprs','subcritprf','countprs','countprf'))
        ->setPaper('a4', 'landscape')
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function inpall($period)
    {
        $month = Period::where('id_period', $period)->first()->month;
        $year = Period::where('id_period', $period)->first()->year;
        $officers = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('user', function($query){$query->whereIn('part', ['KBU', 'KTT', 'KBPS']);})
        ->get();
        $performances = Performance::with('subcriteria')->where('id_period', $period)->get();
        $presences = Presence::with('subcriteria')->where('id_period', $period)->get();
        $subcritprs = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprf = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $file = 'RPT-Input-All-'.$month.'-'.$year.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.inpall', compact('month','year','officers','performances','presences','subcritprs','subcritprf'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function inpsingle($period, $id)
    {
        $month = Period::where('id_period', $period)->first()->month;
        $year = Period::where('id_period', $period)->first()->year;
        $officers = Officer::with('department')->where('id_officer', $id)->get();
        $performances = Performance::with('subcriteria')->where('id_period', $period)->get();
        $presences = Presence::with('subcriteria')->where('id_period', $period)->get();
        $subcritprs = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprf = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $file = 'RPT-Input-All-'.$month.'-'.$year.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.inpsingle', compact('month','year','officers','performances','presences','subcritprs','subcritprf'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function analysis($period)
    {
        $month = Period::where('id_period', $period)->first()->month;
        $year = Period::where('id_period', $period)->first()->year;

        $periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $subcriterias = SubCriteria::with('criteria')->get();
        $officers = HistoryPerformance::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->where('is_lead', 'No')->get();

        $first_alt = HistoryPerformance::select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('is_lead', 'No');
        $last_alt = HistoryPresence::select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('is_lead', 'No')
        ->union($first_alt)
        ->getQuery()->get();
        $alternatives = $last_alt;

        $first_cri = DB::table("history_performances")
        ->select(
            'id_sub_criteria',
            'weight',
            'attribute',
            )
        ->groupBy(
            'id_sub_criteria',
            'weight',
            'attribute'
            )
        ->where('id_period', $period);
        $last_cri = DB::table("history_presences")
        ->select(
            'id_sub_criteria',
            'weight',
            'attribute',
            )
        ->groupBy(
            'id_sub_criteria',
            'weight',
            'attribute'
            )
        ->where('id_period', $period)
        ->union($first_cri)
        ->get();
        $criterias = $last_cri;
        //$criterias = SubCriteria::get();

        $first_inp = HistoryPerformance::where('id_period', $period)->where('is_lead', 'No');
        $last_inp = HistoryPresence::where('id_period', $period)
        ->where('is_lead', 'No')
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
            /*
            DB::table('hasil_saw_desa')->insert([
                'id_officer'=>$n,
                'matrix'=>$matrix[$n],
            ]);
            */
        }
        arsort($matrix);
        //dd($mx_hasil);

        $file = 'RPT-Analysis-'.$year.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.analysis', compact('month', 'year', 'officers', 'alternatives', 'criterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix'))
        ->setPaper('a4', 'landscape')
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function result($period)
    {
        $month = Period::where('id_period', $period)->first()->month;
        $year = Period::where('id_period', $period)->first()->year;
        $results = Result::with('officer')->where('id_period', $period)->orderBy('final_score', 'DESC')->get();
        $file = 'RPT-Result-'.$year.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.result', compact('month','year','results'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }
}
