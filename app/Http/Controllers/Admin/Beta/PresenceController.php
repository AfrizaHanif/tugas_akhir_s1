<?php

namespace App\Http\Controllers\Admin\Beta;

use App\Http\Controllers\Controller;
use App\Models\BetaPerformance;
use App\Models\BetaPresence;
use App\Models\Criteria;
use App\Models\Officer;
use App\Models\Period;
use App\Models\SubCriteria;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $officers = Officer::with('department')->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})->get();
        $performances = BetaPerformance::get();
        $presences = BetaPresence::get();
        $status = BetaPresence::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $periods = Period::orderBy('id_period', 'ASC')->get();
        $criterias = Criteria::with('subcriteria')->get();
        $subcriterias = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprs = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprf = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $countsub = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->count();
        $countprs = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->count();
        $countprf = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Prestasi Kerja');})
        ->count();

        return view('Pages.Admin.Beta.input', compact('officers', 'performances', 'presences', 'periods', 'criterias', 'subcriterias', 'countsub', 'countprs', 'countprf', 'subcritprs', 'subcritprf', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subcriterias = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        foreach($subcriterias as $subcriteria){
            //COMBINE KODE (Ex: PRS-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($subcriteria->id_sub_criteria, 4);
            $id_presence = "PRS-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //STORE DATA
            BetaPresence::insert([
                'id_presence'=>$id_presence,
                'id_period'=>$request->id_period,
                'id_officer'=>$request->id_officer,
                'id_sub_criteria'=>$subcriteria->id_sub_criteria,
                'input'=>$request->input($subcriteria->id_sub_criteria),
                'status'=>'Pending',
            ]);
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.beta.presences.index')->with('success','Tambah Data Kehadiran Berhasil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $subcriterias = SubCriteria::with('criteria')
        ->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        foreach($subcriterias as $subcriteria){
            //COMBINE KODE (Ex: PRS-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($subcriteria->id_sub_criteria, 4);
            $id_presence = "PRS-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //UPDATE DATA
            if(BetaPresence::where('id_presence', $id_presence)->exists()){
                BetaPresence::where('id_presence', $id_presence)->update([
                    'input'=>$request->input($subcriteria->id_sub_criteria),
                    'status'=>'Pending',
                ]);
            }else{
                BetaPresence::insert([
                    'id_presence'=>$id_presence,
                    'id_period'=>$request->id_period,
                    'id_officer'=>$request->id_officer,
                    'id_sub_criteria'=>$subcriteria->id_sub_criteria,
                    'input'=>$request->input($subcriteria->id_sub_criteria),
                    'status'=>'Pending',
                ]);
            }
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.beta.presences.index')->with('success','Ubah Data Kehadiran Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $subcriterias = SubCriteria::with('criteria')->WhereHas('criteria', function($query){$query->where('type', 'Kehadiran');})->get();
        foreach($subcriterias as $subcriteria){
            //COMBINE KODE (Ex: PRS-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($subcriteria->id_sub_criteria, 4);
            $id_presence = "PRS-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //DELETE DATA
            BetaPresence::where('id_presence', $id_presence)->delete();
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.beta.presences.index')->with('success','Hapus Data Kehadiran Berhasil');
    }
}
