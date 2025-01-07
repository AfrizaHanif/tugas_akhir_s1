<?php

namespace App\Imports;

use App\Models\Criteria;
use App\Models\Input;
use App\Models\InputRAW;
use App\Models\Employee;
use App\Models\Period;
use App\Models\Score;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use PDO;

class InputsImport implements ToCollection, SkipsEmptyRows, SkipsOnError, SkipsOnFailure, WithHeadingRow
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $latest_per, $active_days, $employees, $criterias, $scores, $inputs, $val_setting, $per_status;

    public function __construct($period)
    {
        $this->latest_per = $period;
        $this->active_days = Period::where('id_period', $period)->first()->active_days;
        $this->employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala BPS%');
        })
        ->where('status', 'Active')
        //->where('is_lead', 'No')
        ->orderBy('name', 'ASC')->get();
        $this->criterias = Criteria::with('category')->get();
        $this->scores = Score::where('id_period', $period)->get();
        $this->inputs = Input::where('id_period', $period)->get();
        $this->val_setting = Setting::where('id_setting', 'STG-001')->first()->value;
        $this->per_status = Period::where('id_period', $period)->first()->progress_status;
    }

    public function collection(Collection $rows)
    {
        //dd($this->set_crit);
        foreach($this->criterias as $criteria){
            foreach ($rows as $row){
                //dd($row);
                $name_import = $row['nama'];
                //dd(Employee::where('name', 'like', '%'.$row['nama'].'%')->first());
                if(!empty($row['nip'])){
                    $employee = $this->employees->where('id_employee', $row['nip'])->first();
                }elseif(!empty($row['nama'])){
                    $employee = Employee::where('name', 'LIKE', '%'.$name_import.'%')->first();
                }else{
                    $employee = Employee::where('id_employee', $row['nip'])->orwhere('name', 'LIKE', '%'.$name_import.'%')->first();
                }
                //dd($employee);
                if(!is_null($employee)){
                    $str_employee = substr($employee->id_employee, 4);
                    $str_year = substr($this->latest_per, -5);
                    $str_sub = substr($criteria->id_criteria, 4);
                    $id_input = "INP-".$str_year.'-'.$str_employee.'-'.$str_sub;
                    //dd($id_input);
                    if(isset($row[$criteria->source])){
                        if($criteria->id_criteria == $this->val_setting){ //STG
                            //dd('Yes');
                            $remain = $this->active_days - $row[$criteria->source];
                            if($this->per_status == 'Scoring'){
                                Input::firstOrCreate([
                                    'id_input' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_employee' => $employee->id_employee,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $remain,
                                    'input_raw' => $remain,
                                    'status' => 'Not Converted',
                                ]);
                                /*
                                InputRAW::firstOrCreate([
                                    'id_input_raw' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_employee' => $employee->id_employee,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $remain,
                                    'status' => 'Not Converted',
                                ]);
                                */
                            }elseif($this->per_status == 'Verifying'){
                                Input::firstOrCreate([
                                    'id_input' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_employee' => $employee->id_employee,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $remain,
                                    'input_raw' => $remain,
                                    'status' => 'Not Converted',
                                ]);
                                /*
                                InputRAW::firstOrCreate([
                                    'id_input_raw' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_employee' => $employee->id_employee,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $remain,
                                    'status' => 'Not Converted',
                                ]);
                                */
                            }
                        }else{
                            //dd('No');
                            if($this->per_status == 'Scoring'){
                                Input::firstOrCreate([
                                    'id_input' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_employee' => $employee->id_employee,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $row[$criteria->source],
                                    'input_raw' => $row[$criteria->source],
                                    'status' => 'Not Converted',
                                ]);
                                /*
                                InputRAW::firstOrCreate([
                                    'id_input_raw' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_employee' => $employee->id_employee,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $row[$criteria->source],
                                    'status' => 'Not Converted',
                                ]);
                                */
                            }elseif($this->per_status == 'Verifying'){
                                Input::firstOrCreate([
                                    'id_input' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_employee' => $employee->id_employee,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $row[$criteria->source],
                                    'input_raw' => $row[$criteria->source],
                                    'status' => 'Not Converted',
                                ]);
                                /*
                                InputRAW::firstOrCreate([
                                    'id_input_raw' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_employee' => $employee->id_employee,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $row[$criteria->source],
                                    'status' => 'Not Converted',
                                ]);
                                */
                            }
                        }
                    }

                    /*
                    $check_score = $this->scores->where('id_employee', $employee->id_employee)->where('status', 'Rejected')->first();
                    if(!is_null($check_score)){
                        Score::where('id_employee', $employee->id_employee)->where('status', 'Rejected')->update([
                            'status'=>'Revised',
                        ]);
                    }
                    */
                }else{
                    //SKIP
                }
            }
        }
    }

    /*
    public function model(array $row)
    {
        foreach($this->criterias as $criteria){
            $employee = $this->employees->where('id_employee', $row['nip'])->first();
            $str_employee = substr($employee->id_employee, 4);
            $str_year = substr($this->latest_per, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_input = "INP-".$str_year.'-'.$str_employee.'-'.$str_sub;
            //dd($id_input);
            return new Input([
                'id_input' => $id_input,
                'id_period' => $this->latest_per,
                'id_employee' => $employee->id_employee,
                'id_criteria' => $criteria->id_criteria,
                'input' => $row[$criteria->source],
                'status' => 'Not Converted',
            ]);
        }
    }
    */
}
