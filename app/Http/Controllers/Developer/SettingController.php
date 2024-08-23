<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $criterias = Criteria::get();
        $settings = Setting::get();
        return view('Pages.Developer.setting', compact('criterias', 'settings'));
    }

    public function update(Request $request) //MANUAL UNTUK DEVELOPER KARENA BERSIFAT UNIVERSAL
    {
        //PRESENCE COUNT (PERHITUNGAN KEHADIRAN)
        Setting::where('id_setting', 'STG-001')->update([
            'value'=>$request->presence_counter,
        ]);

        //SECOND SORT (SORTING KEDUA)
        Setting::where('id_setting', 'STG-002')->update([
            'value'=>$request->second_sort,
        ]);

        //RETURN TO VIEW
        return redirect()->route('developer.settings.index')->with('success','Simpan Berhasil')->with('code_alert', 1);
    }
}
