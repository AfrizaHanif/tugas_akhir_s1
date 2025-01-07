<?php

namespace App\Http\Controllers;

use App\Models\Input;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Part;
use App\Models\Period;
use App\Models\SubTeam;
use App\Models\Team;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    public function store(Request $request)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.employees.index';
        }else{
            $redirect_route = 'developer.masters.employees.index';
        }

        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Tim',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Tambah Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$request->id_part])
                ->with('modal_redirect', 'modal-tim-view')
                ->with('id_redirect', $request->id_part)
                ->with('code_alert', 2);
            }
        }

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

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => 'unique:teams',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Tim',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Tim Tidak Berhasil (Nama '.$request->name.' Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route($redirect_route)
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'modal_tab_redirect'=>'pills-'.$id_team, 'old_input'=>$request->all()])
            ->with('modal_redirect', 'modal-tim-create')
            ->with('id_redirect', $request->id_part)
            ->with('code_alert', 3);
        }

        //STORE DATA
        Team::insert([
            'id_team'=>$id_team,
            'id_part'=>$request->id_part,
            'name'=>$request->name,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Tim',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Tim Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->with('success','Tambah Tim Berhasil')
        ->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'modal_tab_redirect'=>'pills-'.$id_team])
        ->with('modal_redirect', 'modal-tim-view')
        ->with('id_redirect', $request->id_part)
        ->with('code_alert', 2);
    }

    public function update(Request $request, Team $team)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.employees.index';
        }else{
            $redirect_route = 'developer.masters.employees.index';
        }

        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD

        //GET REDIRECT
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($team){
            $query->where('id_team', $team->id_team);
        })->latest()->first(); //GET PART FOR REDIRECT
        $redirect_team = Team::where('id_team', $team->id_team)->first(); //GET TEAM FOR REDIRECT

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Tim',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Hapus Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$team->id_part, 'modal_tab_redirect'=>'pills-'.$team->id_team])
                ->with('modal_redirect', 'modal-tim-view')
                ->with('id_redirect', $team->id_part)
                ->with('code_alert', 2);
            }
        }

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => 'unique:teams',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Tim',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Ubah Tim Tidak Berhasil (Nama '.$request->name.' Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route($redirect_route)
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])
            ->with('modal_redirect', 'modal-tim-update')
            ->with('id_redirect', $redirect_team->id_team)
            ->with('code_alert', 3);
        }

        //UPDATE DATA
        $team->update([
            'name'=>$request->name,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Tim',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Tim Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->with('success','Ubah Tim Berhasil')
        ->withInput(['tab_redirect'=>'pills-'.$team->id_part, 'modal_tab_redirect'=>'pills-'.$team->id_team])
        ->with('modal_redirect', 'modal-tim-view')
        ->with('id_redirect', $team->id_part)
        ->with('code_alert', 2);
    }

    public function destroy(Team $team)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.employees.index';
        }else{
            $redirect_route = 'developer.masters.employees.index';
        }

        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD

        //GET REDIRECT
        $redirect_part = Part::with('team')
        ->whereHas('team', function($query) use($team){
            $query->where('id_team', $team->id_team);
        })->latest()->first(); //GET PART FOR REDIRECT
        $redirect_team = Team::where('id_team', $team->id_team)->first(); //GET TEAM FOR REDIRECT

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Tim',
                    'progress'=>'Delete',
                    'result'=>'Error',
                    'descriptions'=>'Hapus Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Hapus Tim Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])
                ->with('modal_redirect', 'modal-tim-view')
                ->with('id_redirect', $redirect_part->id_part)
                ->with('code_alert', 2);
            }
        }

        //GET TEAM FOR CHECK
        //$check_team = Team::where('id_team', $team->id_team)->first();
        $subteams = SubTeam::where('id_team', $team->id_team)->get(); //GET SUB TEAM
        //dd($check_sub_team);

        //CHECK DATA
        /*
        foreach($subteams as $subteam){
            if(Employee::where('id_sub_team_1', $subteam->id_sub_team)
            ->orWhere('id_sub_team_2', $subteam->id_sub_team)
            ->where('status', 'Active')
            ->exists()){
                return redirect()
                ->route($redirect_route)
                ->with('fail', 'Hapus Tim Tidak Berhasil (Terhubung dengan tabel Karyawan)')
                ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])
                ->with('modal_redirect', 'modal-tim-view')
                ->with('id_redirect', $redirect_part->id_part)
                ->with('code_alert', 2);
            }else{
                //CLEAR
            }
        }
            */

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Tim',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Tim Berhasil ('.$team->name.')',
        ]);

        //DESTROY DATA
        $loop_subteam = SubTeam::where('id_team', $team->id_team)->get(); //GET SUB TEAMS FOR LOOP
        foreach($loop_subteam as $subteam){
            Input::with('employee')
            ->whereHas('employee', function($query) use($subteam){
                $query->where('id_sub_team_1', $subteam->id_sub_team);
            })
            ->delete();
            Employee::where('id_sub_team_2', $subteam->id_sub_team)->update([
                'id_sub_team_2'=>'',
            ]);
            Employee::where('id_sub_team_1', $subteam->id_sub_team)->delete();
        }
        SubTeam::where('id_team', $team->id_team)->delete();
        $team->delete();

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->with('success','Hapus Tim Berhasil')
        ->withInput(['tab_redirect'=>'pills-'.$redirect_part->id_part, 'modal_tab_redirect'=>'pills-'.$redirect_team->id_team])
        ->with('modal_redirect', 'modal-tim-view')
        ->with('id_redirect', $redirect_part->id_part)
        ->with('code_alert', 2);
    }
}
