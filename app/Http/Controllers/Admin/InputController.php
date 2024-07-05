<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InputsExport;
use App\Http\Controllers\Controller;
use App\Imports\InputsImport;
use App\Models\Category;
use App\Models\Crips;
use App\Models\Input;
use App\Models\Criteria;
use App\Models\HistoryInput;
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
        $officers = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){
            $query->where('name', 'Developer');
        })
        ->whereDoesntHave('user', function($query){
            $query->whereIn('part', ['KBPS']);
        })
        ->orderBy('name', 'ASC')
        ->get();
        $inputs = Input::get();
        $status = Input::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $periods = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending'])->get();
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending', 'Finished'])->latest()->first();
        $history_per = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->get();
        $categories = Category::with('criteria')->get();
        $allcriterias = Criteria::with('category')->get();
        $criterias = Criteria::get();
        $countsub = Criteria::count();
        $crips = Crips::orderBy('value_from', 'ASC')->get();
        $histories = HistoryInput::get();
        $hofficers = HistoryInput::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->get();
        $hcriterias = HistoryInput::select('id_criteria', 'criteria_name')->groupBy('id_criteria', 'criteria_name')->get();
        $hallsub = HistoryInput::select('id_category', 'category_name', 'id_criteria', 'criteria_name',)->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name',)->get();
        $hsubs = HistoryInput::select('id_criteria', 'criteria_name')->groupBy('id_criteria', 'criteria_name')->get();

        return view('Pages.Admin.input', compact('officers', 'inputs', 'status', 'periods', 'latest_per', 'history_per', 'categories', 'allcriterias', 'criterias', 'countsub', 'crips', 'histories', 'hofficers', 'hcriterias', 'hallsub', 'hsubs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $criterias = Criteria::get();
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
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Tambah Data Kehadiran Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $criterias = Criteria::get();
        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: INP-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_input = "INP-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //UPDATE DATA
            if(Input::where('id_input', $id_input)->exists()){
                Input::where('id_input', $id_input)->update([
                    'input'=>$request->input($criteria->id_criteria),
                    'status'=>'Pending',
                ]);
                Score::where('id_period', $request->id_period)->where('id_officer', $request->id_officer)->update([
                    'status'=>'Revised',
                ]);
            }else{
                Input::insert([
                    'id_input'=>$id_input,
                    'id_period'=>$request->id_period,
                    'id_officer'=>$request->id_officer,
                    'id_criteria'=>$criteria->id_criteria,
                    'input'=>$request->input($criteria->id_criteria),
                    'status'=>'Pending',
                ]);
            }
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Ubah Data Kehadiran Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
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
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Hapus Data Kehadiran Berhasil')->with('code_alert', 1);
    }

    public function destroyall(Request $request, $period)
    {
        //DELETE DATA
        Input::where('id_period', $period)->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('success','Hapus Semua Data Kehadiran Berhasil')->with('code_alert', 1);
    }

    public function import(Request $request, $period)
    {
        //GET DATA
        $crips = Crips::with('criteria')->get();
        $allcriterias = Criteria::get();

        //CHECK CRIPS (DISABLE ONLY FOR TESTING PURPOSE)
        /*
        foreach($allcriterias as $criteria){
            //dd($crips->where('id_criteria', $criteria->id_criteria)->count());
            if(count($crips->where('id_criteria', $criteria->id_criteria)) == 0){
                return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('fail','Import Data Tidak Berhasil. Terdapat Data Crips yang belum ditambahkan untuk konversi nilai. Silahkan tambahkan Data Crips di halaman Kriteria. ('.$criteria->id_criteria.')')->with('code_alert', 1);
            }
        }*/

        //IMPORT MULTIPLE FILE
        $file = $request->file('file');
        foreach($file as $f){
            //GET NAME
            $name_file = $f->getClientOriginalName();
            //DELETE REMAINING DATA AND GET CRITERIA
            if(Str::contains($name_file, 'Presensi') || Str::contains($name_file, 'presensi')){
                //dd('Presensi Detected');
                Input::with('criteria')
                ->whereHas('criteria', function($query){
                    $query->with('category')
                    ->whereHas('category', function($query){
                        $query->where('source', 'Presensi');
                    });
                })
                ->delete();
                $criterias = Criteria::with('category')
                ->whereHas('category', function($query){
                    $query->where('source', 'Presensi');
                })->get();
            }elseif(Str::contains($name_file, 'SKP') || Str::contains($name_file, 'skp')){
                //dd('SKP Detected');
                Input::with('criteria')
                ->whereHas('criteria', function($query){
                    $query->with('category')
                    ->whereHas('category', function($query){
                        $query->where('source', 'SKP');
                    });
                })
                ->delete();
                $criterias = Criteria::with('category')
                ->whereHas('category', function($query){
                    $query->where('source', 'SKP');
                })->get();
            }elseif(Str::contains($name_file, 'CKP') || Str::contains($name_file, 'ckp')){
                //dd('CKP Detected');
                Input::with('criteria')
                ->whereHas('criteria', function($query){
                    $query->with('category')
                    ->whereHas('category', function($query){
                        $query->where('source', 'CKP');
                    });
                })
                ->delete();
                $criterias = Criteria::with('category')
                ->whereHas('category', function($query){
                    $query->where('source', 'CKP');
                })->get();
            }else{
                DB::table('inputs')->delete();
                $criterias = Criteria::get();
            }
            //IMPORT FILE
            Excel::import(new InputsImport($period), $f->store('temp'));
            //UPDATE VALUE ACCORDING TO DATA CRIPS (DISABLE ONLY FOR TESTING PURPOSE)
            $inputs = Input::get();
            foreach($criterias as $criteria){
                foreach($inputs->where('id_period', $period)->where('id_criteria', $criteria->id_criteria) as $input){
                    foreach($crips->where('id_criteria', $criteria->id_criteria) as $crip){
                        //dd($input->input.'<='.$crip->value_from);
                        if(($input->input >= 0) && ($input->input <= $crip->value_from) && ($crip->value_type == 'Less')){
                            //($input->input <= $crip->value_from) && ($crip->value_type == 'Less')
                            Input::where('id_input', $input->id_input)->update([
                                'input'=>$crip->score,
                            ]);
                        }elseif(($input->input >= $crip->value_from) && ($input->input <= $crip->value_to) && ($crip->value_type == 'Between')){
                            //($crip->value_from <= $input->input) && ($input->input <= $crip->value_to) && ($crip->value_type == 'Between')
                            Input::where('id_input', $input->id_input)->update([
                                'input'=>$crip->score,
                            ]);
                        }elseif(($input->input >= $crip->value_from) && ($input->input <= $criteria->max) && ($crip->value_type == 'More')){
                            //($crip->value_from <= $input->input) && ($crip->value_type == 'More')
                            Input::where('id_input', $input->id_input)->update([
                                'input'=>$crip->score,
                            ]);
                        }else{
                            /*
                            if($crip->value_type == 'Less'){
                                return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('fail','Import Data Gagal. Terdapat gap yang ada di range Kriteria. Silahkan cek kembali data crips pada setiap Kriteria di halaman Kriteria. ('.$criteria->name.') ('.$crip->value_type.') ('.$input->input.'>=0) ('.$input->input.'<='.$crip->value_from.')')->with('code_alert', 1);
                            }elseif($crip->value_type == 'Between'){
                                return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('fail','Import Data Gagal. Terdapat gap yang ada di range Kriteria. Silahkan cek kembali data crips pada setiap Kriteria di halaman Kriteria. ('.$criteria->name.') ('.$crip->value_type.') ('.$input->input.'>='.$crip->value_from.') ('.$input->input.'<='.$crip->value_to.')')->with('code_alert', 1);
                            }elseif($crip->value_type == 'More'){
                                return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('fail','Import Data Gagal. Terdapat gap yang ada di range Kriteria. Silahkan cek kembali data crips pada setiap Kriteria di halaman Kriteria. ('.$criteria->name.') ('.$crip->value_type.') ('.$input->input.'>='.$crip->value_from.') ('.$input->input.'<='.$criteria->max.')')->with('code_alert', 1);
                            }
                            */
                        }
                    }
                }
            }
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('success','Import Data Berhasil')->with('code_alert', 1);
    }

    public function export(Request $request, $period)
    {
        //$periods = Period::where('id_period', $period)->first();

        return Excel::download(new InputsExport($period), 'INP-Backup-'.$period.'.xlsx');
    }
}
