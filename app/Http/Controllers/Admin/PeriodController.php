<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;

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

        //STORE DATA
        Period::insert([
            'id_period'=>$id_period,
            'name'=>$name_month.' '.$request->year,
            'month'=>$name_month,
            'year'=>$request->year,
            'status'=>'Pending',
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.periods.index')->with('success','Tambah Periode Berhasil');
    }

    public function skip($period)
    {
        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'status'=>'Skipped',
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.periods.index')->with('success','Proses Lewat Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period)
    {
        //DELETE DATA
        $period->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.periods.index')->with('success','Hapus Periode Berhasil');
    }
}
