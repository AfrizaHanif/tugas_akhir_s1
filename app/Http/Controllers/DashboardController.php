<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\HistoryInput;
use App\Models\HistoryResult;
use App\Models\HistoryScore;
use App\Models\Input;
use App\Models\Message;
use App\Models\Employee;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\Result;
use App\Models\Score;
use App\Models\SubCriteria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function admin(){ //KEPEGAWAIAN ONLY
        //AUTO CREATE PERIOD (DISABLE IF NOT NEEDED)
        //Artisan::call('app:create-period');

        //CHECK LOGOUT CONDITION
        /*
        if(Auth::check() && Auth::user()->force_logout == true){
            ForceLogoutController::check();
            return redirect()
            ->route('index')
            ->with('success','Anda telah dikeluarkan secara otomatis dari sistem. Silahkan login kembali.')
            ->with('code_alert', 1);
        }
            */

        //GET DATA
        $periods = Period::get(); //GET PERIODS
        $inputs = Input::get(); //GET INPUTS
        $employees = Employee::with('position')
        ->where('status', 'Active')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        //->where('is_lead', 'No')
        ->get(); //GET EMPLOYEES WITHOUT KEPALA BPS AND DEVELOPER
        $input_lists = Input::with('employee')
        ->select('id_period', 'id_employee', 'status')
        ->groupBy('id_period', 'id_employee', 'status')
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->get(); //GET INPUT LISTS FOR CHECK CONVERT STATUS
        $scores = Score::with('employee')
        ->select('id_period', 'id_employee', 'status')
        ->groupBy('id_period', 'id_employee', 'status')
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->get(); //GET SCORES
        $count = Input::with('employee')
        ->select('id_period', 'id_employee', 'status')
        ->groupBy('id_period', 'id_employee', 'status')
        ->get(); //COUNT INPUTS
        $countsub = Criteria::count(); //COUNT CRITERIAS
        $subcriterias = Criteria::get(); //GET CRITERIAS
        $status = Input::select('id_period', 'id_employee', 'status')->groupBy('id_period', 'id_employee', 'status')->get(); //GET STATUS IN INPUTS

        //GET DATA PER PART OF ACCOUNT
        if(Auth::user()->part == 'Admin'){
            //LIST OF EMPLOYEES FOR INPUT
            $input_off = Employee::with('position')
            ->where('status', 'Active')
            ->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            })
            //->where('is_lead', 'No')
            ->get(); //GET EMPLOYEES FOR INPUT CARD
            //dd($input_off);
        }elseif(Auth::user()->part == 'KBPS'){
            //LIST OF EMPLOYEES FOR INPUT
            $input_off = Employee::with('position')
            ->where('status', 'Active')
            ->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            })
            //->where('is_lead', 'No')
            ->get(); //GET EMPLOYEES FOR INPUT CARD
        }

        //GET DATA FOR CARDS
        $reject_offs = Employee::with('position', 'score')
        ->where('status', 'Active')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('score', function($query){
            $query->whereIn('status', ['Rejected', 'Revised']);
        })
        //->where('is_lead', 'No')
        ->get(); //GET EMPLOYEES WHO HAS REJECTED SCORES
        $progress_offs = Employee::with('position', 'input')
        ->where('status', 'Active')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('input', function($query){
            $query->whereIn('status', ['Pending', 'Fixed', 'Not Converted']);
        })
        //->where('is_lead', 'No')
        ->get(); //GET EMPLOYEES TO PREPARE FOR VERIFYING
        $acc_offs = Employee::with('position', 'input')
        ->where('status', 'Active')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->whereHas('input', function($query){
            $query->whereIn('status', ['Pending', 'In Review', 'Fixed']);
        })
        //->where('is_lead', 'No')
        ->get(); //GET EMPLOYEES TO LOOK WHICH EMPLOYEE THAT ARE NOT BEING VERIFIED
        $check_score = Score::select('id_period', 'id_employee', 'status')->groupBy('id_period', 'id_employee', 'status')->first('status'); //NOT USED (OPT: DELETE)
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD
        $latest_best = HistoryResult::orderBy('id', 'DESC')->latest()->first(); //CURRENT WINNER
        $latest_top3 = HistoryScore::orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->latest()->get(); //CURRENT TOP 3 BEST SCORES
        $history_prd = HistoryScore::join('periods', 'periods.id_period', '=', 'periods.id_period')->select('periods.id_period', 'periods.name')->groupBy('periods.id_period', 'periods.name')->orderBy('periods.updated_at', 'DESC')->where('periods.progress_status', 'Finished')->first(); //GET FINISHED PERIOD
        $voteresults = HistoryResult::orderBy('id_period', 'ASC')->get(); //GET PREVIOUS RESULTS
        $scoreresults = HistoryScore::orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->get(); //GET OLD SCORES RESULT
        //dd($scoreresults);

        //RETURN TO VIEW
        return view('Pages.Admin.dashboard', compact('employees', 'reject_offs', 'progress_offs', 'acc_offs', 'input_off', 'count', 'inputs', 'scores', 'check_score', 'latest_per', 'latest_best', 'latest_top3', 'history_prd', 'countsub', 'subcriterias', 'periods', 'voteresults', 'scoreresults', 'input_lists', 'status'));
    }

    public function employee(Request $request){
        //CHECK LOGOUT CONDITION
        /*
        if(Auth::check() && Auth::user()->force_logout == true){
            ForceLogoutController::check();
            return redirect()
            ->route('index')
            ->with('success','Anda telah dikeluarkan secara otomatis dari sistem. Silahkan login kembali.')
            ->with('code_alert', 1);
        }
            */

        //GET PERIODS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD
        $history_per = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->groupBy('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->orderBy('periods.year', 'DESC')->orderBy('periods.num_month', 'DESC')->get(); //GET PREVIOUS PERIOD
        $hper_latest = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->groupBy('periods.id_period', 'periods.name', 'periods.month', 'periods.year')->orderBy('periods.year', 'DESC')->orderBy('periods.num_month', 'DESC')->latest('history_inputs.created_at')->first(); //GET FINISHED PERIOD
        $hper_year = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.year')->groupBy('periods.year')->orderBy('periods.year', 'ASC')->orderBy('periods.year', 'DESC')->latest('history_inputs.created_at')->first(); //GET PREVIOUS PERIOD IN YEAR
        $hscore_year = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.year')->groupBy('periods.year')->orderBy('periods.year', 'ASC')->orderBy('periods.year', 'DESC')->get(); //GET OLD SCORE FROM PREVIOUS PERIOD IN YEAR

        //GET LATEST DATA
        $periods = Period::get(); //GET PERIODS
        $criterias = Criteria::get(); //GET CRITERIAS
        $inputs = Input::get(); //GET INPUTS

        //GET HISTORY DATA
        $hcriterias = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('history_inputs.id_criteria', 'history_inputs.criteria_name', 'periods.id_period', 'history_inputs.unit')->groupBy('history_inputs.id_criteria', 'history_inputs.criteria_name', 'periods.id_period', 'history_inputs.unit')->get(); //GET PREVIOUS CRITERIAS FROM OLD INPUTS
        $histories = HistoryInput::get(); //GET OLD INPUTS
        $hscores = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->orderBy('periods.id_period', 'ASC')->get(); //GET OLD SCORES
        //dd($hresults);

        //TEST DATA FOR CHART
        $search = $request->year; //GET SELECTED YEAR
        $chart = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')
        ->where('history_scores.id_employee', Auth::user()->id_employee)
        ->where('periods.year','like',"%".$search."%")
        ->select('periods.name', 'history_scores.final_score')
        ->groupBy('periods.name', 'history_scores.final_score')
        ->orderBy('periods.year', 'ASC')
        ->orderBy('periods.num_month', 'ASC')
        ->pluck('history_scores.final_score', 'periods.name'); //GET FINAL SCORE AND PERIOD NAME FOR CHART

        $c_labels = $chart->keys(); //FOR PERIOD NAME
        $c_datas = $chart->values(); //FOR FINAL SCORE

        /*
        $results = Result::with('employee', 'period')
        ->orderBy('count', 'DESC')
        ->whereHas('employee', function ($query) {
            $query->with('score')
            ->whereHas('score', function ($query) {
                $query->orderBy('final_score', 'DESC');
            });
        })
        ->offset(0)->limit(1)->get();
        */

        return view('Pages.Employee.dashboard', compact('latest_per', 'history_per', 'periods', 'criterias', 'inputs', 'histories', 'hcriterias', 'hscores', 'hper_latest', 'hper_year', 'c_labels', 'c_datas', 'hscore_year'));
    }

    public function developer(){
        //CHECK LOGOUT CONDITION
        /*
        if(Auth::check() && Auth::user()->force_logout == true){
            ForceLogoutController::check();
            return redirect()
            ->route('index')
            ->with('success','Anda telah dikeluarkan secara otomatis dari sistem. Silahkan login kembali.')
            ->with('code_alert', 1);
        }
            */

        //GET DATA
        $employees = Employee::get(); //GET EMPLOYEES
        $users = User::get(); //GET USERS
        $messages = Message::get(); //GET MESSAGES

        //RETURN TO VIEW
        return view('Pages.Developer.dashboard', compact('employees', 'users', 'messages'));
    }
}
