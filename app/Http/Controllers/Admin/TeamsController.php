<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Team;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        /*
        $total_id = Team::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        */
        //$str_cri = substr($request->id_criteria, 4);
        $str_prt = substr($request->id_part, 4); //TIM-000-xxx (CAT-CRT)
        $id_team = IdGenerator::generate([
            'table'=>'teams',
            'field'=>'id_team',
            'length'=>7,
            //'length'=>11,
            'prefix'=>'TIM-',
            //'prefix'=>'TIM-'.$str_prt.'-',
            'reset_on_prefix_change'=>true,
        ]);
        //dd($id_team);
        //$id_team = "TIM-".$str_id;

        //STORE DATA
        Team::insert([
            'id_team'=>$id_team,
            'id_part'=>$request->id_part,
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Tambah Tim Berhasil')->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'modal_tab_redirect'=>'pills-'.$id_team])->with('modal_redirect', 'modal-tim-view')->with('id_redirect', $request->id_part);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        //UPDATE DATA
        $team->update([
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Ubah Tim Berhasil')->withInput(['tab_redirect'=>'pills-'.$team->id_part, 'modal_tab_redirect'=>'pills-'.$team->id_team])->with('modal_redirect', 'modal-tim-view')->with('id_redirect', $team->id_part);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //DESTROY DATA
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($team){$query->where('id_team', $team->id_team);})->latest()->first();
        $redirect_team = Team::where('id_team', $team->id_team)->first();

        $team->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Hapus Tim Berhasil')->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])->with('modal_redirect', 'modal-tim-view')->with('id_redirect', $redirect_part->id_part);
    }
}
