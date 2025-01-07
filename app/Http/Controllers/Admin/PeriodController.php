<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\HistoryResult;
use App\Models\Input;
use App\Models\Log;
use App\Models\Employee;
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
use Illuminate\Support\Facades\Auth;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('progress_status', ['Skipped', 'Pending', 'Finished'])->latest()->first(); //GET CURRENT PERIOD
        $periods = Period::orderBy('year', 'ASC')->orderBy('num_month', 'ASC')->get(); //GET ALL PERIODS
        return view('Pages.Admin.period', compact('latest_per', 'periods'));
    }

    public function store(Request $request)
    {
        //COMBINE KODE
        $str_month = str_pad($request->month, 2, '0', STR_PAD_LEFT); //ADD 0 IN MONTH
        $str_year = substr($request->year, -2); //REMOVE 2 CHARACTER FROM START IN YEAR
        $id_period = "PRD-".$str_month.'-'.$str_year; //COMBINE MONTH AND YEAR

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
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Periode',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Periode Tidak Berhasil (Nama '.$name_month.' '.$request->year.' Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route('admin.masters.periods.index')
            ->withErrors($validator)
            ->withInput()
            ->with('modal_redirect', 'modal-per-create')
            ->with('code_alert', 2);
        }

        //STORE DATA
        Period::insert([
            'id_period'=>$id_period,
            'name'=>$name_month.' '.$request->year,
            'month'=>$name_month,
            'num_month'=>$request->month,
            'year'=>$request->year,
            'active_days'=>$request->active_days,
            'progress_status'=>'Pending',
            'import_status'=>'No Data',
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Periode',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Periode Berhasil ('.$name_month.' '.$request->year.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Tambah Periode Berhasil')->with('code_alert', 1);
    }

    public function update(Request $request, Period $period)
    {
        //UPDATE DATA
        $period->update([
            'active_days'=>$request->active_days,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Periode',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Periode Berhasil ('.$period->name.')',
        ]);

        //RETURN TO VIEW
        if($period->progress_status == 'Scoring'){
            return redirect()->route('admin.masters.periods.index')->with('success','Ubah Periode Berhasil. Jika diperlukan, silahkan lakukan import ulang')->with('code_alert', 1);
        }else{
            return redirect()->route('admin.masters.periods.index')->with('success','Ubah Periode Berhasil')->with('code_alert', 1);
        }
    }

    public function destroy(Period $period)
    {
        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Periode',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Periode Berhasil ('.$period->name.')',
        ]);

        //DELETE DATA
        $period->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Hapus Periode Berhasil')->with('code_alert', 1);
    }

    /*
    public function refresh()
    {
        Artisan::call('app:create-period');
        return redirect()->route('admin.masters.periods.index')->with('success', 'Refresh Periode Berhasil. Jika tidak ada perubahan, maka hal ini normal. Baca Bantuan untuk mengetahui mengenai Refresh Periode.')->with('code_alert', 1);
    }
    */

    public function start($period)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $period)->first(); //GET CURRENT PERIOD

        //CHECK RUNNING
        $count = Period::where('progress_status', 'Scoring')->count();
        if($count != 0){
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Periode',
                'progress'=>'Other',
                'result'=>'Error',
                'descriptions'=>'Mulai Periode Tidak Berhasil (Sedang Berjalan)',
            ]);

            //RETURN TO VIEW
            return redirect()->route('admin.masters.periods.index')->with('fail','Tidak dapat memulai proses pada periode ini karena proses Penentuan Karyawan Terbaik sedang berjalan.')->with('code_alert', 1);
        }

        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'progress_status'=>'Scoring',
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Periode',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Proses Dimulai ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.data.index')->withInput(['tab_redirect'=>'pills-'.$period])->with('success','Proses Penentuan Karyawan Terbaik Dimulai')->with('code_alert', 1);
    }

    public function stop($period)
    {
        //GET DATA
        $latest_per = Period::where('id_period', $period)->first(); //GET CURRENT PERIOD

        //DELETE DATA
        Input::where('id_period', $latest_per->id_period)->delete();

        //UPDATE DATA
        Period::where('id_period', $period)->update([
            'progress_status'=>'Pending',
            'import_status'=>'No Data',
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Periode',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Proses Dihentikan ('.$latest_per->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.periods.index')->with('success','Proses Penentuan Karyawan Terbaik Telah Berhenti. Anda dapat melakukan proses kembali dengan cara klik mulai')->with('code_alert', 1);
    }
}
