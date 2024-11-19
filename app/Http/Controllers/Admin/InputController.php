<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InputsAllOldExport;
use App\Exports\InputsExport;
use App\Exports\InputsOldExport;
use App\Http\Controllers\Controller;
use App\Imports\InputsImport;
use App\Imports\InputsImportSingle;
use App\Models\Category;
use App\Models\Crips;
use App\Models\Input;
use App\Models\Criteria;
use App\Models\HistoryInput;
use App\Models\HistoryInputRAW;
use App\Models\InputRAW;
use App\Models\Log;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InputController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //GET DATA
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'LIKE', 'Kepala BPS%');
        })
        ->orderBy('name', 'ASC')
        ->get();
        $inputs = Input::get();
        //$input_raws = InputRAW::get();
        $status = Input::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $periods = Period::orderBy('id_period', 'ASC')->whereIn('progress_status', ['Scoring', 'Verifying', 'Finished'])->get();
        $categories = Category::with('criteria')->get();
        $allcriterias = Criteria::with('category')->get();
        $criterias = Criteria::get();
        $countsub = Criteria::count();
        $crips = Crips::orderBy('value_from', 'ASC')->get();
        $scores = Score::get();

        //GET PERIOD FOR LIST
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('progress_status', ['Skipped', 'Pending', 'Finished'])->latest()->first();
        $history_per = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('period_year', 'ASC')->orderBy('period_num_month', 'ASC')->get();

        //GET HISTORY
        $histories = HistoryInput::get();
        //$hraws = HistoryInputRAW::get();
        $hofficers = HistoryInput::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_position')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_position')->get();
        $hcriterias = HistoryInput::select('id_criteria', 'criteria_name', 'id_period', 'unit')->groupBy('id_criteria', 'criteria_name', 'id_period', 'unit')->get();
        $hallsub = HistoryInput::select('id_category', 'category_name', 'id_criteria', 'criteria_name',)->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name',)->get();
        $hsubs = HistoryInput::select('id_criteria', 'criteria_name')->groupBy('id_criteria', 'criteria_name')->get();

        //RETURN TO VIEW
        return view('Pages.Admin.input', compact('officers', 'inputs', 'status', 'periods', 'latest_per', 'history_per', 'categories', 'allcriterias', 'criterias', 'countsub', 'crips', 'scores', 'histories', 'hofficers', 'hcriterias', 'hallsub', 'hsubs'));
    }

    /*
    public function store(Request $request)
    {
        //GET DATA
        $criterias = Criteria::get();
        $latest_per = Period::where('id_period', $request->id_period)->first();

        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: INP-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4); //CRT-000-000
            $id_input = "INP-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //STORE DATA
            Input::insert([
                'id_input'=>$id_input,
                'id_period'=>$request->id_period,
                'id_officer'=>$request->id_officer,
                'id_criteria'=>$criteria->id_criteria,
                'input'=>$request->input($criteria->id_criteria),
                'status'=>'Pending',
            ]);

            //UPDATE STATUS IN INPUTS
            if($latest_per->progress_status == 'Scoring'){
                Input::where('id_input', $id_input)->update([
                    'status' => 'Pending',
                ]);
            }elseif($latest_per->progress_status == 'Verifying'){
                Input::where('id_input', $id_input)->update([
                    'status' => 'Fixed',
                ]);
            }
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Tambah Data Nilai Berhasil')->with('code_alert', 1);
    }

    public function update(Request $request)
    {
        //GET DATA
        $criterias = Criteria::get();
        $latest_per = Period::where('id_period', $request->id_period)->first();

        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: INP-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_input = "INP-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //UPDATE DATA
            if(Input::where('id_input', $id_input)->exists()){
                //IF INPUT EXISTS
                Input::where('id_input', $id_input)->update([
                    'input'=>$request->input($criteria->id_criteria),
                    'status'=>'Pending',
                ]);
                Score::where('id_period', $request->id_period)->where('id_officer', $request->id_officer)->update([
                    'status'=>'Revised',
                ]);
            }else{
                //IF INPUT NOT EXISTS (NOT FILLED)
                Input::insert([
                    'id_input'=>$id_input,
                    'id_period'=>$request->id_period,
                    'id_officer'=>$request->id_officer,
                    'id_criteria'=>$criteria->id_criteria,
                    'input'=>$request->input($criteria->id_criteria),
                    'status'=>'Pending',
                ]);
            }

            //UPDATE STATUS IN INPUTS
            if($latest_per->progress_status == 'Scoring'){
                Input::where('id_input', $id_input)->update([
                    'status' => 'Pending',
                ]);
            }elseif($latest_per->progress_status == 'Verifying'){
                Input::where('id_input', $id_input)->update([
                    'status' => 'Fixed',
                ]);
            }
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Ubah Data Nilai Berhasil')->with('code_alert', 1);
    }

    public function destroy(Request $request)
    {
        //GET DATA
        $criterias = Criteria::get();

        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: INP-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_input = "INP-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //DELETE DATA
            Input::where('id_input', $id_input)->delete();
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Hapus Data Nilai Berhasil')->with('code_alert', 1);
    }
    */

    public function destroyall($period)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $period)->first();

        //DELETE ALL DATA
        if($latest_per->progress_status == 'Verifying'){
            Input::where('id_period', $period)->where('status', 'Fixed')->orWhere('status', 'Not Converted')->delete();
        }else{
            Input::where('id_period', $period)->delete();
        }
        //InputRAW::where('id_period', $period)->delete();

        //UPDATE IMPORT STATUS
        Period::where('id_period', $period)->update([
            'import_status'=>'No Data',
        ]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Hapus Semua Nilai',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Seluruh Data Berhasil ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('success','Hapus Semua Data Nilai Berhasil')->with('code_alert', 1);
    }

    public function import(Request $request, $period)
    {
        //GET DATA
        $crips = Crips::with('criteria')->get();
        $latest_per = Period::where('id_period', $period)->first();
        $allcriterias = Criteria::get();
        //$inp_rejects = Input::where('id_period', $period)->where('status', 'Need Fix')->get();
        //dd($inp_rejects);

        //CHECK WEIGHTS
        if($allcriterias->sum('weight')*100 > 100){ //IF MORE THAN 100%
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Import Nilai',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Import Data Tidak Berhasil (Bobot Melebihi 100%)',
            ]);

            //RETURN TO VIEW
            return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('fail','Import Data Tidak Berhasil. Total Bobot melebihi 100%. Cek kembali bobot di setiap kriteria')->with('code_alert', 1);
        }elseif($allcriterias->sum('weight')*100 <= 99){ //IF LESS THAN 100%
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Import Nilai',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Import Data Tidak Berhasil (Bobot Belum Mencapai 100%)',
            ]);

            //RETURN TO VIEW
            return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('fail','Import Data Tidak Berhasil. Total Bobot belum mencapai 100%. Cek kembali bobot di setiap kriteria')->with('code_alert', 1);
        }

        //CHECK CRIPS (DISABLE ONLY FOR TESTING PURPOSE)
        foreach($allcriterias as $criteria){
            //dd($crips->where('id_criteria', $criteria->id_criteria)->count());
            if(count($crips->where('id_criteria', $criteria->id_criteria)) == 0){ //IF NO DATA CRIPS IN CRITERIA
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Import Nilai',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Import Data Tidak Berhasil (Tidak Ada Data Crips di Kriteria '.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('fail','Import Data Tidak Berhasil. Terdapat Data Crips yang belum ditambahkan untuk konversi nilai. Silahkan tambahkan Data Crips di halaman Kriteria. ('.$criteria->id_criteria.')')->with('code_alert', 1);
            }
        }

        //IMPORT MULTIPLE FILE (WITH LOOP)
        //GET MULTIPLE FILE
        $file = $request->file('file');
        foreach($file as $f){
            //GET NAME
            $name_file = $f->getClientOriginalName();

            //DELETE REMAINING DATA
            if(Str::contains($name_file, $latest_per->month) && Str::contains($name_file, $latest_per->year)) //IF FILE CONTAINS DATE AND MONTH FROM RUNNING PERIOD
            {
                if(Str::contains($name_file, 'Presensi') || Str::contains($name_file, 'presensi')){ //IF FILE CONTAINS PRESENSI
                    //dd('Presensi Detected');
                    if($latest_per->progress_status == 'Scoring'){
                        Input::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'Presensi');
                            });
                        })
                        ->delete();
                        /*
                        InputRAW::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'Presensi');
                            });
                        })
                        ->delete();
                        */
                    }elseif($latest_per->progress_status == 'Verifying'){
                        Input::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'Presensi');
                            });
                        })
                        ->whereIn('status', ['Need Fix', 'Fixed'])
                        ->delete();
                        /*
                        InputRAW::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'Presensi');
                            });
                        })
                        ->whereIn('status', ['Need Fix', 'Fixed'])
                        ->delete();
                        */
                    }
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Import Nilai',
                        'progress'=>'Delete',
                        'result'=>'Success',
                        'descriptions'=>'Hapus Data Presensi Sebelumnya Berhasil ('.$name_file.')',
                    ]);
                }elseif(Str::contains($name_file, 'SKP') || Str::contains($name_file, 'skp')){ //IF FILE CONTANS SKP
                    //dd('SKP Detected');
                    if($latest_per->progress_status == 'Scoring'){
                        Input::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'SKP');
                            });
                        })
                        ->delete();
                        /*
                        InputRAW::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'SKP');
                            });
                        })
                        ->delete();
                        */
                    }elseif($latest_per->progress_status == 'Verifying'){
                        Input::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'SKP');
                            });
                        })
                        ->whereIn('status', ['Need Fix', 'Fixed'])
                        ->delete();
                        /*
                        InputRAW::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'SKP');
                            });
                        })
                        ->whereIn('status', ['Need Fix', 'Fixed'])
                        ->delete();
                        */
                    }
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Import Nilai',
                        'progress'=>'Delete',
                        'result'=>'Success',
                        'descriptions'=>'Hapus Data SKP Sebelumnya Berhasil ('.$name_file.')',
                    ]);
                }elseif(Str::contains($name_file, 'CKP') || Str::contains($name_file, 'ckp')){ //IF FILE CONTAINS CKP
                    //dd('CKP Detected');
                    if($latest_per->progress_status == 'Scoring'){
                        Input::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'CKP');
                            });
                        })
                        ->delete();
                        /*
                        InputRAW::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'CKP');
                            });
                        })
                        ->delete();
                        */
                    }elseif($latest_per->progress_status == 'Verifying'){
                        Input::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'CKP');
                            });
                        })
                        ->whereIn('status', ['Need Fix', 'Fixed'])
                        ->delete();
                        /*
                        InputRAW::with('criteria')
                        ->whereHas('criteria', function($query){
                            $query->with('category')
                            ->whereHas('category', function($query){
                                $query->where('source', 'CKP');
                            });
                        })
                        ->whereIn('status', ['Need Fix', 'Fixed'])
                        ->delete();
                        */
                    }
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Import Nilai',
                        'progress'=>'Delete',
                        'result'=>'Success',
                        'descriptions'=>'Hapus Data CKP Sebelumnya Berhasil ('.$name_file.')',
                    ]);
                }else{ //IF FILE DIDN'T MEET SOURCE CRITERIA
                    //OPTIONAL: USE RETURN IF FILE NAME NOT CONTAIN PRESENSI, SKP, OR CKP.
                    //DB::table('inputs')->delete();
                    //$criterias = Criteria::get();
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Import Nilai',
                        'progress'=>'Create',
                        'result'=>'Error',
                        'descriptions'=>'Import Data Tidak Berhasil (Nama File Tidak Sesuai (Sumber File))',
                    ]);

                    //RETURN TO VIEW
                    return redirect()->route('admin.inputs.data.index')->with('fail','Import Data Gagal. Penamaan file tidak sesuai dengan kriteria yang berlaku (Presensi, CKP, atau SKP).')->with('modal_redirect', 'modal-inp-import')->with('code_alert', 2);
                }
            }else{ //IF FILE DIDN'T MEET DATE AND MONTH CRITERIA
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Import Nilai',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Import Data Tidak Berhasil (Nama File Tidak Sesuai (Bulan dan Tahun))',
                ]);

                //RETURN TO VIEW
                return redirect()->route('admin.inputs.data.index')->with('fail','Import Data Gagal. Penamaan file tidak sesuai dengan kriteria yang berlaku (Bulan dan Tahun).')->with('modal_redirect', 'modal-inp-import')->with('code_alert', 2);
            }

            //IMPORT FILE (USING LARAVEL EXCEL)
            Excel::import(new InputsImport($period), $f->store('temp'));

            /*
            //UPDATE VALUE ACCORDING TO DATA CRIPS (DISABLE ONLY FOR TESTING PURPOSE)
            //GET INPUT DATA AND CRITERIA AFTER IMPORT
            if(Str::contains($name_file, 'Presensi') || Str::contains($name_file, 'presensi')){
                if($latest_per->progress_status == 'Scoring'){
                    $inputs = Input::with('criteria')
                    ->whereHas('criteria', function($query){
                        $query->with('category')
                        ->whereHas('category', function($query){
                            $query->where('source', 'Presensi');
                        });
                    })->get();
                }elseif($latest_per->progress_status == 'Verifying'){
                    $inputs = Input::with('criteria')
                    ->whereHas('criteria', function($query){
                        $query->with('category')
                        ->whereHas('category', function($query){
                            $query->where('source', 'Presensi');
                        });
                    })
                    ->where('status', 'Fixed')
                    ->get();
                }
                $criterias = Criteria::with('category')
                ->whereHas('category', function($query){
                    $query->where('source', 'Presensi');
                })->get();
            }elseif(Str::contains($name_file, 'SKP') || Str::contains($name_file, 'skp')){
                if($latest_per->progress_status == 'Scoring'){
                    $inputs = Input::with('criteria')
                    ->whereHas('criteria', function($query){
                        $query->with('category')
                        ->whereHas('category', function($query){
                            $query->where('source', 'SKP');
                        });
                    })->get();
                }elseif($latest_per->progress_status == 'Verifying'){
                    $inputs = Input::with('criteria')
                    ->whereHas('criteria', function($query){
                        $query->with('category')
                        ->whereHas('category', function($query){
                            $query->where('source', 'SKP');
                        });
                    })
                    ->where('status', 'Fixed')
                    ->get();
                }
                $criterias = Criteria::with('category')
                ->whereHas('category', function($query){
                    $query->where('source', 'SKP');
                })->get();
            }elseif(Str::contains($name_file, 'CKP') || Str::contains($name_file, 'ckp')){
                if($latest_per->progress_status == 'Scoring'){
                    $inputs = Input::with('criteria')
                    ->whereHas('criteria', function($query){
                        $query->with('category')
                        ->whereHas('category', function($query){
                            $query->where('source', 'CKP');
                        });
                    })->get();
                }elseif($latest_per->progress_status == 'Verifying'){
                    $inputs = Input::with('criteria')
                    ->whereHas('criteria', function($query){
                        $query->with('category')
                        ->whereHas('category', function($query){
                            $query->where('source', 'CKP');
                        });
                    })
                    ->where('status', 'Fixed')
                    ->get();
                }
                $criterias = Criteria::with('category')
                ->whereHas('category', function($query){
                    $query->where('source', 'CKP');
                })->get();
            }
            */

            //CREATE A LOG (PER FILE)
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Import Nilai',
                'progress'=>'Create',
                'result'=>'Success',
                'descriptions'=>'Import Data Berhasil ('.$name_file.')',
            ]);
        }

        //UPDATE IMPORT STATUS
        Period::where('id_period', $period)->update([
            'import_status'=>'Not Clear',
        ]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Import Nilai',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Import Data Berhasil ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('success','Import Data Berhasil. Silahkan cek kembali sebelum dilakukan Konversi Data')->with('code_alert', 1);
    }

    public function convert($period)
    {
        //GET DATA
        $officers = Officer::get();
        $latest_per = Period::where('id_period', $period)->first();

        //UPDATE VALUE ACCORDING TO DATA CRIPS (DISABLE ONLY FOR TESTING PURPOSE)
        //GET INPUT DATA AND CRITERIA
        $inputs = Input::where('id_period', $period)->where('status', 'Not Converted')->get();
        $criterias = Criteria::get();
        $crips = Crips::with('criteria')->get();
        //UPDATE DATA
        foreach($criterias as $criteria){
            foreach($inputs->where('id_period', $period)->where('id_criteria', $criteria->id_criteria) as $input){
                foreach($crips->where('id_criteria', $criteria->id_criteria) as $crip){
                    //dd(($input->input >= 0) && ($input->input <= $crip->value_from));
                    if(($input->input >= 0) && ($input->input <= $crip->value_from) && ($crip->value_type == 'Less')){
                        //($input->input <= $crip->value_from) && ($crip->value_type == 'Less')
                        Input::where('id_input', $input->id_input)->update([
                            'input'=>$crip->score,
                            'status' => 'Converted',
                        ]);
                    }elseif(($input->input >= $crip->value_from) && ($input->input <= $crip->value_to) && ($crip->value_type == 'Between')){
                        //($crip->value_from <= $input->input) && ($input->input <= $crip->value_to) && ($crip->value_type == 'Between')
                        Input::where('id_input', $input->id_input)->update([
                            'input'=>$crip->score,
                            'status' => 'Converted',
                        ]);
                    }elseif(($input->input >= $crip->value_from) && ($input->input <= $criteria->max) && ($crip->value_type == 'More')){
                        //($crip->value_from <= $input->input) && ($crip->value_type == 'More')
                        Input::where('id_input', $input->id_input)->update([
                            'input'=>$crip->score,
                            'status' => 'Converted',
                        ]);
                    }
                }
            }
        }

        //UPDATE STATUS IN INPUTS
        foreach(Input::where('id_period', $period)->where('status', 'Converted')->get() as $input){
            if($latest_per->progress_status == 'Scoring'){
                $input->update([
                    'status' => 'Pending',
                ]);
            }elseif($latest_per->progress_status == 'Verifying'){ //IF PREVIOUSLY REJECTED
                $input->update([
                    'status' => 'Fixed',
                ]);
            }
        }
        /*
        if($latest_per->progress_status == 'Scoring'){
            Input::where('status', 'Not Converted')->update([
                'status' => 'Pending',
            ]);
        }elseif($latest_per->progress_status == 'Verifying'){
            Input::where('status', 'Not Converted')->update([
                'status' => 'Fixed',
            ]);
        }
        */

        //UPDATE STATUS IN SCORES
        foreach($officers as $officer){
            $check_score = Score::where('id_period', $period)->where('id_officer', $officer->id_officer)->where('status', 'Rejected')->first();
            if(!is_null($check_score)){ //IF PREVIOUSLY REJECTED
                Score::where('id_officer', $officer->id_officer)->where('status', 'Rejected')->update([
                    'status'=>'Revised',
                ]);
                /*
                Input::where('id_officer', $officer->id_officer)->where('status', 'Need Fix')->update([
                    'status'=>'Fixed',
                ]);
                */
            }
        }

        //UPDATE IMPORT STATUS
        if(Input::where('id_period', $period)->where('status', 'Not Converted')->count() >= 1){ //IF FEW SCORES HAS BEEN CONVERTED
            Period::where('id_period', $period)->update([
                'import_status'=>'Few Clear',
            ]);
        }else{ //IF ALL SCORES HAS BEEN CONVERTED
            Period::where('id_period', $period)->update([
                'import_status'=>'Clear',
            ]);
        }

        //RETURN TO VIEW
        //IF FEW SCORES HAS BEEN CONVERTED
        if(Input::where('id_period', $period)->where('status', 'Not Converted')->count() >= 1){
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Konversi Nilai',
                'progress'=>'Update',
                'result'=>'Warning',
                'descriptions'=>'Konversi Data Sebagian Berhasil ('.$latest_per->name.')',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route('admin.inputs.data.index')
            ->withInput(['tab_redirect'=>'pills-'.$period])
            ->with('warning','Konversi Data Berhasil. Namun terdapat beberapa nilai yang belum berhasil dikonversi. Silahkan cek kembali Data Crips di masing-masing Kriteria')
            ->with('code_alert', 1);
        }
        //IF ALL SCORES HAS BEEN CONVERTED
        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Konversi Nilai',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Konversi Data Berhasil ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()
        ->route('admin.inputs.data.index')
        ->withInput(['tab_redirect'=>'pills-'.$period])
        ->with('success','Konversi Data Berhasil')
        ->with('code_alert', 1);
    }

    public function refresh($period)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $period)->first();

        //MOVE INPUT RAW TO INPUT
        if($latest_per->progress_status == 'Verifying'){ //IF PREVIOUSLY REJECTED
            $move = Input::where('id_period', $period)->where('status', 'Fixed')->get();
        }else{
            $move = Input::where('id_period', $period)->where('status', 'Pending')->get();
        }
        foreach($move as $m){
            $m->update([
                'input' => $m->input_raw,
                'status' => 'Not Converted',
            ]);
        }

        //UPDATE VALUE ACCORDING TO DATA CRIPS (DISABLE ONLY FOR TESTING PURPOSE)
        //GET INPUT DATA AND CRITERIA
        $inputs = Input::where('id_period', $period)->where('status', 'Not Converted')->get();
        $criterias = Criteria::get();
        $crips = Crips::with('criteria')->get();
        //UPDATE DATA
        foreach($criterias as $criteria){
            foreach($inputs->where('id_period', $period)->where('id_criteria', $criteria->id_criteria) as $input){
                foreach($crips->where('id_criteria', $criteria->id_criteria) as $crip){
                    //dd(($input->input >= 0) && ($input->input <= $crip->value_from));
                    if(($input->input >= 0) && ($input->input <= $crip->value_from) && ($crip->value_type == 'Less')){
                        //($input->input <= $crip->value_from) && ($crip->value_type == 'Less')
                        Input::where('id_input', $input->id_input)->update([
                            'input'=>$crip->score,
                            'status' => 'Converted',
                        ]);
                    }elseif(($input->input >= $crip->value_from) && ($input->input <= $crip->value_to) && ($crip->value_type == 'Between')){
                        //($crip->value_from <= $input->input) && ($input->input <= $crip->value_to) && ($crip->value_type == 'Between')
                        Input::where('id_input', $input->id_input)->update([
                            'input'=>$crip->score,
                            'status' => 'Converted',
                        ]);
                    }elseif(($input->input >= $crip->value_from) && ($input->input <= $criteria->max) && ($crip->value_type == 'More')){
                        //($crip->value_from <= $input->input) && ($crip->value_type == 'More')
                        Input::where('id_input', $input->id_input)->update([
                            'input'=>$crip->score,
                            'status' => 'Converted',
                        ]);
                    }
                }
            }
        }

        //UPDATE STATUS IN INPUTS
        foreach(Input::where('id_period', $period)->where('status', 'Converted')->get() as $input){
            if($latest_per->progress_status == 'Scoring'){
                $input->update([
                    'status' => 'Pending',
                ]);
            }elseif($latest_per->progress_status == 'Verifying'){ //IF PREVIOUSLY REJECTED
                $input->update([
                    'status' => 'Fixed',
                ]);
            }
        }
        /*
        Input::where('status', 'Not Converted')->update([
            'status' => 'Pending',
        ]);
        */

        //dd(Input::where('id_period', $period)->where('status', 'Not Converted')->count() >= 1);
        //UPDATE IMPORT STATUS
        if(Input::where('id_period', $period)->where('status', 'Not Converted')->count() >= 1){ //IF FEW SCORES HAS BEEN CONVERTED
            Period::where('id_period', $period)->update([
                'import_status'=>'Few Clear',
            ]);
        }else{ //IF ALL SCORES HAS BEEN CONVERTED
            Period::where('id_period', $period)->update([
                'import_status'=>'Clear',
            ]);
        }

        //RETURN TO VIEW
        //IF FEW SCORES HAS BEEN CONVERTED
        if(Input::where('id_period', $period)->where('status', 'Not Converted')->count() >= 1){
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Refresh Data',
                'progress'=>'Update',
                'result'=>'Warning',
                'descriptions'=>'Refresh Data Sebagian Berhasil ('.$latest_per->name.')',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route('admin.inputs.data.index')
            ->withInput(['tab_redirect'=>'pills-'.$period])
            ->with('warning','Refresh Data Berhasil. Namun terdapat beberapa nilai yang belum berhasil dikonversi. Silahkan cek kembali Data Crips di masing-masing Kriteria')
            ->with('code_alert', 1);
        }
        //IF ALL SCORES HAS BEEN CONVERTED
        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Refresh Data',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Refresh Data Berhasil ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()
        ->route('admin.inputs.data.index')
        ->withInput(['tab_redirect'=>'pills-'.$period])
        ->with('success','Refresh Data Berhasil')
        ->with('code_alert', 1);
    }

    public function reset($period)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $period)->first();

        //MOVE INPUT RAW TO INPUT
        if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
            $move = Input::where('id_period', $period)->where('status', 'Fixed')->get();
        }else{
            $move = Input::where('id_period', $period)->where('status', 'Pending')->get();
        }
        foreach($move as $m){
            $m->update([
                'input' => $m->input_raw,
                'status' => 'Not Converted',
            ]);
        }

        //UPDATE IMPORT STATUS
        Period::where('id_period', $period)->update([
            'import_status'=>'Not Clear',
        ]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Reset Data',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Reset Data Berhasil ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('success','Reset Data Berhasil')->with('code_alert', 1);
    }

    public function export_latest()
    {
        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Export Data',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Export Data Berhasil ('.$latest_per->name.')',
        ]);

        //GET EXPORT FILE
        return Excel::download(new InputsExport($latest_per), 'INP-Backup-'.$latest_per->id_period.'.xlsx');

        //NOTE: NO NEED TO RETURN TO VIEW. LET TOASTS REMIND YOU AFTER EXPORT
    }

    public function export_old($period)
    {
        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Export Data',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Export Riwayat Data Berhasil ('.$period.')',
        ]);

        //GET EXPORT FILE
        return Excel::download(new InputsOldExport($period), 'INP-Backup-'.$period.'.xlsx');

        //NOTE: NO NEED TO RETURN TO VIEW. LET TOASTS REMIND YOU AFTER EXPORT
    }

    //FUTURE DEVELOPMENT
    public function export_all()
    {
        //dd('Coming Soon');

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Export Data',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Export Riwayat Data Berhasil (Semua Periode)',
        ]);

        //GET EXPORT FILE
        return Excel::download(new InputsAllOldExport(), 'INP-Backup-All.xlsx');

        //NOTE: NO NEED TO RETURN TO VIEW. LET TOASTS REMIND YOU AFTER EXPORT
    }
}
