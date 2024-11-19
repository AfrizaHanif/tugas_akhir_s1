<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Crips;
use App\Models\Criteria;
use App\Models\Log;
use App\Models\Period;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CripsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        $criteria = Criteria::where('id_criteria', $request->id_criteria)->first();

        //COMBINE KODE
        /*
        $total_id = Crips::where('id_criteria', $request->id_criteria)->count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $get_cat = Category::with('criteria')
        ->whereHas('criteria', function($query) use($request){$query->where('id_criteria', $request->id_criteria);})
        ->first();
        $str_cat = substr($get_cat->id_category, 4);
        */
        $str_crt = substr($request->id_criteria, 4); //CRP-000-000-xxx (CAT-CRT-CRP)
        $id_crips = IdGenerator::generate([
            'table'=>'crips',
            'field'=>'id_crips',
            //'length'=>15,
            'length'=>11,
            //'prefix'=>'CRP-'.$str_cat.'-'.$str_crt.'-',
            'prefix'=>'CRP-'.$str_crt.'-',
            'reset_on_prefix_change'=>true,
        ]);
        //dd($id_crips);

        //TAB FOR RETURN
        $tab = Category::with('criteria')
        ->whereHas('criteria', function($query) use($request){$query->where('id_criteria', $request->id_criteria);})->latest()->first();

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Data Crips Tidak Berhasil (Proses Verifikasi Sedang Berjalan) ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tambah Data Crips Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //CHECK INPUT RANGE ABNORMAL
        $check_range = Criteria::where('id_criteria', $request->id_criteria)->first();
        if($request->value_from > $check_range->max){ //IF RANGE FROM EXCEED MAXIMUM SCORE
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Data Crips',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Data Crips Tidak Berhasil (Angka Range Pertama lebih besar daripada Nilai Maximum) ('.$criteria->name.')',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route('admin.masters.criterias.index')
            ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range Pertama lebih besar daripada Nilai Maximum)')
            ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
            ->with('modal_redirect', 'modal-crp-view')
            ->with('id_redirect', $request->id_criteria)
            ->with('code_alert', 2);
        }elseif($request->value_type == 'Between'){
            if($request->value_to > $check_range->max){ //IF RANGE TO EXCEED MAXIMUM SCORE
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Data Crips Tidak Berhasil (Angka Range Kedua lebih besar daripada Nilai Maximum) ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range Kedua lebih besar daripada Nilai Maximum)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }elseif($request->value_from > $request->value_to){ //IF RANGE FROM MORE THAN RANGE TO
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Data Crips Tidak Berhasil (Angka Range Kedua lebih besar daripada Angka Range Pertama) ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range Kedua lebih besar daripada Angka Range Pertama)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //IF CRIPS SCORE SIMILAR
        $loop_crips = Crips::where('id_criteria', $request->id_criteria)->get();
        foreach($loop_crips as $crips){
            if($request->score == $crips->score){ //IF CRIPS SIMILAR WITH REGISTERED
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Data Crips Tidak Berhasil (Nilai Crips tidak boleh sama) ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tambah Data Crips Tidak Berhasil (Nilai Crips tidak boleh sama)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }elseif(($request->value_from <= $crips->value_from) && ($crips->value_type == 'Less')){ //IF INPUT RANGE FROM LESS THAN REGISTERED RANGE FROM
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Data Crips Tidak Berhasil (Angka Range yang diisi berada di Range (Kurang Dari) yang telah terdaftar) ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tambah Data Crips Tidak Berhasil (Angka Range yang diisi berada di Range (Kurang Dari) yang telah terdaftar)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }elseif(($request->value_from >= $crips->value_from) && ($request->value_from < $crips->value_to) && ($crips->value_type == 'Between')){ //IF INPUT RANGE FROM INSIDE REGISTERED RANGE (FROM AND TO)
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Data Crips Tidak Berhasil (Angka Range Pertama yang diisi berada di Range (Antara) yang telah terdaftar) ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tambah Data Crips Tidak Berhasil (Angka Range Pertama yang diisi berada di Range (Antara) yang telah terdaftar)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }elseif(($request->value_to >= $crips->value_from) && ($request->value_to < $crips->value_to) && ($crips->value_type == 'Between')){ //IF INPUT RANGE TO INSIDE REGISTERED RANGE (FROM AND TO)
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Data Crips Tidak Berhasil (Angka Range Kedua yang diisi berada di Range (Antara) yang telah terdaftar) ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tambah Data Crips Tidak Berhasil (Angka Range Kedua yang diisi berada di Range (Antara) yang telah terdaftar)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }elseif(($request->value_from >= $crips->value_from) && ($request->value_from <= $crips->criteria->max) && ($crips->value_type == 'More')){ //IF INPUT RANGE FROM INSIDE REGISTERED LAST RANGE
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Data Crips Tidak Berhasil (Angka Range yang diisi berada di Range (Lebih Dari) yang telah terdaftar) ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tambah Data Crips Tidak Berhasil (Angka Range yang diisi berada di Range (Lebih Dari) yang telah terdaftar)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //STORE DATA
        Crips::insert([
            'id_crips'=>$id_crips,
            'id_criteria'=>$request->id_criteria,
            'name'=>$request->name,
            'value_type'=>$request->value_type,
            'value_from'=>$request->value_from,
            'value_to'=>$request->value_to,
            'score'=>$request->score,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Data Crips',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Data Crips Berhasil ('.$request->name.') ('.$criteria->name.')',
        ]);

        //RETURN TO VIEW
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Scoring'){ //PROGRESS: SCORING
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('success','Tambah Data Crips Berhasil. Jika diperlukan, silahkan lakukan Import ulang')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')
            ->with('success','Tambah Data Crips Berhasil')
            ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
            ->with('modal_redirect', 'modal-crp-view')
            ->with('id_redirect', $request->id_criteria)
            ->with('code_alert', 2);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Crips $crip)
    {
        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        $criteria = Criteria::where('id_criteria', $crip->id_criteria)->first();

        //TAB FOR RETURN
        $tab = Category::with('criteria')
        ->whereHas('criteria', function($query) use($crip){$query->where('id_criteria', $crip->id_criteria);})->latest()->first();

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Proses Verifikasi Sedang Berjalan) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //CHECK INPUT RANGE ABNORMAL
        $check_range = Criteria::where('id_criteria', $crip->id_criteria)->first();
        if($request->value_type == 'Between'){
            if($request->value_to > $check_range->max){ //IF RANGE TO EXCEED MAXIMUM SCORE
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Angka Range Kedua lebih besar daripada Nilai Maximum) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range Kedua lebih besar daripada Nilai Maximum)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }elseif($request->value_from > $request->value_to){ //IF RANGE FROM MORE THAN RANGE TO
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Angka Range Kedua lebih besar daripada Angka Range Pertama) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range Kedua lebih besar daripada Angka Range Pertama)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }elseif($request->value_type == 'More'){
            if($request->value_from > $check_range->max){ //IF RANGE FROM EXCEED MAXIMUM SCORE
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Angka Range Pertama lebih besar daripada Nilai Maximum) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range Pertama lebih besar daripada Nilai Maximum)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //IF CRIPS SCORE SIMILAR
        $loop_crips = Crips::whereNot('id_crips', $crip->id_crips)->where('id_criteria', $crip->id_criteria)->get();
        foreach($loop_crips as $crips){
            if($request->score == $crips->score){ //IF CRIPS SIMILAR WITH REGISTERED
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Nilai Crips tidak boleh sama) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Nilai Crips tidak boleh sama)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }elseif(($request->value_from <= $crips->value_from) && ($crips->value_type == 'Less')){ //IF INPUT RANGE FROM LESS THAN REGISTERED RANGE FROM
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Angka Range yang diisi berada di Range (Kurang Dari) yang telah terdaftar) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range yang diisi berada di Range (Kurang Dari) yang telah terdaftar)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }elseif(($request->value_from >= $crips->value_from) && ($request->value_from <= $crips->value_to) && ($crips->value_type == 'Between')){ //IF INPUT RANGE FROM INSIDE REGISTERED RANGE (FROM AND TO)
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Angka Range Pertama yang diisi berada di Range (Antara) yang telah terdaftar) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range Pertama yang diisi berada di Range (Antara) yang telah terdaftar)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }elseif(($request->value_to >= $crips->value_from) && ($request->value_to <= $crips->value_to) && ($crips->value_type == 'Between')){ //IF INPUT RANGE TO INSIDE REGISTERED RANGE (FROM AND TO)
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Angka Range Kedua yang diisi berada di Range (Antara) yang telah terdaftar) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range Kedua yang diisi berada di Range (Antara) yang telah terdaftar)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }elseif(($request->value_from >= $crips->value_from) && ($request->value_from <= $crips->criteria->max) && ($crips->value_type == 'More')){ //IF INPUT RANGE FROM INSIDE REGISTERED LAST RANGE
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Data Crips Tidak Berhasil (Angka Range yang diisi berada di Range (Lebih Dari) yang telah terdaftar) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Angka Range yang diisi berada di Range (Lebih Dari) yang telah terdaftar)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //UPDATE DATA
        $crip->update([
            'name'=>$request->name,
            'value_type'=>$request->value_type,
            'value_from'=>$request->value_from,
            'value_to'=>$request->value_to,
            'score'=>$request->score,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Data Crips',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Data Crips Berhasil ('.$crip->name.') ('.$criteria->name.')',
        ]);

        //RETURN TO VIEW
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Scoring'){ //PROGRESS: SCORING
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('success','Ubah Data Crips Berhasil. Jika diperlukan, silahkan lakukan Import ulang')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')
            ->with('success','Ubah Data Crips Berhasil')
            ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
            ->with('modal_redirect', 'modal-crp-view')
            ->with('id_redirect', $crip->id_criteria)
            ->with('code_alert', 2);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crips $crip)
    {
        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        $criteria = Criteria::where('id_criteria', $crip->id_criteria)->first();

        //TAB FOR RETURN
        $tab = Category::with('criteria')
        ->whereHas('criteria', function($query) use($crip){$query->where('id_criteria', $crip->id_criteria);})->latest()->first();

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Data Crips',
                    'progress'=>'Delete',
                    'result'=>'Error',
                    'descriptions'=>'Hapus Data Crips Tidak Berhasil (Proses Verifikasi Sedang Berjalan) ('.$crip->name.') ('.$criteria->name.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Hapus Data Crips Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Data Crips',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Data Crips Berhasil ('.$crip->name.') ('.$criteria->name.')',
        ]);

        //DESTROY DATA
        $crip->delete();

        //RETURN TO VIEW
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Scoring'){ //PROGRESS: SCORING
                return redirect()
                ->route('admin.masters.criterias.index')->with('success','Hapus Data Crips Berhasil. Jika diperlukan, silahkan lakukan Import ulang')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')->with('success','Hapus Data Crips Berhasil')
            ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
            ->with('modal_redirect', 'modal-crp-view')
            ->with('id_redirect', $crip->id_criteria)
            ->with('code_alert', 2);
        }
    }
}
