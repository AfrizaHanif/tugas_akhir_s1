<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\HistoryInput;
use App\Models\HistoryScore;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Period;
use App\Models\SubTeam;
use App\Models\Team;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Fpdi;

class ReportController extends Controller
{
    public function officers()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $departments = Department::whereNot('name', 'Developer')->get();
        $teams = Team::get();
        $subteams = SubTeam::get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();

        //CREATE A REPORT
        $file = 'RPT-Pegawai.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.officer', compact('parts', 'teams', 'subteams', 'departments', 'officers'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function analysis($period)
    {
        //GET DATA
        $periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->where('id_period', $period)->first();
        $subcriterias = HistoryInput::select('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name', 'attribute', 'weight')->where('id_period', $period)->get();
        $officers = HistoryInput::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->where('id_period', $period)->where('is_lead', 'No')->get();
        //$prd_name = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->where('id_period', $period)->first()->period_name;

        $alternatives = HistoryInput::with('criteria', 'officer')
        ->select('id_officer')
        ->groupBy('id_officer')
        ->where('id_period', $period)
        ->where('is_lead', 'No')
        ->getQuery()->get();

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

        //CREATE A REPORT
        $file = 'RPT-Analysis-'.$periods->id_period.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.analysis', compact('periods', 'officers', 'alternatives', 'criterias', 'subcriterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix'))
        ->setPaper('a4', 'landscape')
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function result($period)
    {
        //GET DATA
        $periods = HistoryScore::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->where('id_period', $period)->first();
        $results = HistoryScore::where('id_period', $period)->orderBy('final_score', 'DESC')->get();

        //CREATE A REPORT
        $file = 'RPT-Result-'.$periods->id_period.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.result', compact('periods','results'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function certificate($period)
    {
        //GET DATA
        $periods = HistoryScore::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('id_period', 'ASC')->where('id_period', $period)->first();
        $results = HistoryScore::where('id_period', $period)->orderBy('final_score', 'DESC')->first();

        //PREPARING A CERTIFICATE
        $officer_name = $results->officer_name;
        $period_name = $periods->period_name;
        $now = Carbon::now()
        ->locale('id')
        ->settings(['formatFunction' => 'translatedFormat'])
        ->format('d F Y');
        $file = 'RPT-Certificate-'.$periods->id_period.'.pdf';
        $output = public_path().'/PDFs/'.$file;
        $source = public_path().'/PDFs/Default/New Certificate.pdf';

        //CREATE A CERTIFICATE (SWITCH TO ANOTHER FUNCTION)
        $this->fillPDF($file, $source, $output, $officer_name, $period_name, $now);

        //return response()->download($output, $file);
    }

    public function fillPDF($file, $source, $output, $officer_name, $period_name, $now){
        //SET TEMPLATE
        $fpdi = new Fpdi;
        $fpdi->setSourceFile($source);
        $template = $fpdi->importPage(1);
        $size = $fpdi->getTemplateSize($template);
        $fpdi->AddPage($size['orientation'],array($size['width'],$size['height']));
        $fpdi->useTemplate($template);

        //GET AND CUSTOMIZE OFFICER NAME
        $text1 = $officer_name;
        $fpdi->SetFont("Helvetica", "", 35);
        $fpdi->SetTextColor(25,26,25);
        $fpdi->SetXY(103, 103);
        $fpdi->Cell(75, 0, $text1, 0, 2, 'C');

        //GET AND CUSTOMIZE PERIOD NAME
        $text2 = $period_name;
        $fpdi->SetFont("Helvetica", "", 20);
        $fpdi->SetTextColor(25,26,25);
        $fpdi->SetXY(103, 126);
        $fpdi->Cell(75, 0, $text2, 0, 2, 'C');

        //GET AND CUSTOMIZE TODAY'S DATE
        $text3 = $now;
        $fpdi->SetFont("Helvetica", "", 17);
        $fpdi->SetTextColor(25,26,25);
        $fpdi->SetXY(149, 165);
        $fpdi->Cell(75, 0, $text3, 0, 2, 'L');

        //RETURN TO VIEW
        $fpdi->Output('F', $output);
        return $fpdi->Output('I', $file);
    }
}
