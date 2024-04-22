<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Criteria;
use App\Models\Officer;
use App\Models\Period;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->part == 'KBU'){
            $officers = Officer::with('department', 'part')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%Bagian Umum%');})
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
        }elseif(Auth::user()->part == 'KTT'){
            $officers = Officer::with('department', 'part')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
        }else{
            $officers = Officer::with('department', 'part')
            ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
            ->whereDoesntHave('part', function($query){$query->where('name', 'Kepemimpinan')->orWhere('name', 'Kepegawaian');})
            ->get();
        }
        $performances = Performance::get();
        $presences = Presence::get();
        $status = Performance::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $periods = Period::orderBy('id_period', 'ASC')->whereNot('status', 'Skipped')->whereNot('status', 'Pending')->get();
        $criterias = Criteria::with('subcriteria')->get();
        $allsubcriterias = SubCriteria::with('criteria')->get();
        $subcriterias = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $subcritprs = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprf = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $countsub = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->count();
        $countprs = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->count();
        $countprf = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->count();

        return view('Pages.Admin.input', compact('officers', 'performances', 'presences', 'periods', 'criterias', 'allsubcriterias', 'subcriterias', 'countsub', 'countprs', 'countprf', 'subcritprs', 'subcritprf', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subcriterias = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        foreach($subcriterias as $subcriteria){
            //COMBINE KODE (Ex: PRF-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($subcriteria->id_sub_criteria, 4);
            $id_performance = "PRF-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //STORE DATA
            Performance::insert([
                'id_performance'=>$id_performance,
                'id_period'=>$request->id_period,
                'id_officer'=>$request->id_officer,
                'id_sub_criteria'=>$subcriteria->id_sub_criteria,
                'input'=>$request->input($subcriteria->id_sub_criteria),
                'status'=>'Pending',
            ]);
        }

        //RETURN TO VIEW
        $lowerpart = strtolower(Auth::user()->part);
        return redirect()->route('admin.inputs.'.$lowerpart.'.performances.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Tambah Data Prestasi Kerja Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $subcriterias = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        foreach($subcriterias as $subcriteria){
            //COMBINE KODE (Ex: PRF-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($subcriteria->id_sub_criteria, 4);
            $id_performance = "PRF-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //UPDATE DATA
            if(Performance::where('id_performance', $id_performance)->exists()){
                Performance::where('id_performance', $id_performance)->update([
                    'input'=>$request->input($subcriteria->id_sub_criteria),
                    'status'=>'Pending',
                ]);
            }else{
                Performance::insert([
                    'id_performance'=>$id_performance,
                    'id_period'=>$request->id_period,
                    'id_officer'=>$request->id_officer,
                    'id_sub_criteria'=>$subcriteria->id_sub_criteria,
                    'input'=>$request->input($subcriteria->id_sub_criteria),
                    'status'=>'Pending',
                ]);
            }
        }

        //RETURN TO VIEW
        $lowerpart = strtolower(Auth::user()->part);
        return redirect()->route('admin.inputs.'.$lowerpart.'.performances.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Ubah Data Prestasi Kerja Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $subcriterias = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        foreach($subcriterias as $subcriteria){
            //COMBINE KODE (Ex: PRS-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($subcriteria->id_sub_criteria, 4);
            $id_performance = "PRF-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //DELETE DATA
            Performance::where('id_performance', $id_performance)->delete();
        }

        //RETURN TO VIEW
        $lowerpart = strtolower(Auth::user()->part);
        return redirect()->route('admin.inputs.'.$lowerpart.'.performances.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Hapus Data Prestasi Kerja Berhasil')->with('code_alert', 1);
    }
}
