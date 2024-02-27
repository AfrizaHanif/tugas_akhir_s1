<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parts = Part::get();
        $departments = Department::whereNot('name', 'Developer')->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();
        return view('Pages.Admin.officer', compact('parts', 'departments', 'officers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $total_id = Officer::
        whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_officer = "OFF-".$str_id;

        //VALIDATE DATA
        $request->validate([
            //'nip_bps' => 'unique:officers',
            //'nip' => 'unique:officers',
            'name' => 'unique:officers',
        ], [
            //'nip_bps.unique' => 'NIP BPS tidak boleh sama dengan yang terdaftar',
            //'nip.unique' => 'NIP tidak boleh sama dengan yang terdaftar',
            'name.unique' => 'Nama telah terdaftar',
        ]);

        //UPLOAD PHOTO
        $photo = '';
        if($request->photo){
            $photo = 'IMG-'.$request->nip_bps.'.'.$request->photo->extension();
            $request->photo->move(public_path('Images/'), $photo);
        }else{
            //SKIP
        }

        //STORE DATA
        Officer::insert([
            'id_officer'=>$id_officer,
            //'nip_bps'=>$request->nip_bps,
            //'nip'=>$request->nip,
            'name'=>$request->name,
            //'org_code'=>$request->org_code,
            'id_department'=>$request->id_department,
            'id_part'=>$request->id_part,
            //'status'=>$request->status,
            //'last_group'=>$request->last_group,
            //'last_education'=>$request->last_education,
            'place_birth'=>$request->place_birth,
            'date_birth'=>$request->date_birth,
            'gender'=>$request->gender,
            'religion'=>$request->religion,
            'photo'=>$photo,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Tambah Pegawai Berhasil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Officer $officer)
    {
        //VALIDATE DATA
        $request->validate([
            //'nip_bps' => [Rule::unique('officers')->ignore($officer),],
            //'nip' => [Rule::unique('officers')->ignore($officer),],
            'name' => [Rule::unique('officers')->ignore($officer),]
        ], [
            //'nip_bps.unique' => 'NIP BPS tidak boleh sama dengan yang terdaftar',
            //'nip.unique' => 'NIP tidak boleh sama dengan yang terdaftar',
            'name.unique' => 'Nama telah terdaftar',
        ]);

        //UPDATE DATA
        $officer->update([
            //'nip_bps'=>$request->nip_bps,
            //'nip'=>$request->nip,
            'name'=>$request->name,
            //'org_code'=>$request->org_code,
            'id_department'=>$request->id_department,
            'id_part'=>$request->id_part,
            //'status'=>$request->status,
            //'last_group'=>$request->last_group,
            //'last_education'=>$request->last_education,
            'place_birth'=>$request->place_birth,
            'date_birth'=>$request->date_birth,
            'gender'=>$request->gender,
            'religion'=>$request->religion,
		]);

        //UPDATE IMAGE
        $photo = '';
        $id_officer = Officer::find($officer->id_officer);
        $path_photo = public_path('Images/'.$id_officer->photo);
        if($request->hasFile('photo')){
            File::delete($path_photo);
            $photo = 'IMG-'.$request->nip_bps.'.'.$request->photo->extension();
            $request->photo->move(public_path('Images/'), $photo);
            $officer->update([
                'photo'=>$request['image'] = $photo,
            ]);
        }

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Ubah Pegawai Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Officer $officer)
    {
        //CHECK DATA
        if(Presence::where('id_officer', $officer->id_officer)->exists()) {
            return redirect()->route('masters.officers.index')->with('fail', 'Hapus Pegawai Tidak Berhasil (Terhubung dengan tabel Kehadiran)');
        }elseif(Performance::where('id_officer', $officer->id_officer)->exists()){
            return redirect()->route('masters.officers.index')->with('fail', 'Hapus Pegawai Tidak Berhasil (Terhubung dengan tabel Prestasi Kerja)');
        }elseif(User::where('id_officer', $officer->id_officer)->exists()){
            return redirect()->route('masters.officers.index')->with('fail', 'Hapus Pegawai Tidak Berhasil (Terhubung dengan tabel Pengguna / Akun)');
        }else{
            //CLEAR
        }

        //DESTROY IMAGE
        $id_officer = Officer::find($officer->id_officer);
        $path_photo = public_path('Images/'.$id_officer->photo);
        if(File::exists($path_photo)){
            File::delete($path_photo);
        }

        //DESTROY DATA
        $officer->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.officers.index')->with('success','Hapus Pegawai Berhasil');
    }
}
