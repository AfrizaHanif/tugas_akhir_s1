<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryInput;
use App\Models\HistoryScore;
use App\Models\Log;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Part;
use App\Models\SubTeam;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Fpdi;

class ReportController extends Controller
{
    public function index()
    {
        //GET DATA
        $h_periods = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.id_period', 'periods.name')->groupBy('periods.id_period', 'periods.name')->orderBy('periods.id_period', 'ASC')->get(); //GET PREVIOUS PERIODS FROM OLD INPUTS
        $h_years = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.year')->groupBy('periods.year')->orderBy('periods.year', 'ASC')->get(); //GET PREVIOUS PERIODS IN YEAR FROM OLD INPUTS
        $h_team_years = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('history_inputs.id_sub_team', 'history_inputs.sub_team_1_name', 'periods.year')->groupBy('history_inputs.id_sub_team', 'history_inputs.sub_team_1_name', 'periods.year')->orderBy('periods.year', 'ASC')->get(); //GET PREVIOUS TEAMS IN YEAR FROM OLD INPUTS
        $h_months = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.year', 'periods.month', 'periods.num_month')->groupBy('periods.year', 'periods.month', 'periods.num_month')->orderBy('periods.year', 'DESC')->orderBy('periods.num_month', 'ASC')->get(); //GET PREVIOUS PERIODS IN MONTH FROM OLD INPUTS
        $h_subteams = HistoryScore::select('id_sub_team', 'sub_team_1_name')->groupBy('id_sub_team', 'sub_team_1_name')->whereNotIn('sub_team_1_name', ['Pimpinan BPS', 'Developer'])->get(); //GET PREVIOUS SUB TEAMS FROM OLD INPUTS
        $h_employees = HistoryInput::select('id_employee', 'employee_name')->groupBy('id_employee', 'employee_name')->get(); //GET PREVIOUS EMPLOYEES FROM OLD INPUTS
        $h_scores = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'periods.year', 'periods.month', 'periods.num_month', 'history_scores.id_sub_team', 'history_scores.sub_team_1_name')->groupBy('periods.id_period', 'periods.name', 'periods.year', 'periods.month', 'periods.num_month', 'history_scores.id_sub_team', 'history_scores.sub_team_1_name')->orderBy('periods.year', 'DESC')->orderBy('periods.year', 'DESC')->orderBy('periods.num_month', 'ASC')->get(); //GET PREVIOUS SCORES FROM OLD INPUTS
        /*
        $employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();
        */
        //$check_teams = HistoryScore::get();

        //UNUSED CODE
        if(Auth::user()->part != "Karyawan"){

        }else{

        }

        //RETURN TO VIEW
        return view('Pages.Admin.report', compact('h_periods', 'h_years', 'h_team_years', 'h_months', 'h_subteams', 'h_employees', 'h_scores'));
    }

    public function employees()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $teams = Team::get();
        $subteams = SubTeam::get();
        $employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('status', 'Active')
        ->get();

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Laporan',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Laporan Karyawan Berhasil Dibuat',
        ]);

        //CREATE A REPORT
        $file = 'RPT-Karyawan.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.employee', compact('parts', 'teams', 'subteams', 'positions', 'employees'))
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function analysis($month, $year)
    {
        //GET DATA
        $periods = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->groupBy('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->orderBy('periods.id_period', 'ASC')->where('periods.month', $month)->where('periods.year', $year)->first();
        $subcriterias = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.num_month', 'history_inputs.category_name', 'history_inputs.id_criteria', 'history_inputs.criteria_name', 'history_inputs.attribute', 'history_inputs.weight')->groupBy('periods.num_month', 'history_inputs.category_name', 'history_inputs.id_criteria', 'history_inputs.criteria_name', 'history_inputs.attribute', 'history_inputs.weight')->where('periods.id_period', $periods->id_period)->get();
        $employees = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.id_period', 'periods.name', 'history_inputs.id_employee', 'history_inputs.employee_name', 'history_inputs.employee_position')
        ->groupBy('periods.id_period', 'periods.name', 'history_inputs.id_employee', 'history_inputs.employee_name', 'history_inputs.employee_position')
        ->where('periods.id_period', $periods->id_period)
        //->where('is_lead', 'No')
        ->get();
        //$prd_name = HistoryInput::select('id_period', 'periods.name')->groupBy('id_period', 'periods.name')->orderBy('id_period', 'ASC')->where('id_period', $period)->first()->period_name;

        $alternatives = HistoryInput::with('criteria', 'employee')
        ->select('id_employee')
        ->groupBy('id_employee')
        ->where('id_period', $periods->id_period)
        //->where('is_lead', 'No')
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
        ->where('id_period', $periods->id_period)
        ->get();

        $inputs = HistoryInput::with('criteria')
        ->where('id_period', $periods->id_period)
        //->where('is_lead', 'No')
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
                        $normal[$value1->id_employee][$value2->id_criteria] = $value1->input / (max($minmax[$value2->id_criteria]) ?: 1);
                    }elseif($value2->attribute == 'Cost'){
                        if(min($minmax[$value2->id_criteria]) == 0){
                            if($value1->input == 0){
                                $normal[$value1->id_employee][$value2->id_criteria] = 0.5 / 0.5;
                            }else{
                                $normal[$value1->id_employee][$value2->id_criteria] = 0.5 / ($value1->input ?: 1);
                            }
                        }else{
                            $normal[$value1->id_employee][$value2->id_criteria] = (min($minmax[$value2->id_criteria]) ?: 1) / ($value1->input ?: 1);
                        }
                        //$normal[$value1->id_employee][$value2->id_criteria] = (min($minmax[$value2->id_criteria]) ?: 1) / $value1->input;
                    }
                }
            }
        }
        //dd($normal);

        //MATRIX
        foreach($inputs as $input => $value1){
            foreach($criterias as $crit => $value2){
                if($value2->id_criteria == $value1->id_criteria){
                    $mxin[$value1->id_employee][$value2->id_criteria] = $normal[$value1->id_employee][$value2->id_criteria] * $value2->weight;
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
                'id_employee'=>$n,
                'matrix'=>$matrix[$n],
            ]);
            */
        }
        arsort($matrix);
        //dd($mx_hasil);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Laporan',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Laporan Hasil Analisis SAW Berhasil Dibuat ('.$periods->period->name.')',
        ]);

        //CREATE A REPORT
        $file = 'RPT-Analysis-'.$periods->id_period.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.analysis', compact('periods', 'employees', 'alternatives', 'criterias', 'subcriterias', 'inputs', 'minmax', 'normal', 'mx_hasil', 'matrix'))
        ->setPaper('a4', 'landscape')
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function team_result($subteam, $month, $year)
    {
        //GET DATA
        $periods = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->groupBy('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->orderBy('periods.id_period', 'ASC')->where('periods.month', $month)->where('periods.year', $year)->first();
        $results = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->where('periods.month', $month)->where('periods.year', $year)->where('history_scores.id_sub_team', $subteam)->orderBy('final_score', 'DESC')->get();
        $subteams = HistoryScore::select('id_sub_team', 'sub_team_1_name')->groupBy('id_sub_team', 'sub_team_1_name')->where('id_sub_team', $subteam)->first();

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Laporan',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Laporan Nilai Akhir per Tim Berhasil Dibuat ('.$periods->period->name.') ('.$subteams->sub_team_1_name.')',
        ]);

        //CREATE A REPORT
        $file = 'RPT-Result-'.$subteams->id_sub_team.'-'.$periods->id_period.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.teamresult', compact('periods','results','subteams'))
        ->setPaper('a4', 'landscape')
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function result($month, $year)
    {
        //GET DATA
        $periods = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->groupBy('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->orderBy('periods.id_period', 'ASC')->where('periods.month', $month)->where('periods.year', $year)->first();
        $results = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->where('periods.id_period', $periods->id_period)->orderBy('final_score', 'DESC')->get();

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Laporan',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Laporan Karyawan Terbaik Berhasil Dibuat ('.$periods->period->name.')',
        ]);

        //CREATE A REPORT
        $file = 'RPT-Result-'.$periods->id_period.'.pdf';
        $pdf = PDF::
        loadview('Pages.PDF.result', compact('periods','results'))
        ->setPaper('a4', 'landscape')
        ->save('PDFs/'.$file)
        ->stream($file);
        return $pdf;
    }

    public function certificate($month, $year)
    {
        //GET DATA
        $periods = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->groupBy('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->orderBy('periods.id_period', 'ASC')->where('periods.month', $month)->where('periods.year', $year)->first();
        $results = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->where('periods.id_period', $periods->id_period)->orderBy('final_score', 'DESC')->first();

        //PREPARING A CERTIFICATE
        $employee_name = $results->employee_name;
        $period_name = $periods->period->name;
        $now = Carbon::now()
        ->locale('id')
        ->settings(['formatFunction' => 'translatedFormat'])
        ->format('d F Y');
        $file = 'RPT-Certificate-'.$periods->id_period.'.pdf';
        $output = public_path().'/PDFs/'.$file;
        $source = public_path().'/PDFs/Default/New Certificate.pdf';

        //CREATE A CERTIFICATE (SWITCH TO ANOTHER FUNCTION)
        $this->fillPDF($file, $source, $output, $employee_name, $period_name, $now);

        //return response()->download($output, $file);
    }

    public function fillPDF($file, $source, $output, $employee_name, $period_name, $now) //FOR CERTIFICATE
    {
        //SET TEMPLATE
        $fpdi = new Fpdi;
        $fpdi->setSourceFile($source);
        $template = $fpdi->importPage(1);
        $size = $fpdi->getTemplateSize($template);
        $fpdi->AddPage($size['orientation'],array($size['width'],$size['height']));
        $fpdi->useTemplate($template);

        //GET AND CUSTOMIZE EMPLOYEE NAME
        $text1 = $employee_name;
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

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Laporan',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Sertifikat Karyawan Terbaik Berhasil Dibuat ('.$period_name.')',
        ]);

        //RETURN TO VIEW
        $fpdi->Output('F', $output);
        return $fpdi->Output('I', $file);
    }
}
