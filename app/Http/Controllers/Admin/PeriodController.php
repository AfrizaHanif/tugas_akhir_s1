<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\HistoryResult;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Result;
use App\Models\Score;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->get();
        return view('Pages.Admin.period', compact('periods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $str_month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
        $str_year = substr($request->year, -2);
        $id_period = "PRD-".$str_month.'-'.$str_year;

        //CONVERT MONTH
        $name_month = '';
        if($request->month == 1){
            $name_month = 'Januari';
        }elseif($request->month == 2){
            $name_month = 'Februari';
        }elseif($request->month == 3){
            $name_month = 'Maret';
        }elseif($request->month == 4){
            $name_month = 'April';
        }elseif($request->month == 5){
            $name_month = 'Mei';
        }elseif($request->month == 6){
            $name_month = 'Juni';
        }elseif($request->month == 7){
            $name_month = 'Juli';
        }elseif($request->month == 8){
            $name_month = 'Agustus';
        }elseif($request->month == 9){
            $name_month = 'September';
        }elseif($request->month == 10){
            $name_month = 'Oktober';
        }elseif($request->month == 11){
            $name_month = 'November';
        }elseif($request->month == 12){
            $name_month = 'Desember';
        }

        //VALIDATE DATA
        $input = [
            'id_period' => $id_period,
            'name' => $name_month.' '.$request->year,
        ];

        $validator = Validator::make($input, [
            'id_period' => 'unique:periods',
            'name' => 'unique:periods',
        ], [
            'id_period.unique' => 'ID telah terdaftar sebelumnya',
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.masters.periods.index')->withErrors($validator)->with('modal_redirect', 'modal-per-create');
        }

        //STORE DATA
        Period::insert([
            'id_period'=>$id_period,
            'name'=>$name_month.' '.$request->year,
            'month'=>$name_month,
            'year'=>$request->year,
            'status'=>'Pending',
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Tambah Periode Berhasil')->with('code_alert', 1);
    }

    public function refresh()
    {
        Artisan::call('app:create-period');
        return redirect()->route('admin.masters.periods.index')->with('success', 'Refresh Periode Berhasil. Jika tidak ada perubahan, maka hal ini normal. Baca Bantuan untuk mengetahui mengenai Refresh Periode.')->with('code_alert', 1);
    }

    public function start($period)
    {
        //CHECK RUNNING
        $count = Period::whereIn('status', ['Scoring', 'Voting'])->count();
        if($count != 0){
            return redirect()->route('admin.masters.periods.index')->with('fail','Tidak dapat memulai proses pada periode ini karena proses Pemilihan Karyawan Terbaik sedang berjalan.')->with('code_alert', 1);
        }

        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Scoring',
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Proses Pemilihan Karyawan Terbaik Dimulai')->with('code_alert', 1);
    }

    public function skip($period)
    {
        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Skipped',
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Proses Lewat Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period)
    {
        //DELETE DATA
        $period->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Hapus Periode Berhasil')->with('code_alert', 1);
    }
}
