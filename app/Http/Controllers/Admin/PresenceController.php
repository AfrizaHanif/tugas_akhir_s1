<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Criteria;
use App\Models\HistoryPerformance;
use App\Models\HistoryPresence;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Score;
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
            $query->whereIn('part', ['KBPS']);
        })
        ->get();
        /*
        $leaders = Officer::with('department', 'user')
        ->whereDoesntHave('department', function($query){
            $query->where('name', 'Developer');
        })
        ->whereHas('user', function($query){
            $query->whereIn('part', ['KBU', 'KTT']);
        })
        ->get();
        */
        $performances = Performance::get();
        $presences = Presence::get();
        $status = Presence::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $periods = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending'])->get();
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending', 'Finished'])->latest()->first();
        $history_per = HistoryPresence::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->get();
        $criterias = Category::with('criteria')->get();
        $allsubcriterias = Criteria::with('category')->get();
        $subcriterias = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprs = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprf = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $countsub = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})
        ->count();
        $countprs = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})
        ->count();
        $countprf = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->count();
        $historyprs = HistoryPresence::get();
        $historyprf = HistoryPerformance::get();
        $hofficer = HistoryPresence::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->get();
        $hcriteria = HistoryPresence::select('id_criteria', 'criteria_name')->groupBy('id_criteria', 'criteria_name')->union(HistoryPerformance::select('id_criteria', 'criteria_name')->groupBy('id_criteria', 'criteria_name'))->get();
        $hallsub = HistoryPresence::select('id_category', 'category_name', 'id_criteria', 'criteria_name',)->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name',)->union(HistoryPerformance::select('id_category', 'category_name', 'id_criteria', 'criteria_name',)->groupBy('id_category', 'category_name', 'id_criteria', 'criteria_name',))->get();
        $hsubprs = HistoryPresence::select('id_criteria', 'criteria_name')->groupBy('id_criteria', 'criteria_name')->get();
        $hsubprf = HistoryPerformance::select('id_criteria', 'criteria_name')->groupBy('id_criteria', 'criteria_name')->get();

        return view('Pages.Admin.input', compact('officers', 'performances', 'presences', 'periods', 'latest_per', 'history_per', 'criterias', 'allsubcriterias', 'subcriterias', 'countsub', 'countprs', 'countprf', 'subcritprs', 'subcritprf', 'status', 'historyprs', 'historyprf', 'hofficer', 'hcriteria', 'hallsub', 'hsubprs', 'hsubprf'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $criterias = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: PRS-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_presence = "PRS-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //STORE DATA
            Presence::insert([
                'id_presence'=>$id_presence,
                'id_period'=>$request->id_period,
                'id_officer'=>$request->id_officer,
                'id_criteria'=>$criteria->id_criteria,
                'input'=>$request->input($criteria->id_criteria),
                'status'=>'Pending',
            ]);
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.presences.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Tambah Data Kehadiran Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $criterias = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: PRS-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_presence = "PRS-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //UPDATE DATA
            if(Presence::where('id_presence', $id_presence)->exists()){
                Presence::where('id_presence', $id_presence)->update([
                    'input'=>$request->input($criteria->id_criteria),
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
                    'id_criteria'=>$criteria->id_criteria,
                    'input'=>$request->input($criteria->id_criteria),
                    'status'=>'Pending',
                ]);
            }
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.presences.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Ubah Data Kehadiran Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $criterias = Criteria::with('category')->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})->get();
        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: PRS-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_presence = "PRS-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //DELETE DATA
            Presence::where('id_presence', $id_presence)->delete();
        }

        //RETURN TO VIEW
        return redirect()->route('admin.inputs.presences.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Hapus Data Kehadiran Berhasil')->with('code_alert', 1);
    }
}
