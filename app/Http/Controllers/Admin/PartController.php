<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Part;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        /*
        $total_id = Part::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_part = "PRT-".$str_id;
        */
        $id_part = IdGenerator::generate([
            'table'=>'parts',
            'field'=>'id_part',
            'length'=> 7,
            'prefix'=>'PRT-',
            'reset_on_prefix_change' => true,
        ]);

        //VALIDATE DATA
        /*
        $request->validate([
            'name' => 'unique:parts',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => 'unique:parts',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.masters.officers.index')->withErrors($validator)->with('modal_redirect', 'modal-prt-create');
        }

        //STORE DATA
        Part::insert([
            'id_part'=>$id_part,
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->withInput(['tab_redirect'=>'pills-'.$id_part])->with('success','Tambah Bagian Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Part $part)
    {
        //VALIDATE DATA
        /*
        $request->validate([
            'name' => [Rule::unique('parts')->ignore($part),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('parts')->ignore($part),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.masters.officers.index')->withErrors($validator)->with('modal_redirect', 'modal-prt-update')->with('id_redirect', $part->id_part);
        }

        //UPDATE DATA
        $part->update([
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->withInput(['tab_redirect'=>'pills-'.$part->id_part])->with('success','Ubah Bagian Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part)
    {
        //CHECK DATA
        if(Officer::where('id_part', $part->id_part)->exists()) {
            return redirect()->route('admin.masters.officers.index')->with('fail', 'Hapus Bagian Tidak Berhasil (Terhubung dengan tabel Pegawai)');
        }else{
            //CLEAR
        }

        //DESTROY DATA
        $part->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Hapus Bagian Berhasil')->with('code_alert', 1);
    }
}
