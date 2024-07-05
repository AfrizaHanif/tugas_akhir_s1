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

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //GET DATA
        if(Auth::user()->part == 'KBU'){
            $officers = Officer::with('department')
            ->whereHas('department', function($query)
            {
                $query->with('part')->whereHas('part', function($query)
                {
                    $query->where('name', 'Bagian Umum');
                });
            })
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala%');})
            ->get();
        }elseif(Auth::user()->part == 'KTT'){
            $officers = Officer::with('department')
            ->whereHas('department', function($query){$query->where('name', 'LIKE', '%'.Auth::user()->officer->department->name.'%');})
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer')->orWhere('name', 'LIKE', 'Kepala%');})
            ->whereNot('name', Auth::user()->officer->name)
            ->get();
        }elseif(Auth::user()->part == 'KBPS'){
            $officers = Officer::with('department', 'user')
            ->whereHas('user', function($query)
            {
                $query->whereIn('part', ['KBU', 'KTT']);
            })
            ->whereDoesntHave('department', function($query)
            {$query->where('name', 'Developer');})
            ->get();
        }
        $performances = Performance::get();
        $presences = Presence::get();
        $status = Performance::select('id_period', 'id_officer', 'status')->groupBy('id_period', 'id_officer', 'status')->get();
        $periods = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending'])->get();
        $latest_per = Period::orderBy('id_period', 'ASC')->whereNotIn('status', ['Skipped', 'Pending', 'Finished'])->latest()->first();
        $history_per = HistoryPerformance::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->get();
        $criterias = Category::with('criteria')->get();
        $allsubcriterias = Criteria::with('category')->get();
        $subcriterias = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $subcritprs = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})
        ->get();
        $subcritprf = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        $countsub = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->count();
        $countprs = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Kehadiran');})
        ->count();
        $countprf = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->count();
        $historyprs = HistoryPresence::get();
        $historyprf = HistoryPerformance::get();
        $hofficer = HistoryPerformance::select('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->groupBy('id_period', 'period_name', 'id_officer', 'officer_name', 'officer_department')->get();
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
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: PRF-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_performance = "PRF-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //STORE DATA
            Performance::insert([
                'id_performance'=>$id_performance,
                'id_period'=>$request->id_period,
                'id_officer'=>$request->id_officer,
                'id_criteria'=>$criteria->id_criteria,
                'input'=>$request->input($criteria->id_criteria),
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
        $criterias = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: PRF-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_performance = "PRF-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //UPDATE DATA
            if(Performance::where('id_performance', $id_performance)->exists()){
                Performance::where('id_performance', $id_performance)->update([
                    'input'=>$request->input($criteria->id_criteria),
                    'status'=>'Pending',
                ]);
                Score::where('id_period', $request->id_period)->where('id_officer', $request->id_officer)->update([
                    'status'=>'Revised',
                ]);
            }else{
                Performance::insert([
                    'id_performance'=>$id_performance,
                    'id_period'=>$request->id_period,
                    'id_officer'=>$request->id_officer,
                    'id_criteria'=>$criteria->id_criteria,
                    'input'=>$request->input($criteria->id_criteria),
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
        $criterias = Criteria::with('category')
        ->WhereHas('category', function($query){$query->where('type', 'Prestasi Kerja');})
        ->get();
        foreach($criterias as $criteria){
            //COMBINE KODE (Ex: PRS-01-24-001-001)
            $str_officer = substr($request->id_officer, 4);
            $str_year = substr($request->id_period, -5);
            $str_sub = substr($criteria->id_criteria, 4);
            $id_performance = "PRF-".$str_year.'-'.$str_officer.'-'.$str_sub;

            //DELETE DATA
            Performance::where('id_performance', $id_performance)->delete();
        }

        //RETURN TO VIEW
        $lowerpart = strtolower(Auth::user()->part);
        return redirect()->route('admin.inputs.'.$lowerpart.'.performances.index')->withInput(['tab_redirect'=>'pills-'.$request->id_period])->with('success','Hapus Data Prestasi Kerja Berhasil')->with('code_alert', 1);
    }
}
