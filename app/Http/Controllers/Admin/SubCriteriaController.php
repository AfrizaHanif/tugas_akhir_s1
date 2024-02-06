<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
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
        $request->validate([
            'name' => 'unique:sub_criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

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
        return redirect()->route('masters.criterias.index')->with('success','Tambah Sub Kriteria Berhasil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCriteria $subcriteria)
    {
        //VALIDATE DATA
        $request->validate([
            'name' => [Rule::unique('sub_criterias')->ignore($subcriteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        //STORE DATA
        $subcriteria->update([
            'name'=>$request->name,
            'weight'=>$request->weight,
            'attribute'=>$request->attribute,
            'level'=>$request->level,
            'need'=>$request->need,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.criterias.index')->with('success','Ubah Sub Kriteria Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCriteria $subcriteria)
    {
        //DESTROY DATA
        $subcriteria->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.criterias.index')->with('success','Hapus Sub Kriteria Berhasil');
    }
}
