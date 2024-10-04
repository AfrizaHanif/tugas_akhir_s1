<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Crips;
use App\Models\Criteria;
use App\Models\Period;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;

class CripsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();

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
            if($latest_per->progress_status == 'Verifying'){
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tambah Data Crips Tidak Berhasil (Proses Verifikasi sedang berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $request->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //IF CRIPS SCORE SIMILAR
        $loop_crips = Crips::whereNot('id_crips', $id_crips)->where('id_criteria', $request->id_criteria)->get();
        foreach($loop_crips as $crips){
            if($request->score == $crips->score){
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tambah Data Crips Tidak Berhasil (Nilai Crips tidak boleh sama)')
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

        //RETURN TO VIEW
        if($latest_per->progress_status == 'Scoring'){
            return redirect()
            ->route('admin.masters.criterias.index')
            ->with('success','Tambah Data Crips Berhasil. Jika diperlukan, silahkan lakukan Import ulang')
            ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
            ->with('modal_redirect', 'modal-crp-view')
            ->with('id_redirect', $request->id_criteria)
            ->with('code_alert', 2);
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

        //TAB FOR RETURN
        $tab = Category::with('criteria')
        ->whereHas('criteria', function($query) use($crip){$query->where('id_criteria', $crip->id_criteria);})->latest()->first();

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Proses Verifikasi sedang berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //IF CRIPS SCORE SIMILAR
        $loop_crips = Crips::whereNot('id_crips', $crip->id_crips)->where('id_criteria', $crip->id_criteria)->get();
        foreach($loop_crips as $crips){
            if($request->score == $crips->score){
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Ubah Data Crips Tidak Berhasil (Nilai Crips tidak boleh sama)')
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

        //RETURN TO VIEW
        if($latest_per->progress_status == 'Scoring'){
            return redirect()
            ->route('admin.masters.criterias.index')
            ->with('success','Ubah Data Crips Berhasil. Jika diperlukan, silahkan lakukan Import ulang')
            ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
            ->with('modal_redirect', 'modal-crp-view')
            ->with('id_redirect', $crip->id_criteria)
            ->with('code_alert', 2);
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

        //TAB FOR RETURN
        $tab = Category::with('criteria')
        ->whereHas('criteria', function($query) use($crip){$query->where('id_criteria', $crip->id_criteria);})->latest()->first();

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){
                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Hapus Data Crips Tidak Berhasil (Proses Verifikasi sedang berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
                ->with('modal_redirect', 'modal-crp-view')
                ->with('id_redirect', $crip->id_criteria)
                ->with('code_alert', 2);
            }
        }

        //DESTROY DATA
        $crip->delete();

        //RETURN TO VIEW
        if($latest_per->progress_status == 'Scoring'){
            return redirect()
            ->route('admin.masters.criterias.index')->with('success','Hapus Data Crips Berhasil. Jika diperlukan, silahkan lakukan Import ulang')
            ->withInput(['tab_redirect'=>'pills-'.$tab->id_category])
            ->with('modal_redirect', 'modal-crp-view')
            ->with('id_redirect', $crip->id_criteria)
            ->with('code_alert', 2);
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
