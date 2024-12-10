<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Imports\OfficersImport;
use App\Imports\UserImport;
use App\Models\Input;
use App\Models\Log;
use App\Models\Position;
use App\Models\Officer;
use App\Models\Part;
use App\Models\SubTeam;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficerController extends Controller
{
    public function index()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $parts_2 = Part::whereNotIn('name', ['Developer', 'Kepemimpinan'])->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $kbps_positions = Position::whereNot('name', 'Developer')->where('id_position', 'DPT-001')->get();
        $umum_positions = Position::whereNot('name', 'Developer')->whereNot('id_position', 'DPT-001')->get();
        $off_positions = Position::whereNot('name', 'Developer')->whereNotIn('id_position', ['DPT-001', 'DPT-002'])->get();
        $teams = Team::with('part')->get();
        $team_lists = Team::with('part')->whereNotIn('name', ['Developer', 'Pimpinan BPS'])->get();
        $subteams = SubTeam::with('team')->get();
        $officers = Officer::with('position', 'subteam_1', 'subteam_2')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();
        $users = User::get();
        //dd($officers);

        //RETURN TO VIEW
        return view('Pages.Developer.officer', compact('parts', 'parts_2', 'positions', 'kbps_positions', 'umum_positions', 'off_positions', 'teams', 'team_lists', 'subteams', 'officers', 'users'));
    }

    public function search(Request $request)
    {
        //GET DATA
        $search = $request->search;
        $parts = Part::whereNot('name', 'Developer')->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $teams = Team::with('part')->get();
        $subteams = SubTeam::with('team')->get();

        //GET SEARCH QUERY
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->paginate(10);

        //RETURN TO VIEW
        return view('Pages.Developer.officer', compact('parts', 'positions', 'teams', 'subteams', 'officers', 'search'));
    }

    public function import(Request $request): RedirectResponse
    {
        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Pegawai',
                    'progress'=>'Import',
                    'result'=>'Error',
                    'descriptions'=>'Import Pegawai Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('developer.masters.officers.index')
                ->with('fail','Import Pegawai Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->with('code_alert', 1);
            }
        }

        //ERASE ALL DATA (RESET ONLY)
        if($request->import_method == 'reset'){
            //DELETE ALL DATA (INPUTS AND OFFICERS)
            DB::statement("SET foreign_key_checks=0");
            Log::truncate();
            Input::truncate();
            //User::whereNot('id_user', 'USR-000')->delete();
            Officer::truncate();
            DB::statement("SET foreign_key_checks=1");

            /*
            //IMPORT FILE
            $import = New OfficersModalImport();
            $import->import($request->file('file'));

            //CHECK IF DUPLICATE
            if ($import->failures()->isNotEmpty()) {
                return redirect()->route('admin.masters.officers.index')
                ->withFailures($import->failures())
                ->with('code_alert', 1);
            }else{
                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.officers.index')
                ->with('success','Import Pegawai Berhasil')
                ->with('code_alert', 1);
            }
            */
        }

        //TRY AND CATCH
        try{
            //IMPORT FILE (OFFICERS)
            $import_off = New OfficersImport($request->import_method);
            $import_off->import($request->file('file'));

            //DELETE USER
            if($request->import_method == 'reset'){
                User::whereNot('id_user', 'USR-000')->delete();
            }

            //IMPORT FILE (USERS)
            $import_usr = New UserImport($request->import_method);
            $import_usr->import($request->file('file'));

            //RETURN TO VIEW
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Pegawai',
                'progress'=>'All',
                'result'=>'Success',
                'descriptions'=>'Import Pegawai Berhasil (Reset)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route('developer.masters.officers.index')
            ->with('success','Import Pegawai Berhasil')
            ->with('code_alert', 1);
        }catch (QueryException $ex){
            //GET ERROR MESSAGE AND INFO
            $message = $ex->getMessage();
            $errorCode = $ex->errorInfo[1];

            //RETURN TO VIEW
            if($errorCode == 1062){ //IF DATA IN EXCEL DUPLICATE
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Pegawai',
                    'progress'=>'Import',
                    'result'=>'Error',
                    'descriptions'=>'Import Pegawai Gagal (Duplikat Data di Excel)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('developer.masters.officers.index')
                ->with('fail', 'Import Pegawai Gagal. Terdapat duplikat data yang ada di Excel Import Pegawai. Silahkan cek file Excel kembali')
                ->with('code_alert', 1);
            }elseif($errorCode == 1364){ //IF COLUMN NOT MEET CRITERIA
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Pegawai',
                    'progress'=>'Import',
                    'result'=>'Error',
                    'descriptions'=>'Import Pegawai Gagal (Kolom Tidak Lengkap di Excel)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('developer.masters.officers.index')
                ->with('fail', 'Import Pegawai Gagal. Terdapat kolom yang tidak ada di Excel. Silahkan cek kebutuhan kolom di Modal Import atau hubungi Developer')
                ->with('code_alert', 1);
            }else{ //NORMAL ERROR
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Pegawai',
                    'progress'=>'Import',
                    'result'=>'Error',
                    'descriptions'=>'Import Pegawai Gagal ('.$message.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('developer.masters.officers.index')
                ->with('fail', $message)
                ->with('code_alert', 1);
            }
        }
    }
}
