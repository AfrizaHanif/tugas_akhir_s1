<?php

namespace App\Http\Controllers;

use App\Models\Input;
use App\Models\Log;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Period;
use App\Models\SubTeam;
use App\Models\Team;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubTeamsController extends Controller
{
    public function store(Request $request)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.officers.index';
        }else{
            $redirect_route = 'developer.masters.officers.index';
        }

        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD

        //GET REDIRECT
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($request){
            $query->where('id_team', $request->id_team);
        })->first(); //GET PART FOR REDIRECT

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Sub Tim',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Sub Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Hapus Sub Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part])
                ->with('modal_redirect', 'modal-tim-view')
                ->with('id_redirect', $redirect_part->id_part)
                ->with('code_alert', 2);
            }
        }

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

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => 'unique:sub_teams',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Sub Tim',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Sub Tim Tidak Berhasil (Nama '.$request->name.' Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()->route($redirect_route)->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'modal_tab_redirect'=>'pills-'.$request->id_team])->with('modal_redirect', 'modal-stm-create')->with('id_redirect', $request->id_team)->with('code_alert', 3);
        }

        //STORE DATA
        SubTeam::insert([
            'id_sub_team'=>$id_sub_team,
            'id_team'=>$request->id_team,
            'name'=>$request->name,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Sub Tim',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Sub Tim Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->with('success','Tambah Sub Tim Berhasil')
        ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$request->id_team])
        ->with('modal_redirect', 'modal-tim-view')
        ->with('id_redirect', $redirect_part->id_part)
        ->with('code_alert', 2);
    }

    public function update(Request $request, SubTeam $subteam)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.officers.index';
        }else{
            $redirect_route = 'developer.masters.officers.index';
        }

        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD

        //GET REDIRECT
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($subteam){
            $query->where('id_team', $subteam->id_team);
        })->latest()->first(); //GET PART FOR REDIRECT
        $redirect_team = Team::where('id_team', $subteam->id_team)->first(); //GET TEAM FOR REDIRECT

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Sub Tim',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Sub Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Hapus Sub Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$subteam->id_team])
                ->with('modal_redirect', 'modal-tim-view')
                ->with('id_redirect', $redirect_part->id_part)
                ->with('code_alert', 2);
            }
        }

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => 'unique:sub_teams',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Sub Tim',
                'progress'=>'Update',
                'result'=>'Error',
                'descriptions'=>'Ubah Sub Tim Tidak Berhasil (Nama '.$request->name.' Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route($redirect_route)
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])
            ->with('modal_redirect', 'modal-stm-update')
            ->with('id_redirect', $subteam->id_sub_team)
            ->with('code_alert', 3);
        }

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

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Sub Tim',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Sub Tim Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->with('success','Ubah Sub Tim Berhasil')
        ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$subteam->id_team])
        ->with('modal_redirect', 'modal-tim-view')
        ->with('id_redirect', $redirect_part->id_part)
        ->with('code_alert', 2);
    }

    public function destroy(SubTeam $subteam)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.officers.index';
        }else{
            $redirect_route = 'developer.masters.officers.index';
        }

        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD

        //GET REDIRECT
        /*
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($subteam){
            $query->where('id_team', $subteam->id_team);
        })->first();
        $redirect_team = Team::with('subteam')
        ->whereHas('subteam', function($query) use($subteam){$query->where('id_sub_team', $subteam->id_sub_team);})->latest()->first();
        */
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($subteam){
            $query->where('id_team', $subteam->id_team);
        })->latest()->first(); //GET PART FOR REDIRECT
        $redirect_team = Team::where('id_team', $subteam->id_team)->first(); //GET TEAM FOR REDIRECT

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Sub Tim',
                    'progress'=>'Delete',
                    'result'=>'Error',
                    'descriptions'=>'Hapus Sub Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Hapus Sub Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])
                ->with('modal_redirect', 'modal-tim-view')
                ->with('id_redirect', $redirect_part->id_part)
                ->with('code_alert', 2);
            }
        }

        //CHECK DATA
        /*
        if(Officer::where('id_sub_team_1', $subteam->id_sub_team)->orWhere('id_sub_team_2', $subteam->id_sub_team)->exists()) {
            return redirect()
            ->route($redirect_route)
            ->with('fail', 'Hapus Sub Tim Tidak Berhasil (Terhubung dengan tabel Pegawai)')
            ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])
            ->with('modal_redirect', 'modal-tim-view')
            ->with('id_redirect', $redirect_part->id_part)
            ->with('code_alert', 2);
        }else{
            //CLEAR
        }
            */

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Sub Tim',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Sub Tim Berhasil ('.$subteam->name.')',
        ]);

        //DESTROY DATA
        Input::with('officer')
        ->whereHas('officer', function($query) use($subteam){
            $query->where('id_sub_team_1', $subteam->id_sub_team);
        })
        ->delete();
        Officer::where('id_sub_team_2', $subteam->id_sub_team)->update([
            'id_sub_team_2'=>'',
        ]);
        Officer::where('id_sub_team_1', $subteam->id_sub_team)->delete();
        $subteam->delete();

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->with('success','Hapus Sub Tim Berhasil')
        ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])
        ->with('modal_redirect', 'modal-tim-view')
        ->with('id_redirect', $redirect_part->id_part)
        ->with('code_alert', 2);
    }
}
