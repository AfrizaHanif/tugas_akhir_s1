<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criterias = Criteria::get();
        $subcriterias = SubCriteria::get();
        return view('Pages.Admin.criteria', compact('criterias', 'subcriterias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $total_id = Criteria::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_criteria = "CRT-".$str_id;

        //VALIDATE DATA
        $request->validate([
            'name' => 'unique:criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        //STORE DATA
        Criteria::insert([
            'id_criteria'=>$id_criteria,
            'name'=>$request->name,
            'type'=>$request->type,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.criterias.index')->with('success','Tambah Kriteria Berhasil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Criteria $criteria)
    {
        //VALIDATE DATA
        $request->validate([
            'name' => [Rule::unique('criterias')->ignore($criteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        //STORE DATA
        $criteria->update([
            'name'=>$request->name,
            'type'=>$request->type,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.criterias.index')->with('success','Ubah Kriteria Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criteria $criteria)
    {
        //CHECK DATA
        if(SubCriteria::where('id_criteria', $criteria->id_criteria)->exists()) {
            return redirect()->route('masters.criterias.index')->with('fail', 'Hapus Kriteria Tidak Berhasil (Terhubung dengan tabel Sub Kriteria)');
        }else{
            //CLEAR
        }

        //DESTROY DATA
        $criteria->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.criterias.index')->with('success','Hapus Kriteria Berhasil');
    }
}
