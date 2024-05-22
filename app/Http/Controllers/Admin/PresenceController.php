<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Criteria;
use App\Models\HistoryPerformance;
use App\Models\HistoryPresence;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
use App\Models\SubCriteria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //GET DATA
        $officers = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){
            $query->where('name', 'Developer');
        })
        ->whereDoesntHave('user', function($query){
            $query->whereIn('part', ['KBU', 'KTT', 'KBPS']);
        })
        ->get();
        $leaders = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){
            $query->where('name', 'Developer');
        })
        ->whereHas('user', function($query){
            $query->whereIn('part', ['KBU', 'KTT']);
        })
        ->get();
        $performances = Performance::get();
        $presences = Presence::get();
        $status = Presence::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $periods = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending'])->get();
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending', 'Finished'])->latest()->first();
        $history_per = Period::orderBy('id_period', 'ASC')->whereIn('status', ['Voting', 'Finished'])->get();
        $criterias = Criteria::with('subcriteria')->get();
        $allsubcriterias = SubCriteria::with('criteria')->get();
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
        $historyprs = HistoryPresence::get();
        $historyprf = HistoryPerformance::get();

        return view('Pages.Admin.input', compact('officers', 'leaders', 'performances', 'presences', 'periods', 'latest_per', 'history_per', 'criterias', 'allsubcriterias', 'subcriterias', 'countsub', 'countprs', 'countprf', 'subcritprs', 'subcritprf', 'status', 'historyprs', 'historyprf'));
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
            Presence::insert([
                'id_presence'=>$id_presence,
                'id_period'=>$request->id_period,
                'id_officer'=>$request->id_officer,
                'id_sub_criteria'=>$subcriteria->id_sub_criteria,
                'input'=>$request->input($subcriteria->id_sub_criteria),
                'status'=>'Pending',
            ]);
        }

        //RETURN TO VIEW
        $test = User::where('id_officer', $request->id_officer)->first();
        if(!empty($test) && $test->part != 'Pegawai'){
            return redirect()->route('admin.inputs.presences.leaders.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Tambah Data Kehadiran Berhasil')->with('code_alert', 1);
        }else{
            return redirect()->route('admin.inputs.presences.officers.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Tambah Data Kehadiran Berhasil')->with('code_alert', 1);
        }
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
            if(Presence::where('id_presence', $id_presence)->exists()){
                Presence::where('id_presence', $id_presence)->update([
                    'input'=>$request->input($subcriteria->id_sub_criteria),
                    'status'=>'Pending',
                ]);
                Score::where('id_period', $request->id_period)->where('id_officer', $request->id_officer)->update([
                    'status'=>'Revised',
                ]);
            }else{
                Presence::insert([
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
        $test = User::where('id_officer', $request->id_officer)->first();
        if(!empty($test) && $test->part != 'Pegawai'){
            return redirect()->route('admin.inputs.presences.leaders.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Ubah Data Kehadiran Berhasil')->with('code_alert', 1);
        }else{
            return redirect()->route('admin.inputs.presences.officers.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Ubah Data Kehadiran Berhasil')->with('code_alert', 1);
        }
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
            Presence::where('id_presence', $id_presence)->delete();
        }

        //RETURN TO VIEW
        $test = User::where('id_officer', $request->id_officer)->first();
        if(!empty($test) && $test->part != 'Pegawai'){
            return redirect()->route('admin.inputs.presences.leaders.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Hapus Data Kehadiran Berhasil')->with('code_alert', 1);
        }else{
            return redirect()->route('admin.inputs.presences.officers.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Hapus Data Kehadiran Berhasil')->with('code_alert', 1);
        }
    }
}
