<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $total_id = Department::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_department = "DPT-".$str_id;

        //VALIDATE DATA
        /*
        $request->validate([
            'name' => 'unique:departments',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => 'unique:departments',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('masters.officers.index')->withErrors($validator)->with('modal_redirect', 'modal-dep-create');
        }

        //STORE DATA
        Department::insert([
            'id_department'=>$id_department,
            'name'=>$request->name,
            'description'=>$request->description,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Tambah Jabatan Berhasil')->with('modal_redirect', 'modal-dep-view');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //VALIDATE DATA
        /*
        $request->validate([
            'name' => [Rule::unique('departments')->ignore($department),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('departments')->ignore($department),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('masters.officers.index')->withErrors($validator)->with('modal_redirect', 'modal-dep-update')->with('id_redirect', $department->id_department);
        }

        //UPDATE DATA
        $department->update([
            'name'=>$request->name,
            'description'=>$request->description,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Ubah Jabatan Berhasil')->with('modal_redirect',  'modal-dep-view');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //CHECK DATA
        if(Officer::where('id_department', $department->id_department)->exists()) {
            return redirect()->route('masters.officers.index')->with('fail', 'Hapus Jabatan Tidak Berhasil (Terhubung dengan tabel Pegawai)')->with('modal_redirect',  'modal-dep-view');
        }else{
            //CLEAR
        }

        //DESTROY DATA
        $department->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Hapus Jabatan Berhasil')->with('modal_redirect',  'modal-dep-view');
    }
}
