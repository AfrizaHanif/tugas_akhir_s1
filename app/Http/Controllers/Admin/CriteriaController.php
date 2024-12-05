<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Category;
use App\Models\Crips;
use App\Models\Input;
use App\Models\Log;
use App\Models\Period;
use App\Models\Setting;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //GET DATA
        $categories = Category::get();
        $criterias = Criteria::get();
        $crips = Crips::orderBy('value_from', 'ASC')->get();
        //dd($crips);

        //RETURN TO VIEW
        return view('Pages.Admin.criteria', compact('categories', 'criterias', 'crips'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Kriteria',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Kriteria Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tidak dapat melakukan proses pembuatan kriteria dikarenakan sedang dalam proses verifikasi nilai.')
                ->with('code_alert', 1)
                ->withInput(['tab_redirect'=>'pills-'.$request->id_category])
                ->with('modal_redirect', 'modal-crt-create');
            }
        }

        //COMBINE KODE
        /*
        $total_id = Criteria::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        */
        $str_cat = substr($request->id_category, 4); //CRT-000-xxx (CAT-CRT)
        $id_criteria = IdGenerator::generate([
            'table'=>'criterias',
            'field'=>'id_criteria',
            'length'=>7,
            //'length'=>11,
            'prefix'=>'CRT-',
            //'prefix'=>'CRT-'.$str_cat.'-',
            'reset_on_prefix_change'=>true,
        ]);
        //dd($id_criteria);
        //$id_criteria = $gen_cri.'-'.$str_cat;

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => 'unique:criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Kriteria',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Kriteria Tidak Berhasil (Beberapa Data Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$request->id_category])
            ->with('modal_redirect', 'modal-crt-create')
            ->with('code_alert', 2);
        }

        //CONVERT TO PERCENTAGE
        $weight = $request->weight / 100;

        //MODIFY SOURCE TEXT
        $str_src_trim = trim($request->source); //REMOVE WHITESPACE
        $str_src_replace = str_replace(' ', '_', $str_src_trim); //REPLACE UNDERSCORE TO SPACE
        $source = strtolower($str_src_replace); //LOWER ALL CHARACTER

        //STORE DATA
        Criteria::insert([
            'id_criteria'=>$id_criteria,
            'id_category'=>$request->id_category,
            'name'=>$request->name,
            'weight'=>$weight,
            'attribute'=>$request->attribute,
            //'level'=>$request->level,
            'max'=>$request->max,
            'unit'=>$request->unit,
            //'need'=>$request->need,
            'source'=>$source,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Kriteria',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Kriteria Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Scoring'){ //PROGRESS: SCORING
                return redirect()
                ->route('admin.masters.criterias.index')
                ->withInput(['tab_redirect'=>'pills-'.$request->id_category])
                ->with('success','Tambah Kriteria Berhasil. Jika diperlukan, silahkan lakukan import ulang.')
                ->with('code_alert', 1);
            }
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withInput(['tab_redirect'=>'pills-'.$request->id_category])
            ->with('success','Tambah Kriteria Berhasil')
            ->with('code_alert', 1);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Criteria $criteria)
    {
        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Kriteria',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Kriteria Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tidak dapat melakukan perubahan data kriteria dikarenakan sedang dalam proses verifikasi nilai.')
                ->with('code_alert', 1)
                ->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])
                ->with('modal_redirect', 'modal-crt-update');
            }
        }

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('criterias')->ignore($criteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Kriteria',
                'progress'=>'Update',
                'result'=>'Error',
                'descriptions'=>'Ubah Kriteria Tidak Berhasil (Beberapa Data Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])
            ->with('modal_redirect', 'modal-crt-update')
            ->with('id_redirect', $criteria->id_category)
            ->with('code_alert', 2);
        }

        //CONVERT TO PERCENTAGE
        $weight = $request->weight / 100;

        //MODIFY SOURCE TEXT
        $str_src_trim = trim($request->source); //REMOVE WHITESPACE
        $str_src_replace = str_replace(' ', '_', $str_src_trim); //REPLACE UNDERSCORE TO SPACE
        $source = strtolower($str_src_replace); //LOWER ALL CHARACTER

        //STORE DATA
        $criteria->update([
            'name'=>$request->name,
            'weight'=>$weight,
            'attribute'=>$request->attribute,
            //'level'=>$request->level,
            'max'=>$request->max,
            'unit'=>$request->unit,
            //'need'=>$request->need,
            'source'=>$source,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Kriteria',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Kriteria Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Scoring'){ //PROGRESS: SCORING
                return redirect()
                ->route('admin.masters.criterias.index')
                ->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])
                ->with('success','Ubah Kriteria Berhasil. Jika diperlukan, silahkan lakukan import ulang')
                ->with('code_alert', 1);
            }
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])
            ->with('success','Ubah Kriteria Berhasil')
            ->with('code_alert', 1);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criteria $criteria)
    {
        //GET LATEST PERIOD
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Kriteria',
                    'progress'=>'Delete',
                    'result'=>'Error',
                    'descriptions'=>'Hapus Kriteria Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tidak dapat melakukan penghapusan kriteria dikarenakan sedang dalam proses verifikasi nilai.')
                ->with('code_alert', 1)
                ->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])
                ->with('modal_redirect', 'modal-crt-update');
            }
        }

        //CHANGE SETTING
        $check_setting = Setting::where('id_setting', 'STG-001')->first();
        if($check_setting->value == $criteria->id_criteria){
            Setting::where('id_setting', 'STG-001')->update([
                'value'=>'None',
            ]);
        }

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Kriteria',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Kriteria Berhasil ('.$criteria->name.')',
        ]);

        //DESTROY DATA
        Crips::where('id_criteria', $criteria->id_criteria)->delete();
        Input::where('id_criteria', $criteria->id_criteria)->delete();
        $criteria->delete();

        //RETURN TO VIEW
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Scoring'){ //PROGRESS: SCORING
                return redirect()
                ->route('admin.masters.criterias.index')
                ->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])
                ->with('success','Hapus Kriteria Berhasil. Jika diperlukan, silahkan lakukan import ulang')
                ->with('code_alert', 1);
            }
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])
            ->with('success','Hapus Kriteria Berhasil')
            ->with('code_alert', 1);
        }
    }
}
