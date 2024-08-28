<?php

namespace App\Imports;

use App\Models\Criteria;
use App\Models\Input;
use App\Models\InputRAW;
use App\Models\Officer;
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

    protected $latest_per, $active_days, $officers, $criterias, $scores, $inputs, $val_setting, $per_status;

    public function __construct($period)
    {
        $this->latest_per = $period;
        $this->active_days = Period::where('id_period', $period)->first()->active_days;
        $this->officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){
            $query->where('name', 'Developer');
        })
        ->where('is_lead', 'No')
        ->orderBy('name', 'ASC')->get();
        $this->criterias = Criteria::with('category')->get();
        $this->scores = Score::where('id_period', $period)->get();
        $this->inputs = Input::where('id_period', $period)->get();
        $this->val_setting = Setting::where('id_setting', 'STG-001')->first()->value;
        $this->per_status = Period::where('id_period', $period)->first()->status;
    }

    public function collection(Collection $rows)
    {
        //dd($this->set_crit);
        foreach($this->criterias as $criteria){
            foreach ($rows as $row){
                //dd($row);
                $officer = $this->officers->where('nip', $row['nip'])->first();
                //dd(!is_null($officer));
                if(!is_null($officer)){
                    $str_officer = substr($officer->id_officer, 4);
                    $str_year = substr($this->latest_per, -5);
                    $str_sub = substr($criteria->id_criteria, 4);
                    $id_input = "INP-".$str_year.'-'.$str_officer.'-'.$str_sub;
                    //dd($id_input);
                    if(isset($row[$criteria->source])){
                        if($criteria->id_criteria == $this->val_setting){ //STG
                            //dd('Yes');
                            $remain = $this->active_days - $row[$criteria->source];
                            if($this->per_status == 'Scoring'){
                                Input::firstOrCreate([
                                    'id_input' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_officer' => $officer->id_officer,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $remain,
                                    'input_raw' => $remain,
                                    'status' => 'Pending',
                                ]);
                                /*
                                InputRAW::firstOrCreate([
                                    'id_input_raw' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_officer' => $officer->id_officer,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $remain,
                                    'status' => 'Pending',
                                ]);
                                */
                            }elseif($this->per_status == 'Validating'){
                                Input::firstOrCreate([
                                    'id_input' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_officer' => $officer->id_officer,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $remain,
                                    'input_raw' => $remain,
                                    'status' => 'Fixed',
                                ]);
                                /*
                                InputRAW::firstOrCreate([
                                    'id_input_raw' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_officer' => $officer->id_officer,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $remain,
                                    'status' => 'Fixed',
                                ]);
                                */
                            }
                        }else{
                            //dd('No');
                            if($this->per_status == 'Scoring'){
                                Input::firstOrCreate([
                                    'id_input' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_officer' => $officer->id_officer,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $row[$criteria->source],
                                    'input_raw' => $row[$criteria->source],
                                    'status' => 'Pending',
                                ]);
                                /*
                                InputRAW::firstOrCreate([
                                    'id_input_raw' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_officer' => $officer->id_officer,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $row[$criteria->source],
                                    'status' => 'Pending',
                                ]);
                                */
                            }elseif($this->per_status == 'Validating'){
                                Input::firstOrCreate([
                                    'id_input' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_officer' => $officer->id_officer,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $row[$criteria->source],
                                    'input_raw' => $row[$criteria->source],
                                    'status' => 'Fixed',
                                ]);
                                /*
                                InputRAW::firstOrCreate([
                                    'id_input_raw' => $id_input,
                                    'id_period' => $this->latest_per,
                                    'id_officer' => $officer->id_officer,
                                    'id_criteria' => $criteria->id_criteria,
                                ],[
                                    'input' => $row[$criteria->source],
                                    'status' => 'Fixed',
                                ]);
                                */
                            }
                        }
                    }

                    $check_score = $this->scores->where('id_officer', $officer->id_officer)->where('status', 'Rejected')->first();
                    if(!is_null($check_score)){
                        Score::where('id_officer', $officer->id_officer)->where('status', 'Rejected')->update([
                            'status'=>'Revised',
                        ]);
                    }
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
            $officer = $this->officers->where('nip', $row['nip'])->first();
            $str_officer = substr($officer->id_officer, 4);
            $str_year = substr($this->latest_per, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_input = "INP-".$str_year.'-'.$str_officer.'-'.$str_sub;
            //dd($id_input);
            return new Input([
                'id_input' => $id_input,
                'id_period' => $this->latest_per,
                'id_officer' => $officer->id_officer,
                'id_criteria' => $criteria->id_criteria,
                'input' => $row[$criteria->source],
                'status' => 'Pending',
            ]);
        }
    }
    */
}
