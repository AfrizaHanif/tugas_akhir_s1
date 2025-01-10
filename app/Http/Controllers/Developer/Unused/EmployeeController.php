<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Imports\EmployeesImport;
use App\Imports\UserImport;
use App\Models\Input;
use App\Models\Log;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Part;
use App\Models\SubTeam;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $parts_2 = Part::whereNotIn('name', ['Developer', 'Kepemimpinan'])->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $kbps_positions = Position::whereNot('name', 'Developer')->where('id_position', 'POS-001')->get();
        $umum_positions = Position::whereNot('name', 'Developer')->whereNot('id_position', 'POS-001')->get();
        $emp_positions = Position::whereNot('name', 'Developer')->whereNotIn('id_position', ['POS-001', 'POS-002'])->get();
        $teams = Team::with('part')->get();
        $team_lists = Team::with('part')->whereNotIn('name', ['Developer', 'Pimpinan BPS'])->get();
        $subteams = SubTeam::with('team')->get();
        $employees = Employee::with('position', 'subteam_1', 'subteam_2')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('status', 'Active')
        ->get();
        $users = User::get();
        //dd($employees);

        //RETURN TO VIEW
        return view('Pages.Developer.employee', compact('parts', 'parts_2', 'positions', 'kbps_positions', 'umum_positions', 'emp_positions', 'teams', 'team_lists', 'subteams', 'employees', 'users'));
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
        $employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->where('status', 'Active')
        ->paginate(10);

        //RETURN TO VIEW
        return view('Pages.Developer.employee', compact('parts', 'positions', 'teams', 'subteams', 'employees', 'search'));
    }

    public function import(Request $request): RedirectResponse
    {
        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Karyawan',
                    'progress'=>'Import',
                    'result'=>'Error',
                    'descriptions'=>'Import Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('developer.masters.employees.index')
                ->with('fail','Import Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->with('code_alert', 1);
            }
        }

        //ERASE ALL DATA (RESET ONLY)
        if($request->import_method == 'reset'){
            //DELETE ALL DATA (INPUTS AND EMPLOYEES)
            DB::statement("SET foreign_key_checks=0");
            Log::truncate();
            Input::truncate();
            //User::whereNot('id_user', 'USR-000')->delete();
            Employee::truncate();
            DB::statement("SET foreign_key_checks=1");

            /*
            //IMPORT FILE
            $import = New EmployeesModalImport();
            $import->import($request->file('file'));

            //CHECK IF DUPLICATE
            if ($import->failures()->isNotEmpty()) {
                return redirect()->route('admin.masters.employees.index')
                ->withFailures($import->failures())
                ->with('code_alert', 1);
            }else{
                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.employees.index')
                ->with('success','Import Karyawan Berhasil')
                ->with('code_alert', 1);
            }
            */
        }

        //TRY AND CATCH
        try{
            //IMPORT FILE (EMPLOYEES)
            $import_off = New EmployeesImport($request->import_method);
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
                'activity'=>'Karyawan',
                'progress'=>'All',
                'result'=>'Success',
                'descriptions'=>'Import Karyawan Berhasil (Reset)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route('developer.masters.employees.index')
            ->with('success','Import Karyawan Berhasil')
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
                    'activity'=>'Karyawan',
                    'progress'=>'Import',
                    'result'=>'Error',
                    'descriptions'=>'Import Karyawan Gagal (Duplikat Data di Excel)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('developer.masters.employees.index')
                ->with('fail', 'Import Karyawan Gagal. Terdapat duplikat data yang ada di Excel Import Karyawan. Silahkan cek file Excel kembali')
                ->with('code_alert', 1);
            }elseif($errorCode == 1364){ //IF COLUMN NOT MEET CRITERIA
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Karyawan',
                    'progress'=>'Import',
                    'result'=>'Error',
                    'descriptions'=>'Import Karyawan Gagal (Kolom Tidak Lengkap di Excel)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('developer.masters.employees.index')
                ->with('fail', 'Import Karyawan Gagal. Terdapat kolom yang tidak ada di Excel. Silahkan cek kebutuhan kolom di Modal Import atau hubungi Developer')
                ->with('code_alert', 1);
            }else{ //NORMAL ERROR
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Karyawan',
                    'progress'=>'Import',
                    'result'=>'Error',
                    'descriptions'=>'Import Karyawan Gagal ('.$message.')',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route('developer.masters.employees.index')
                ->with('fail', $message)
                ->with('code_alert', 1);
            }
        }
    }
}
