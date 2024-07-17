<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Crips;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;

class CripsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        //TAB FOR RETURN
        $tab = Category::with('criteria')
        ->whereHas('criteria', function($query) use($request){$query->where('id_criteria', $request->id_criteria);})->latest()->first();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->with('success','Tambah Data Crips Berhasil')->withInput(['tab_redirect'=>'pills-'.$tab->id_category])->with('modal_redirect', 'modal-crp-view')->with('id_redirect', $request->id_criteria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Crips $crip)
    {
        //UPDATE DATA
        $crip->update([
            'name'=>$request->name,
            'value_type'=>$request->value_type,
            'value_from'=>$request->value_from,
            'value_to'=>$request->value_to,
            'score'=>$request->score,
		]);

        //TAB FOR RETURN
        $tab = Category::with('criteria')
        ->whereHas('criteria', function($query) use($crip){$query->where('id_criteria', $crip->id_criteria);})->latest()->first();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->with('success','Ubah Data Crips Berhasil')->withInput(['tab_redirect'=>'pills-'.$tab->id_category])->with('modal_redirect', 'modal-crp-view')->with('id_redirect', $crip->id_criteria);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crips $crip)
    {
        //TAB FOR RETURN
        $tab = Category::with('criteria')
        ->whereHas('criteria', function($query) use($crip){$query->where('id_criteria', $crip->id_criteria);})->latest()->first();

        //DESTROY DATA
        $crip->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->with('success','Hapus Data Crips Berhasil')->withInput(['tab_redirect'=>'pills-'.$tab->id_category])->with('modal_redirect', 'modal-crp-view')->with('id_redirect', $crip->id_criteria);
    }
}
