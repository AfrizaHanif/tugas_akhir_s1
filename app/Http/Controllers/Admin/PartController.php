<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $total_id = Part::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_part = "PRT-".$str_id;

        //VALIDATE DATA
        $request->validate([
            'name' => 'unique:criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        //STORE DATA
        Part::insert([
            'id_part'=>$id_part,
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Tambah Bagian Berhasil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Part $part)
    {
        //VALIDATE DATA
        $request->validate([
            'name' => [Rule::unique('parts')->ignore($part),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        //UPDATE DATA
        $part->update([
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Ubah Bagian Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part)
    {
        //CHECK DATA
        if(Officer::where('id_part', $part->id_part)->exists()) {
            return redirect()->route('masters.officers.index')->with('fail', 'Hapus Bagian Tidak Berhasil (Terhubung dengan tabel Pegawai)');
        }else{
            //CLEAR
        }

        //DESTROY DATA
        $part->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Hapus Bagian Berhasil');
    }
}
