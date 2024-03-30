<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubCriteriaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $total_id = SubCriteria::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_sub_criteria = "SUB-".$str_id;

        //VALIDATE DATA
        /*
        $request->validate([
            'name' => 'unique:sub_criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => 'unique:sub_criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.masters.criterias.index')->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$request->id_criteria])->with('modal_redirect', 'modal-sub-create');
        }

        //STORE DATA
        SubCriteria::insert([
            'id_sub_criteria'=>$id_sub_criteria,
            'id_criteria'=>$request->id_criteria,
            'name'=>$request->name,
            'weight'=>$request->weight,
            'attribute'=>$request->attribute,
            'level'=>$request->level,
            'need'=>$request->need,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$request->id_criteria])->with('success','Tambah Sub Kriteria Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCriteria $subcriteria)
    {
        //VALIDATE DATA
        /*
        $request->validate([
            'name' => [Rule::unique('sub_criterias')->ignore($subcriteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('sub_criterias')->ignore($subcriteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.masters.criterias.index')->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$subcriteria->id_criteria])->with('modal_redirect', 'modal-sub-update')->with('id_redirect', $subcriteria->id_sub_criteria);
        }

        //STORE DATA
        $subcriteria->update([
            'name'=>$request->name,
            'weight'=>$request->weight,
            'attribute'=>$request->attribute,
            'level'=>$request->level,
            'need'=>$request->need,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$subcriteria->id_criteria])->with('success','Ubah Sub Kriteria Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCriteria $subcriteria)
    {
        //DESTROY DATA
        $subcriteria->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$subcriteria->id_criteria])->with('success','Hapus Sub Kriteria Berhasil')->with('code_alert', 1);
    }
}
