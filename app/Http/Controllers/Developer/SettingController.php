<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Period;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $criterias = Criteria::get();
        $settings = Setting::get();
        if(Auth::user()->part == "Dev"){
            return view('Pages.Developer.setting', compact('criterias', 'settings'));
        }else{
            return view('Pages.Admin.setting', compact('criterias', 'settings'));
        }
    }

    public function update(Request $request) //MANUAL UNTUK DEVELOPER KARENA BERSIFAT UNIVERSAL
    {
        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Validating')->latest()->first();
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Validating'){
                if(Auth::user()->part == "Dev"){
                    return redirect()->route('developer.settings.index')->with('fail','Tidak dapat mengubah pengaturan dikarenakan sedang dalam proses validasi nilai.')->with('code_alert', 1);
                }else{
                    return redirect()->route('admin.settings.index')->with('fail','Tidak dapat mengubah pengaturan dikarenakan sedang dalam proses validasi nilai.')->with('code_alert', 1);
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

        //RETURN TO VIEW
        if(Auth::user()->part == "Dev"){
            return redirect()->route('developer.settings.index')->with('success','Simpan Berhasil')->with('code_alert', 1);
        }else{
            return redirect()->route('admin.settings.index')->with('success','Simpan Berhasil')->with('code_alert', 1);
        }
    }
}
