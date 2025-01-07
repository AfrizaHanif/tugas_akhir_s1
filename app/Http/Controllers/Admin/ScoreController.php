<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Criteria;
use App\Models\Position;
use App\Models\HistoryInput;
use App\Models\HistoryResult;
use App\Models\HistoryScore;
use App\Models\Input;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Period;
use App\Models\Score;
use App\Models\Setting;
use App\Models\SubTeam;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ScoreController extends Controller
{
    public function index()
    {
        //GET DATA
        $employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        //->where('is_lead', 'No')
        ->where('status', 'Active')
        ->get(); //GET EMPLOYEES
        $periods = Period::orderBy('id_period', 'ASC')->whereIn('progress_status', ['Scoring', 'Verifying', 'Finished'])->get(); //GET PERIODS
        $scores = Score::with('employee')->orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->get(); //GET SCORES
        $inputs = Input::get(); //GET INPUTS
        //$input_raws = InputRAW::get();
        $status = Input::select('id_period', 'id_employee', 'status')->groupBy('id_period', 'id_employee', 'status')->get(); //GET STATUS
        $categories = Category::with('criteria')->get(); //GET CATEGORIES
        $allcriterias = Criteria::with('category')->get(); //GET CRITERIAS
        $criterias = Criteria::get(); //GET CRITERIAS
        $countsub = Criteria::count(); //COUNT CRITERIAS
        $setting = Setting::where('id_setting', 'STG-002')->first()->value; //GET SETTING VALUE
        $set_crit = Criteria::where('id_criteria', $setting)->first(); //SET SETTING FROM VALUE FOR SECOND SCORE

        //GET PERIODS FOR LIST
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('progress_status', ['Skipped', 'Pending', 'Finished'])->latest()->first(); //GET CURRENT PERIOD
        $history_per = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.id_period', 'periods.name')->groupBy('periods.id_period', 'periods.name')->orderBy('periods.year', 'ASC')->orderBy('periods.num_month', 'ASC')->get(); //GET PREVIOUS PERIODS

        //dd($scores->where('id_period', $latest_per->id_period)->where('status', 'Accepted')->count() != $employees->count());

        //GET HISTORY DATA
        $histories = HistoryInput::get(); //GET OLD INPUTS
        $hscore = HistoryScore::orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->get(); //GET OLD SCORES
        $hemployees = HistoryScore::join('periods', 'periods.id_period', '=', 'history_scores.id_period')->select('periods.id_period', 'periods.name', 'history_scores.id_employee', 'history_scores.employee_name', 'history_scores.employee_position')->groupBy('periods.id_period', 'periods.name', 'history_scores.id_employee', 'history_scores.employee_name', 'history_scores.employee_position')->get(); //GET PREVIOUS EMPLOYEES FROM OLD INPUTS
        $hcriterias = HistoryInput::select('id_criteria', 'criteria_name', 'id_period', 'unit')->groupBy('id_criteria', 'criteria_name', 'id_period', 'unit')->get(); //GET PREVIOUS CRITERIAS FROM OLD INPUTS

        //RETURN TO VIEW
        return view('Pages.Admin.score', compact('employees', 'periods', 'latest_per', 'history_per', 'scores', 'inputs', 'status', 'categories', 'allcriterias', 'criterias', 'countsub', 'set_crit', 'histories', 'hscore', 'hemployees', 'hcriterias'));
    }

    public function get($period)
    {
        //GET DATA
        //$periods = Period::orderBy('id_period', 'ASC')->whereNot('progress_status', 'Skipped')->whereNot('status', 'Pending')->get();
        $subcriterias = Criteria::with('category')->get(); //GET CRITERIAS
        $employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->where('status', 'Active')
        ->get(); //GET EMPLOYEES
        $latest_per = Period::whereIn('progress_status', ['Scoring', 'Verifying'])->latest()->first(); //GET CURRENT PERIOD

        //VERIFICATION
        //CHECK EMPTY DATA
        if(Input::where('id_period', $period)->count() == 0){
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Ambil Nilai Akhir',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Ambil Nilai Akhir Tidak Berhasil (Tidak Ada Data) ('.$latest_per->name.')',
            ]);

            //RETURN TO VIEW
            return redirect()->route('admin.inputs.validate.index')->with('fail','Tidak ada data yang terdaftar di periode yang dipilih untuk melakukan analisis.');
        }else{
            if($latest_per->import_status == 'Not Clear'){
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Ambil Nilai Akhir',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Ambil Nilai Akhir Tidak Berhasil (Belum Dikonversi) ('.$latest_per->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()->route('admin.inputs.validate.index')->with('fail','Terdapat nilai yang belum dikonversi. Hubungi kepegawaian untuk melakukan konversi nilai.');
            }elseif($latest_per->import_status == 'Few Clear'){
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Ambil Nilai Akhir',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Ambil Nilai Akhir Tidak Berhasil (Sebagian Dikonversi) ('.$latest_per->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()->route('admin.inputs.validate.index')->with('fail','Terdapat beberapa nilai tidak dapat dikonversi. Hubungi kepegawaian untuk melakukan perbaikan konversi.');
            }elseif($subcriterias->sum('weight')*100 > 100){
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Ambil Nilai Akhir',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Ambil Nilai Akhir Tidak Berhasil (Bobot Melebihi 100%) ('.$latest_per->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()->route('admin.inputs.validate.index')->with('fail','Total Bobot melebihi 100%. Hubungi kepegawaian untuk melakukan perbaikan kriteria.');
            }elseif($subcriterias->sum('weight')*100 <= 99){
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Ambil Nilai Akhir',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Ambil Nilai Akhir Tidak Berhasil (Bobot Belum Mencapai 100%) ('.$latest_per->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()->route('admin.inputs.validate.index')->with('fail','Total Bobot belum mencapai 100%. Hubungi kepegawaian untuk melakukan perbaikan kriteria.');
            }else{
                foreach ($employees as $employee) {
                    if(Input::where('id_period', $period)->where('id_employee', $employee->id_employee)->count() == 0){ //IF EMPLOYEE HAS NO DATA IN BOTH TABLE
                        //CREATE A LOG
                        Log::create([
                            'id_user'=>Auth::user()->id_user,
                            'activity'=>'Ambil Nilai Akhir',
                            'progress'=>'Create',
                            'result'=>'Error',
                            'descriptions'=>'Ambil Nilai Akhir Tidak Berhasil (Belum Dinilai Sepenuhnya ('.$employee->name.') ('.$latest_per->name.'))',
                        ]);

                        //RETURN TO VIEW
                        return redirect()->route('admin.inputs.validate.index')->with('fail','Terdapat karyawan yang belum dinilai sepenuhnya. Silahkan cek kembali di halaman Input Data. ('.$employee->id_employee.')');
                    }else{ //IF EMPLOYEE HAS A FEW DATA IN BOTH TABLE
                        foreach ($subcriterias as $subcriteria) {
                            if(Input::where('id_period', $period)->where('id_employee', $employee->id_employee)->where('id_criteria', $subcriteria->id_criteria)->count() == 0) {
                                //CREATE A LOG
                                Log::create([
                                    'id_user'=>Auth::user()->id_user,
                                    'activity'=>'Ambil Nilai Akhir',
                                    'progress'=>'Create',
                                    'result'=>'Error',
                                    'descriptions'=>'Ambil Nilai Akhir Tidak Berhasil (Hanya Dinilai Sebagian ('.$employee->name.') ('.$subcriteria->name.'))',
                                ]);

                                //RETURN TO VIEW
                                return redirect()->route('admin.inputs.validate.index')->with('fail','Terdapat karyawan yang hanya dinilai sebagian. Silahkan cek kembali di halaman Input Data. ('.$employee->id_employee.') ('.$subcriteria->id_criteria.') ('.$latest_per->name.')');
                            }else{ //ALL CLEAR
                                //CLEAR
                            }
                        }
                    }
                }
            }
        }

        //DELETE EXISTING DATA
        //$check = DB::table('scores')->where('id_period', $period)->where('status', 'Pending')->orWhere('status', 'Rejected')->orWhere('status', 'Revised');
        /* PREVIOUS CODE (DELETE IF REJECTED / REVISED)
        $check = DB::table('scores')->where('id_period', $period)->whereIn('status', ['Pending', 'Accepted', 'Revised']);
        if($check->exists()){
            $check->delete();
        }
        */
        //DB::table('scores')->delete();

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
        ->where('id_period', $period)
        //->where('criterias.need', 'Ya')
        ->get(); //GET CRITERIAS FROM INPUTS
        //$criterias = Criteria::get();

        //GET INPUT
        $inputs = Input::with('criteria', 'employee')
        ->where('id_period', $period)
        ->whereDoesntHave('employee', function($query){
            $query->with('position')->whereHas('position', function($query){
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
        $mx_hasil = $mxin; //$ranking = $normal;

        //INSERT FOR VALIDATION
        foreach($normal as $n => $value1){
            //SUM ALL MATRIX
            $mx_hasil[$n][] = array_sum($mxin[$n]); //$normal[$n][] = array_sum($rank[$n]);
            $matrix[$n] = array_sum($mxin[$n]); //$normal[$n][] = array_sum($rank[$n]);

            //INSERT DATA
            if(DB::table('scores')->where('id_period', $period)->where('id_employee', $n)->count() == 0){ //FIRST TIME GET DATA
                //GET DATA FOR SECOND SORT
                $setting = Setting::where('id_setting', 'STG-002')->first()->value; //GET SETTING VALUE
                $set_second = Criteria::where('id_criteria', $setting)->first(); //SET SETTING FROM VALUE
                $second_score = Input::where('id_period', $period)->where('id_employee', $n)->where('id_criteria', $set_second->id_criteria)->first()->input_raw; //GET SECOND SCORE

                //INSERT DATA SCORES
                $str_employee = substr($n, 4); //GET ID START FROM 4TH DIGIT
                $str_year = substr($period, -5); //GET YEAR START FROM MINUS 5TH DIGIT
                $id_score = "SCR-".$str_year.'-'.$str_employee;
                DB::table('scores')->insert([
                    //'id_score'=>$id_score,
                    'id_employee'=>$n,
                    'id_period'=>$period,
                    'final_score'=>$matrix[$n],
                    'second_score'=>$second_score,
                    'status'=>'Pending'
                ]);

                //UPDATE STATUS
                Input::with('employee')
                ->where('id_period', $period)
                ->where('id_employee', $n)
                ->whereIn('status', ['Pending', 'Final', 'Need Fix', 'Fixed'])
                ->update([
                    'status'=>'In Review'
                ]);
            }else{ //AFTER REVISION
                //GET DATA FOR SECOND SORT
                $setting = Setting::where('id_setting', 'STG-002')->first()->value; //GET SETTING VALUE
                $set_second = Criteria::where('id_criteria', $setting)->first(); //SET SETTING FROM VALUE
                $second_score = Input::where('id_period', $period)->where('id_employee', $n)->where('id_criteria', $set_second->id_criteria)->first()->input_raw; //GET SECOND SCORE

                //CHECK STATUS
                if(Score::where('id_employee', $n)->where('id_period', $period)->first()->status == 'Accepted'){
                    $stat_score = 'Accepted';
                    $stat_input = 'Final';
                }else{
                    $stat_score = 'Pending';
                    $stat_input = 'In Review';
                }

                //UPDATE DATA SCORES
                DB::table('scores')->where('id_employee', $n)->where('id_period', $period)->update([
                    //'id_score'=>$id_score,
                    //'id_employee'=>$n,
                    //'id_period'=>$period,
                    'final_score'=>$matrix[$n],
                    'second_score'=>$second_score,
                    'status'=>$stat_score,
                ]);

                //UPDATE STATUS
                Input::with('employee')
                ->where('id_period', $period)
                ->where('id_employee', $n)
                ->whereIn('status', ['Pending', 'Final', 'Need Fix', 'Fixed'])
                ->update([
                    'status'=>$stat_input
                ]);
            }
        }
        arsort($matrix);

        //UPDATE STATUS
        /*
        Input::with('employee')
        ->where('id_period', $period)
        ->whereIn('status', ['Pending', 'Final', 'Need Fix', 'Fixed'])
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->update([
            'status'=>'In Review'
        ]);
        */
        /*
        InputRAW::with('employee')
        ->where('id_period', $period)
        ->whereIn('status', ['Pending', 'Need Fix', 'Fixed'])
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->update([
            'status'=>'In Review'
        ]);
        */
        Period::where('id_period', $period)
        ->update([
            'progress_status'=>'Verifying'
        ]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Ambil Nilai Akhir',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Ambil Nilai Akhir Berhasil ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Ambil Data Berhasil')->with('code_alert', 1);
    }

    public function yes($id)
    {
        //GET DATA
        $result = Score::where('id', $id)->first(); //GET SELECTED SCORE
        $period = Period::where('id_period', $result->id_period)->first()->id_period; //GET SELECTED PERIOD
        $employee = Employee::where('id_employee', $result->id_employee)->first()->id_employee; //GET SELECTED EMPLOYEE
        $name = Employee::where('id_employee', $result->id_employee)->first()->name; //GET SELECTED NAME

        //UPDATE STATUS
        Score::where('id', $id)->update([
            'status'=>'Accepted'
        ]);
        Input::where('id_period', $period)->where('id_employee', $employee)->update([
            'status'=>'Final'
        ]);
        /*
        InputRAW::where('id_period', $period)->where('id_employee', $employee)->update([
            'status'=>'Final'
        ]);
        */

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Persetujuan Nilai',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Persetujuan Berhasil ('.$name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Persetujuan Berhasil. Data dari karyawan ('. $name .') telah disetujui')->with('code_alert', 1);
    }

    public function yesall($id)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $id)->first(); //GET CURRENT PERIOD

        //UPDATE STATUS
        Score::where('id_period', $id)->update([
            'status'=>'Accepted'
        ]);
        Input::with('employee')->where('id_period', $id)
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->update([
            'status'=>'Final'
        ]);
        /*
        InputRAW::with('employee')->where('id_period', $id)
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->update([
            'status'=>'Final'
        ]);
        */

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Persetujuan Nilai',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Persetujuan Berhasil (Semua) ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Persetujuan Berhasil. Data dari seluruh karyawan telah disetujui')->with('code_alert', 1);
    }

    public function yesall_remain($id)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $id)->first(); //GET CURRENT PERIOD

        //UPDATE STATUS
        Score::where('id_period', $id)->where('status', 'Pending')->update([
            'status'=>'Accepted'
        ]);
        Input::with('employee')->where('id_period', $id)->where('status', 'In Review')
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->update([
            'status'=>'Final'
        ]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Persetujuan Nilai',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Persetujuan Berhasil (Sebagian) ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Persetujuan Berhasil. Data dari beberapa karyawan yang belum diperiksa telah disetujui')->with('code_alert', 1);
    }

    public function no($id)
    {
        //GET DATA
        $result = Score::where('id', $id)->first(); //GET SELECTED SCORE
        $period = Period::where('id_period', $result->id_period)->first()->id_period; //GET SELECTED PERIOD
        $employee = Employee::where('id_employee', $result->id_employee)->first()->id_employee; //GET SELECTED EMPLOYEE
        $name = Employee::where('id_employee', $result->id_employee)->first()->name; //GET SELECTED NAME

        //UPDATE STATUS
        Score::where('id', $id)->update([
            'status'=>'Rejected'
        ]);
        Input::where('id_period', $period)->where('id_employee', $employee)->update([
            'status'=>'Need Fix'
        ]);
        /*
        InputRAW::where('id_period', $period)->where('id_employee', $employee)->update([
            'status'=>'Need Fix'
        ]);
        */

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Penolakan Nilai',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Penolakan Berhasil ('.$name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Penolakan Berhasil. Data dari karyawan ('. $name .') telah dikembalikan')->with('code_alert', 1);
    }

    public function noall($id)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $id)->first(); //GET CURRENT PERIOD

        //UPDATE STATUS
        Score::where('id_period', $id)->update([
            'status'=>'Rejected'
        ]);
        Input::with('employee')->where('id_period', $id)
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->update([
            'status'=>'Need Fix'
        ]);
        /*
        InputRAW::with('employee')->where('id_period', $id)
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->update([
            'status'=>'Need Fix'
        ]);
        */

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Penolakan Nilai',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Penolakan Berhasil (Semua) ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Penolakan Berhasil. Data dari seluruh karyawan telah dikembalikan')->with('code_alert', 1);
    }

    public function noall_remain($id)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $id)->first(); //GET CURRENT PERIOD

        //UPDATE STATUS
        Score::where('id_period', $id)->where('status', 'Pending')->update([
            'status'=>'Rejected'
        ]);
        Input::with('employee')->where('id_period', $id)->where('status', 'In Review')
        ->whereHas('employee', function($query){
            $query->with('position')->whereDoesntHave('position', function($query){
                $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
            });
        })
        ->update([
            'status'=>'Need Fix'
        ]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Penolakan Nilai',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Penolakan Berhasil (Sebagian) ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Penolakan Berhasil. Data dari beberapa karyawan yang belum diperiksa telah dikembalikan')->with('code_alert', 1);
    }

    public function finish($period)
    {
        //GET PERIOD DATA
        $latest_per = Period::where('id_period', $period)->first(); //GET CURRENT PERIOD

        //HISTORY SCORE
        //GET DATA (1/2)
        $scores1 = Score::where('id_period', $period)->orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->get(); //GET ALL SCORES
        foreach($scores1 as $score){
            //GET DATA (2/2)
            $getperiod1 = Period::where('id_period', $score->id_period)->first(); //GET PERIOD PER EMPLOYEE
            $getemployee1 = Employee::where('id_employee', $score->id_employee)->first(); //GET SELECTED EMPLOYEE
            $getposition1 = Position::with('employee')->whereHas('employee', function($query) use($score){
                $query->where('id_employee', $score->id_employee);
            })->first(); //GET POSITION PER EMPLOYEE
            //dd($score->id_employee);
            $getteam1 = Team::with('subteam')->whereHas('subteam', function($query) use($score)
            {
                $query->with('employee_1')->whereHas('employee_1', function($query) use($score)
                {
                    $query->where('id_employee', $score->id_employee);
                });
            })->first(); //GET TEAM PER EMPLOYEE
            $getsubteam1a = SubTeam::with('employee_1')->whereHas('employee_1', function($query) use($score)
            {
                $query->where('id_employee', $score->id_employee);
            })->first(); //GET SUB TEAM PER EMPLOYEE (MAIN)
            $getsubteam1b = SubTeam::with('employee_2')->whereHas('employee_2', function($query) use($score)
            {
                $query->where('id_employee', $score->id_employee);
            })->first(); //GET SUB TEAM PER EMPLOYEE (SUB)
            $getsetting1 = Setting::where('id_setting', 'STG-002')->first(); //FUTURE DEVELOPMENT
            $getrank = HistoryScore::where('id_period', $latest_per->id_period)->count();

            //INSERT DATA
            HistoryScore::insert([
                'id_period'=>$getperiod1->id_period,
                //'period_name'=>$getperiod1->name,
                //'period_month'=>$getperiod1->month,
                //'period_num_month'=>$getperiod1->num_month,
                //'period_year'=>$getperiod1->year,
                'id_employee'=>$getemployee1->id_employee,
                //'employee_nip'=>$getemployee1->nip,
                'employee_name'=>$getemployee1->name,
                'employee_position'=>$getposition1->name,
                'id_team'=>$getteam1->id_team,
                'team_name'=>$getteam1->name,
                'id_sub_team'=>$getsubteam1a->id_sub_team,
                'sub_team_1_name'=>$getsubteam1a->name,
                'sub_team_2_name'=>$getsubteam1b->name ?? '',
                'final_score'=>$score->final_score,
                'setting_value'=>$getsetting1->value,
                'second_score'=>$score->second_score,
                'rank'=>$getrank + 1,
            ]);
        }

        //HISTORY INPUT (CONVERTED)
        //GET DATA (1/2)
        $inputs = Input::where('id_period', $period)->get(); //GET ALL INPUTS AT CURRENT PERIOD
        foreach($inputs as $input){
            //GET DATA (2/2)
            //$getuser2 = User::where('id_employee', $input->id_employee)->first();
            $getperiod2 = Period::where('id_period', $input->id_period)->first(); //GET SELECTED PERIOD
            $getemployee2 = Employee::where('id_employee', $input->id_employee)->first(); //GET SELECTED EMPLOYEE
            $getposition2 = Position::with('employee')->whereHas('employee', function($query) use($input){
                $query->where('id_employee', $input->id_employee);
            })->first(); //GET POSITION PER EMPLOYEE
            $getteam2 = Team::with('subteam')->whereHas('subteam', function($query) use($input)
            {
                $query->with('employee_1')->whereHas('employee_1', function($query) use($input)
                {
                    $query->where('id_employee', $input->id_employee);
                });
            })->first(); //GET TEAM PER EMPLOYEE
            $getsubteam2a = SubTeam::with('employee_1')->whereHas('employee_1', function($query) use($input)
            {
                $query->where('id_employee', $input->id_employee);
            })->first(); //GET SUB TEAM PER EMPLOYEE (MAIN)
            $getsubteam2b = SubTeam::with('employee_2')->whereHas('employee_2', function($query) use($input)
            {
                $query->where('id_employee', $input->id_employee);
            })->first(); //GET SUB TEAM PER EMPLOYEE (SUB)
            $getcriteria2 = Category::with('criteria')->whereHas('criteria', function($query) use($input){
                $query->where('id_criteria', $input->id_criteria);
            })->first(); //GET SELECTED CATEGORY
            $getsubcriteria2 = Criteria::where('id_criteria', $input->id_criteria)->first(); //GET SELECTED CRITERIA

            //CHECK IF ADMIN (WILL BE REMOVED)
            /*
            if(empty($getuser2->part) || $getuser2->part == 'Admin'){
                //$is_lead = 'No';
            }else{
                //$is_lead = 'Yes';
            }
            */

            //INSERT DATA
            HistoryInput::insert([
                'id_period'=>$getperiod2->id_period,
                //'period_name'=>$getperiod2->name,
                //'period_month'=>$getperiod2->month,
                //'period_num_month'=>$getperiod2->num_month,
                //'period_year'=>$getperiod2->year,
                'id_employee'=>$getemployee2->id_employee,
                //'employee_nip'=>$getemployee2->nip,
                'employee_name'=>$getemployee2->name,
                'employee_position'=>$getposition2->name,
                'id_team'=>$getteam2->id_team,
                'team_name'=>$getteam2->name,
                'id_sub_team'=>$getsubteam2a->id_sub_team,
                'sub_team_1_name'=>$getsubteam2a->name,
                'sub_team_2_name'=>$getsubteam2b->name ?? '',
                'id_category'=>$getcriteria2->id_category,
                'category_name'=>$getcriteria2->name,
                'id_criteria'=>$getsubcriteria2->id_criteria,
                'criteria_name'=>$getsubcriteria2->name,
                'weight'=>$getsubcriteria2->weight,
                'attribute'=>$getsubcriteria2->attribute,
                //'level'=>$getsubcriteria2->level,
                'max'=>$getsubcriteria2->max,
                'unit'=>$getsubcriteria2->unit,
                //'is_lead'=>'No',
                'input'=>$input->input,
                'input_raw'=>$input->input_raw,
            ]);
        }

        /*
        //HISTORY INPUT (RAW)
        //GET DATA (1/2)
        $input_raws = InputRAW::where('id_period', $period)->get();
        foreach($input_raws as $raw){
            //GET DATA (2/2)
            $getuser3 = User::where('id_employee', $raw->id_employee)->first();
            $getperiod3 = Period::where('id_period', $raw->id_period)->first();
            $getemployee3 = Employee::where('id_employee', $raw->id_employee)->first();
            $getposition3 = Position::with('employee')->whereHas('employee', function($query) use($raw){
                $query->where('id_employee', $raw->id_employee);
            })->first();
            $getcriteria3 = Category::with('criteria')->whereHas('criteria', function($query) use($raw){
                $query->where('id_criteria', $raw->id_criteria);
            })->first();
            $getsubcriteria3 = Criteria::where('id_criteria', $raw->id_criteria)->first();

            //CHECK IF ADMIN (WILL BE REMOVED)
            if(empty($getuser3->part) || $getuser3->part == 'Admin'){
                //$is_lead = 'No';
            }else{
                //$is_lead = 'Yes';
            }

            //INSERT DATA
            HistoryInputRAW::insert([
                'id_period'=>$getperiod3->id_period,
                //'period_name'=>$getperiod3->name,
                'id_employee'=>$getemployee3->id_employee,
                //'employee_nip'=>$getemployee3->nip,
                'employee_name'=>$getemployee3->name,
                'employee_position'=>$getposition3->name,
                'id_category'=>$getcriteria3->id_category,
                'category_name'=>$getcriteria3->name,
                'id_criteria'=>$getsubcriteria3->id_criteria,
                'criteria_name'=>$getsubcriteria3->name,
                'weight'=>$getsubcriteria3->weight,
                'attribute'=>$getsubcriteria3->attribute,
                //'level'=>$getsubcriteria3->level,
                'max'=>$getsubcriteria3->max,
                //'is_lead'=>$is_lead,
                'input'=>$raw->input,
            ]);
        }
        */

        //HISTORY RESULT
        //GET DATA (RESULT)
        $scores2 = Score::where('id_period', $period)->orderBy('final_score', 'DESC')->orderBy('second_score', 'DESC')->offset(0)->limit(1)->first(); //GET SCORE FROM WINNER AT CURRENT PERIOD
        $getperiod4 = Period::where('id_period', $scores2->id_period)->first(); //GET SELECTED PERIOD
        $getemployee4 = Employee::where('id_employee', $scores2->id_employee)->first(); //GET SELECTED EMPLOYEE
        $getposition4 = Position::with('employee')->whereHas('employee', function($query) use($scores2){
            $query->where('id_employee', $scores2->id_employee);
        })->first(); //GET POSITION FROM SELECTED EMPLOYEE
        $getteam4 = Team::with('subteam')->whereHas('subteam', function($query) use($scores2)
            {
                $query->with('employee_1')->whereHas('employee_1', function($query) use($scores2)
                {
                    $query->where('id_employee', $scores2->id_employee);
                });
            })->first(); //GET TEAM FROM SELECTED EMPLOYEE
        $getsubteam4a = SubTeam::with('employee_1')->whereHas('employee_1', function($query) use($scores2)
        {
            $query->where('id_employee', $scores2->id_employee);
        })->first(); //GET SUB TEAM (MAIN) FROM SELECTED EMPLOYEE
        $getsubteam4b = SubTeam::with('employee_2')->whereHas('employee_2', function($query) use($scores2)
        {
            $query->where('id_employee', $scores2->id_employee);
        })->first(); //GET SUB TEAM (SUB) FROM SELECTED EMPLOYEE

        //GET PHOTO (RESULT)
        $photo = '';
        $id_employee = Employee::find($getemployee4->id_employee); //FIND ID FROM SELECTED EMPLOYEE
        $path_photo = public_path('Images/Portrait/'.$id_employee->photo); //GET EMPLOYEE'S PHOTO
        if(!empty($getemployee4->photo)){
            $extension = File::extension($path_photo); //GET IMAGE EXTENSION
            $photo = 'IMG-'.$getperiod4->id_period.'-'.$getemployee4->id_employee.'.'.$extension; //CREATE A NEW NAME
            $new_path = 'Images/History/Portrait/'.$photo; //CREATE A NEW PATH
            File::copy($path_photo , $new_path); //COPY IMAGE
        }

        //INSERT DATA (RESULT)
        HistoryResult::insert([
            'id_period'=>$getperiod4->id_period,
            //'period_name'=>$getperiod4->name,
            //'period_month'=>$getperiod4->month,
            //'period_num_month'=>$getperiod4->num_month,
            //'period_year'=>$getperiod4->year,
            'id_employee'=>$getemployee4->id_employee,
            //'employee_nip'=>$getemployee4->nip,
            'employee_name'=>$getemployee4->name,
            'employee_position'=>$getposition4->name,
            'id_team'=>$getteam4->id_team,
            'team_name'=>$getteam4->name,
            'id_sub_team'=>$getsubteam4a->id_sub_team,
            'sub_team_1_name'=>$getsubteam4a->name,
            'sub_team_2_name'=>$getsubteam4b->name ?? '',
            'employee_photo'=>$photo,
            'final_score'=>$scores2->final_score,
            //'second_score'=>$scores2->second_score,
        ]);

        //UPDATE STATUS
        Period::where('id_period', $period)->update([
            'progress_status'=>'Finished',
        ]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Proses Selesai',
            'progress'=>'All',
            'result'=>'Success',
            'descriptions'=>'Proses Selesai ('.$latest_per->name.')',
        ]);

        //DELETE CURRENT DATA
        DB::table('inputs')->delete();
        //DB::table('input_raws')->delete();
        DB::table('scores')->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.validate.index')->with('success','Proses Penentuan Karyawan Terbaik Selesai. Hasil tersebut dapat dilihat pada halaman Dashboard dan Utama')->with('code_alert', 1);
    }
}
