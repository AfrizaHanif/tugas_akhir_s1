<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Position;
use App\Models\Officer;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function store(Request $request)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.officers.index';
        }else{
            $redirect_route = 'developer.masters.officers.index';
        }

        //COMBINE KODE
        /*
        $total_id = Position::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_position = "DPT-".$str_id;
        */
        $id_position = IdGenerator::generate([
            'table'=>'positions',
            'field'=>'id_position',
            'length'=>7,
            'prefix'=>'DPT-',
            'reset_on_prefix_change'=>true,
        ]);

        //VALIDATE DATA
        /*
        $request->validate([
            'name' => 'unique:positions',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => 'unique:positions',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Jabatan',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Jabatan Tidak Berhasil (Nama '.$request->name.' Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()->route($redirect_route)->withErrors($validator)->with('modal_redirect', 'modal-dep-create')->with('code_alert', 3);
        }

        //STORE DATA
        Position::insert([
            'id_position'=>$id_position,
            'name'=>$request->name,
            //'description'=>$request->description,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Jabatan',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Jabatan Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route($redirect_route)->with('success','Tambah Jabatan Berhasil')->with('modal_redirect', 'modal-dep-view')->with('code_alert', 2);
    }

    public function update(Request $request, Position $position)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.officers.index';
        }else{
            $redirect_route = 'developer.masters.officers.index';
        }

        //VALIDATE DATA
        /*
        $request->validate([
            'name' => [Rule::unique('positions')->ignore($position),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('positions')->ignore($position),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Jabatan',
                'progress'=>'Update',
                'result'=>'Error',
                'descriptions'=>'Ubah Jabatan Tidak Berhasil (Nama '.$request->name.' Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()->route($redirect_route)->withErrors($validator)->with('modal_redirect', 'modal-dep-update')->with('id_redirect', $position->id_position)->with('code_alert', 3);
        }

        //UPDATE DATA
        $position->update([
            'name'=>$request->name,
            //'description'=>$request->description,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Jabatan',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Jabatan Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route($redirect_route)->with('success','Ubah Jabatan Berhasil')->with('modal_redirect', 'modal-dep-view')->with('code_alert', 2);
    }

    public function destroy(Position $position)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.officers.index';
        }else{
            $redirect_route = 'developer.masters.officers.index';
        }

        //CHECK IF EXISTS
        if(Officer::where('id_position', $position->id_position)->exists()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Jabatan',
                'progress'=>'Delete',
                'result'=>'Error',
                'descriptions'=>'Hapus Jabatan Tidak Berhasil (Jabatan '.$position->name.' Terhubung Dengan Beberapa Pegawai)',
            ]);

            //RETURN TO VIEW
            return redirect()->route($redirect_route)->with('fail', 'Hapus Jabatan Tidak Berhasil (Terhubung dengan tabel Pegawai)')->with('modal_redirect',  'modal-dep-view')->with('code_alert', 2);
        }else{
            //CLEAR
        }

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Jabatan',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Jabatan Berhasil ('.$position->name.')',
        ]);

        //DESTROY DATA
        $position->delete();

        //RETURN TO VIEW
        return redirect()->route($redirect_route)->with('success','Hapus Jabatan Berhasil')->with('modal_redirect', 'modal-dep-view')->with('code_alert', 2);
    }

    public function import()
    {

    }
}
