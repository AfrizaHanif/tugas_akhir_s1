<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\SubTeam;
use App\Models\Team;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;

class SubTeamsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        /*
        $total_id = SubTeam::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        //$str_cri = substr($request->id_criteria, 4);
        //$id_sub_team = "STM-".$str_id;

        $get_prt = Part::with('team')
        ->whereHas('team', function($query) use($request){$query->where('id_team', $request->id_team);})
        ->first();
        $str_prt = substr($get_prt->id_category, 4);
        */
        $str_tim = substr($request->id_team, 4); //CRP-000-000-xxx (CAT-CRT-CRP)
        $id_sub_team = IdGenerator::generate([
            'table'=>'sub_teams',
            'field'=>'id_sub_team',
            'length'=>7,
            //'length'=>15,
            'prefix'=>'STM-',
            //'prefix'=>'STM-'.$str_tim.'-',
            //'prefix'=>'STM-'.$str_prt.'-'.$str_tim.'-',
            'reset_on_prefix_change'=>true,
        ]);
        //dd($id_sub_team);

        //GET REDIRECT
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($request){
            $query->where('id_team', $request->id_team);
        })->first();

        //STORE DATA
        SubTeam::insert([
            'id_sub_team'=>$id_sub_team,
            'id_team'=>$request->id_team,
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Tambah Sub Tim Berhasil')->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$request->id_team])->with('modal_redirect', 'modal-tim-view')->with('id_redirect', $redirect_part->id_part);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubTeam $subteam)
    {
        //GET REDIRECT
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($subteam){
            $query->where('id_team', $subteam->id_team);
        })->first();

        //UPDATE DATA
        $subteam->update([
            'name'=>$request->name,
            'id_team'=>$request->id_team,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Ubah Sub Tim Berhasil')->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$subteam->id_team])->with('modal_redirect', 'modal-tim-view')->with('id_redirect', $redirect_part->id_part);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubTeam $subteam)
    {
        //GET REDIRECT
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($subteam){
            $query->where('id_team', $subteam->id_team);
        })->first();
        $redirect_team = Team::with('subteam')
        ->whereHas('subteam', function($query) use($subteam){$query->where('id_sub_team', $subteam->id_sub_team);})->latest()->first();

        //DESTROY DATA
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($subteam){$query->where('id_team', $subteam->id_team);})->latest()->first();
        $redirect_team = Team::where('id_team', $subteam->id_team)->first();

        $subteam->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Hapus Sub Tim Berhasil')->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])->with('modal_redirect', 'modal-tim-view')->with('id_redirect', $redirect_part->id_part);
    }
}
