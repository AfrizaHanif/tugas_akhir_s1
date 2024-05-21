<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
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
        /*
        $request->validate([
            'name' => 'unique:criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => 'unique:criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.masters.criterias.index')->withErrors($validator)->with('modal_redirect', 'modal-crt-create');
        }

        //STORE DATA
        Criteria::insert([
            'id_criteria'=>$id_criteria,
            'name'=>$request->name,
            'type'=>$request->type,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$id_criteria])->with('success','Tambah Kriteria Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Criteria $criteria)
    {
        //VALIDATE DATA
        /*
        $request->validate([
            'name' => [Rule::unique('criterias')->ignore($criteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('criterias')->ignore($criteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.masters.criterias.index')->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$criteria->id_criteria])->with('modal_redirect', 'modal-crt-update')->with('id_redirect', $criteria->id_criteria);
        }

        //STORE DATA
        $criteria->update([
            'name'=>$request->name,
            'type'=>$request->type,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$criteria->id_criteria])->with('success','Ubah Kriteria Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criteria $criteria)
    {
        //CHECK DATA
        if(SubCriteria::where('id_criteria', $criteria->id_criteria)->exists()) {
            return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$criteria->id_criteria])->with('fail', 'Hapus Kriteria Tidak Berhasil (Terhubung dengan tabel Sub Kriteria)');
        }else{
            //CLEAR
        }

        //DESTROY DATA
        $criteria->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->with('success','Hapus Kriteria Berhasil')->with('code_alert', 1);
    }
}
