<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Log;
use App\Models\Period;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        $criterias = Criteria::get();
        $settings = Setting::get();
        if(Auth::user()->part == "Dev"){
            return view('Pages.Developer.setting', compact('criterias', 'settings'));
        }elseif(Auth::user()->part != "Pegawai"){
            return view('Pages.Admin.setting', compact('criterias', 'settings'));
        }else{
            return view('Pages.Officer.setting', compact('criterias', 'settings'));
        }
    }

    public function update(Request $request) //MANUAL UNTUK DEVELOPER KARENA BERSIFAT UNIVERSAL
    {
        //VALIDATE USERNAME
        if($request->filled('username')){
            $validator = Validator::make($request->all(), [
                'username' => [Rule::unique('users')->ignore(Auth::user()),'regex:/^\S*$/u'],
            ], [
                'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
                'username.regex' => 'Username tidak boleh mengandung spasi',
            ]);
            if ($validator->fails()) {
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Setting',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Username Tidak Berhasil',
                ]);

                if(Auth::user()->part == "Dev"){
                    return redirect()->route('developer.settings.index')
                    ->withErrors($validator)
                    ->with('code_alert', 1);
                }elseif(Auth::user()->part != "Pegawai"){
                    return redirect()->route('admin.settings.index')
                    ->withErrors($validator)
                    ->with('code_alert', 1);
                }else{
                    return redirect()->route('officer.settings.index')
                    ->withErrors($validator)
                    ->with('code_alert', 1);
                }
            }
        }

        if(Auth::user()->part == "Dev"){
            //CHECK STATUS
            $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
            if(!empty($latest_per)){
                if($latest_per->progress_status == 'Verifying'){
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Setting',
                        'progress'=>'Update',
                        'result'=>'Error',
                        'descriptions'=>'Ubah Setting Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                    ]);

                    if(Auth::user()->part == "Dev"){
                        return redirect()->route('developer.settings.index')->with('fail','Tidak dapat mengubah pengaturan dikarenakan sedang dalam proses verifikasi nilai.')->with('code_alert', 1);
                    }else{
                        return redirect()->route('admin.settings.index')->with('fail','Tidak dapat mengubah pengaturan dikarenakan sedang dalam proses verifikasi nilai.')->with('code_alert', 1);
                    }
                }
            }

            //PRESENCE COUNT (PERHITUNGAN KEHADIRAN)
            Setting::where('id_setting', 'STG-001')->update([
                'value'=>$request->presence_counter,
            ]);

            //SECOND SORT (SORTING KEDUA)
            Setting::where('id_setting', 'STG-002')->update([
                'value'=>$request->second_sort,
            ]);
        }

        //UPDATE USERNAME
        if($request->filled('username')) {
            User::where('id_user', Auth::user()->id_user)->update([
                'username'=>$request->username,
            ]);
        }

        //UPDATE PASSWORD
        if($request->filled('password')) {
            User::where('id_user', Auth::user()->id_user)->update([
                'password'=>Hash::make($request->password),
            ]);
        }

        //RETURN TO VIEW
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Setting',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Setting Berhasil',
        ]);

        if(Auth::user()->part == "Dev"){
            return redirect()->route('developer.settings.index')->with('success','Simpan Berhasil')->with('code_alert', 1);
        }elseif(Auth::user()->part != "Pegawai"){
            return redirect()->route('admin.settings.index')->with('success','Simpan Berhasil')->with('code_alert', 1);
        }else{
            return redirect()->route('officer.settings.index')->with('success','Simpan Berhasil')->with('code_alert', 1);
        }
    }
}
