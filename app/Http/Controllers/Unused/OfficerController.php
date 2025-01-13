<?php

namespace App\Http\Controllers\Unused;

use App\Http\Controllers\Controller;
use App\Exports\EmployeesExport;
use App\Imports\CheckImport;
use App\Imports\EmployeesImport;
//use App\Imports\EmployeesModalImport;
use App\Imports\UserImport;
use App\Models\Position;
use App\Models\Input;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Part;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\SubTeam;
use App\Models\Team;
use App\Models\User;
use Exception;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get(); //GET PARTS
        $parts_2 = Part::whereNotIn('name', ['Developer', 'Kepemimpinan'])->get(); //GET PARTS (ONLY LEADER)
        $positions = Position::whereNot('name', 'Developer')->get(); //GET POSITIONS
        $kbps_positions = Position::whereNot('name', 'Developer')->where('id_position', 'POS-001')->get(); //GET POSITIONS BY KBPS
        $umum_positions = Position::whereNot('name', 'Developer')->whereNot('id_position', 'POS-001')->get(); //GET POSITIONS BY NOT KBPS
        $emp_positions = Position::whereNot('name', 'Developer')->whereNotIn('id_position', ['POS-001', 'POS-002'])->get(); //GET POSITIONS BY NOT KBPS AND UMUM
        $teams = Team::with('part')->get(); //GET TEAMS
        $team_lists = Team::with('part')->whereNotIn('name', ['Developer', 'Pimpinan BPS'])->get(); //GET LIST OF TEAM
        $subteams = SubTeam::with('team')->get(); //GET SUB TEAMS
        $employees = Employee::with('position', 'subteam_1', 'subteam_2')
        //->where('status', 'Active')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get(); //GET EMPLOYEES
        $users = User::get(); //GET USERS
        //dd($employees);

        //RETURN TO VIEW
        if(Auth::check()){
            if(Auth::user()->part == "Admin" || Auth::user()->part == "KBPS"){
                return view('Pages.Admin.employee', compact('parts', 'parts_2', 'positions', 'kbps_positions', 'umum_positions', 'emp_positions', 'teams', 'team_lists', 'subteams', 'employees', 'users'));
            }elseif(Auth::user()->part == "Dev"){
                return view('Pages.Developer.employee', compact('parts', 'parts_2', 'positions', 'kbps_positions', 'umum_positions', 'emp_positions', 'teams', 'team_lists', 'subteams', 'employees', 'users'));
            }else{
                return view('Pages.Employee.employee', compact('parts', 'parts_2', 'positions', 'kbps_positions', 'umum_positions', 'emp_positions', 'teams', 'team_lists', 'subteams', 'employees', 'users'));
            }
        }else{
            return view('Pages.Home.employee', compact('parts', 'parts_2', 'positions', 'kbps_positions', 'umum_positions', 'emp_positions', 'teams', 'team_lists', 'subteams', 'employees', 'users', 'employees'));
        }
    }

    public function store(Request $request)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.employees.index';
        }else{
            $redirect_route = 'developer.masters.employees.index';
        }

        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Karyawan',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()->route($redirect_route)
                ->with('fail','Tidak dapat menambahkan karyawan dikarenakan sedang dalam proses verifikasi nilai.')
                ->with('code_alert', 2)
                ->withInput(['tab_redirect'=>'pills-'.$request->id_part])
                ->with('modal_redirect', 'modal-emp-create');
            }
        }

        //COMBINE KODE
        $id_user = IdGenerator::generate([
            'table'=>'users',
            'field'=>'id_user',
            'length'=>7,
            'prefix'=>'USR-',
            'reset_on_prefix_change'=>true,
        ]);
        /*
        $total_id = Employee::
        whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('status', 'Active')
        ->count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_employee = "EMP-".$str_id;
        */
        /*
        $id_employee = IdGenerator::generate([
            'table'=>'employees',
            'field'=>'id_employee',
            'length'=>7,
            'prefix'=>'EMP-',
            'reset_on_prefix_change'=>true,
        ]);
        */
        //$id_employee = 'EMP-'.$request->nip;

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'id_employee' => 'unique:employees',
            'name' => 'unique:employees',
            'email' => 'unique:employees',
            'phone' => 'unique:employees',
        ], [
            'id_employee.unique' => 'NIP tidak boleh sama dengan yang terdaftar',
            'name.unique' => 'Nama telah terdaftar',
            'email.unique' => 'E-Mail telah terdaftar',
            'phone.unique' => 'Nomor telepon telah terdaftar',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Karyawan',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Karyawan Tidak Berhasil (Beberapa Data Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route($redirect_route)
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'old_input'=>$request->all()])
            ->with('modal_redirect', 'modal-emp-create')
            ->with('id_redirect', $request->id_part)
            ->with('code_alert', 2);
        }

        //CHECK LEAD MORE THAN 1
        $count_lead = Employee::with('position')
        ->where('status', 'Active')
        ->whereHas('position', function($query){$query->where('name', 'LIKE', 'Kepala%');})
        ->where('id_position', $request->id_position)
        ->count();
        if(!empty($count_lead)){
            if($count_lead > 0){
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Karyawan',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Karyawan Tidak Berhasil (Kepala BPS Jawa Timur tidak boleh lebih dari satu)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Kepala BPS Jawa Timur / Bagian Umum tidak boleh lebih dari satu karyawan. Jika dikarenakan pindah kerja, mohon untuk mengubah jabatan dari Kepala BPS Jatim / Bagian Umum sebelumnya, lalu ubah pada Kepala BPS Jatim / Bagian Umum terbaru.')
                ->with('modal_redirect', 'modal-emp-create')
                ->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'old_input'=>$request->all()])
                ->with('code_alert', 2);
            }
        }

        //CHECK SAME TEAM
        if($request->id_sub_team_1 == $request->id_sub_team_2){
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Karyawan',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Karyawan Tidak Berhasil (Tim Utama dan Tim Cadangan tidak boleh sama)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route($redirect_route)->with('fail','Tim Utama dan Tim Cadangan tidak boleh sama. Jika hanya satu karyawan, pilih Tidak Ada di Tim Cadangan.')
            ->with('code_alert', 2)
            ->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'old_input'=>$request->all()])
            ->with('modal_redirect', 'modal-emp-create')
            ->with('id_redirect', $request->id_part)
            ->with('code_alert', 2);
        }

        //CHECK SUITABLE PART FOR USER
        /*
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                $part = 'KBPS';
                $tim_1 = 'STM-001';
                $tim_2 = null;
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
            }else{
                $part = 'Karyawan';
            }
            $tim_1 = $request->id_sub_team_1;
            $tim_2 = $request->id_sub_team_2;
        }
            */
        $check_lead1 = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        $check_lead2 = Position::where('name', 'LIKE', 'Kepala Bagian Umum%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead1->id_position)){
            if($check_lead1->id_position == $request->id_position){
                $part = 'KBPS';
                $tim_1 = 'STM-001';
                $tim_2 = null;
                $is_hr = false;
            }
        }elseif(!empty($check_lead2->id_position)){
            if($check_lead2->id_position == $request->id_position){
                if($request->has('is_hr')){
                    $part = 'Admin';
                    $is_hr = true;
                }else{
                    $part = 'Karyawan';
                    $is_hr = false;
                }
                $tim_1 = 'STM-002';
                $tim_2 = $request->id_sub_team_2;
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
                $is_hr = true;
            }else{
                $part = 'Karyawan';
                $is_hr = false;
            }
            //$part = 'Karyawan';
            $tim_1 = $request->id_sub_team_1;
            $tim_2 = $request->id_sub_team_2;
        }
        //dd($part);

        //UPLOAD PHOTO
        $photo = '';
        if($request->photo){
            $photo = 'IMG-'.$request->id_employee.'.'.$request->photo->extension();
            $request->photo->move(public_path('Images/Portrait/'), $photo);
        }else{
            //SKIP
        }

        //STORE DATA
        Employee::insert([
            'id_employee'=>$request->id_employee,
            //'nip'=>$request->nip,
            'name'=>$request->name,
            'id_position'=>$request->id_position,
            'id_sub_team_1'=>$tim_1,
            'id_sub_team_2'=>$tim_2,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'place_birth'=>$request->place_birth,
            'date_birth'=>$request->date_birth,
            'gender'=>$request->gender,
            'religion'=>$request->religion,
            'photo'=>$photo,
            //'is_lead'=>'No',
            'is_hr'=>$is_hr,
            'status'=>'Active',
		]);
        User::insert([
            'id_user'=>$id_user,
            'username'=>$request->id_employee,
            //'name'=>$request->name,
            //'nip'=>$request->id_employee,
            'id_employee'=>$request->id_employee,
            'password'=>Hash::make('bps3500'),
            'part'=>$part,
        ]);

        //IF LEAD
        /*
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                Employee::where('id_employee', $request->id_employee)->update([
                    //'is_lead'=>'Yes',
                ]);
            }
        }
        */

        //GET FOR REDIRECT
        $tab = Team::with('subteam')
        ->whereHas('subteam', function($query) use($request){$query->where('id_sub_team', $request->id_sub_team_1);})->latest()->first();

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Karyawan',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Karyawan Berhasil ('.$request->name.')',
        ]);
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Pengguna Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'sub_tab_redirect'=>$request->id_part.'-'.$tab->id_team.'-tab-pane'])
        ->with('success','Tambah Karyawan Berhasil')
        ->with('code_alert', 1);
    }

    public function update(Request $request, Employee $employee)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.employees.index';
        }else{
            $redirect_route = 'developer.masters.employees.index';
        }

        //GET FOR REDIRECT
        if($request->filled('id_sub_team_1')){
            $redirect = Part::with('team')
            ->whereHas('team', function($query) use($request){
                $query->with('subteam')->whereHas('subteam', function($query) use($request){
                    $query->where('id_sub_team', $request->id_sub_team_1);
                });
            })->first(); //GET PART FOR REDIRECT TO PART
            $tab = Team::with('subteam')
            ->whereHas('subteam', function($query) use($request){
                $query->where('id_sub_team', $request->id_sub_team_1);
            })->latest()->first(); //GET TEAM FOR REDIRECT TO TAB
            //dd($redirect->id_part);
        }else{
            if($request->id_position == 'POS-002'){
                $redirect = 'PRT-002'; //GET PART FOR REDIRECT TO PART
                $tab = 'TIM-002'; //GET TEAM FOR REDIRECT TO TAB
            }else{
                $redirect = 'PRT-001'; //GET PART FOR REDIRECT TO PART
                $tab = 'TIM-001'; //GET TEAM FOR REDIRECT TO TAB
            }
        }
        //dd($tab);

        //COMBINE KODE
        $id_user = IdGenerator::generate([
            'table'=>'users',
            'field'=>'id_user',
            'length'=>7,
            'prefix'=>'USR-',
            'reset_on_prefix_change'=>true,
        ]);

        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first(); //GET CURRENT PERIOD
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){ //PROGRESS: VERIFYING
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Karyawan',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Tidak dapat mengubah karyawan dikarenakan sedang dalam proses verifikasi nilai.')
                ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
                ->with('modal_redirect', 'modal-emp-update')
                ->with('id_redirect', $employee->id_employee)
                ->with('code_alert', 2);
            }
        }

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'id_employee' => [Rule::unique('employees')->ignore($employee),],
            'name' => [Rule::unique('employees')->ignore($employee),]
        ], [
            'id_employee.unique' => 'NIP tidak boleh sama dengan yang terdaftar',
            'name.unique' => 'Nama telah terdaftar',
        ]);
        if ($validator->fails()) {
            //CREATE A LOG
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Karyawan',
                'progress'=>'Update',
                'result'=>'Error',
                'descriptions'=>'Ubah Karyawan Tidak Berhasil (Beberapa Data Telah Terdaftar di Database)',
            ]);

            //RETURN TO VIEW
            return redirect()
            ->route($redirect_route)
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
            ->with('modal_redirect', 'modal-emp-update')
            ->with('id_redirect', $employee->id_employee)
            ->with('code_alert', 2);
        }

        //CHECK LEAD MORE THAN 1
        if($request->id_position != $employee->id_position){
            $count_lead = Employee::with('position')
            ->where('status', 'Active')
            ->whereHas('position', function($query){$query->where('name', 'LIKE', 'Kepala%');})
            ->where('id_position', $request->id_position)
            ->count();
            //dd($count_lead);
            if(!empty($count_lead)){
                if($count_lead > 0){
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Karyawan',
                        'progress'=>'Update',
                        'result'=>'Error',
                        'descriptions'=>'Ubah Karyawan Tidak Berhasil (Kepala BPS Jawa Timur tidak boleh lebih dari satu)',
                    ]);

                    //RETURN TO VIEW
                    return redirect()
                    ->route($redirect_route)
                    ->with('fail','Kepala BPS Jawa Timur / Bagian Umum tidak boleh lebih dari satu karyawan. Jika dikarenakan pindah kerja, mohon untuk mengubah jabatan dari Kepala BPS Jatim / Bagian Umum sebelumnya, lalu ubah pada Kepala BPS Jatim / Bagian Umum terbaru.')
                    ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
                    ->with('modal_redirect', 'modal-emp-update')
                    ->with('id_redirect', $employee->id_employee)
                    ->with('code_alert', 2);
                }
            }
        }

        //CHECK SAME TEAM
        if($request->filled('id_sub_team_1') && $request->filled('id_sub_team_2')){
            if($request->id_sub_team_1 == $request->id_sub_team_2){
                //CREATE A LOG
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Karyawan',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Karyawan Tidak Berhasil (Tim Utama dan Tim Cadangan tidak boleh sama)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Tim Utama dan Tim Cadangan tidak boleh sama. Jika hanya satu karyawan, pilih Tidak Ada di Tim Cadangan.')
                ->withInput(['tab_redirect'=>'pills-'.$request->id_part])
                ->with('modal_redirect', 'modal-emp-update')
                ->with('id_redirect', $employee->id_employee)
                ->with('code_alert', 2);
            }
        }

        //CHECK SUITABLE PART FOR USER
        /*
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                $part = 'KBPS';
                $tim_1 = 'STM-001';
                $tim_2 = null;
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
            }else{
                $part = 'Karyawan';
            }

            if($request->id_position == 'POS-002'){
                $tim_1 = 'STM-002';
            }else{
                $tim_1 = $request->id_sub_team_1;
            }
            $tim_2 = $request->id_sub_team_2;
        }
            */
        $check_lead1 = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        $check_lead2 = Position::where('name', 'LIKE', 'Kepala Bagian Umum%')->where('id_position', $request->id_position)->first();
        //$check_sdm = Position::where('name', 'LIKE', '%SDM%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead1->id_position)){
            if($check_lead1->id_position == $request->id_position){
                $part = 'KBPS';
                $tim_1 = 'STM-001';
                $tim_2 = null;
                $is_hr = false;
            }
        }elseif(!empty($check_lead2->id_position)){
            if($check_lead2->id_position == $request->id_position){
                if($request->has('is_hr')){
                    $part = 'Admin';
                    $is_hr = true;
                }else{
                    $part = 'Karyawan';
                    $is_hr = false;
                }
                $tim_1 = 'STM-002';
                $tim_2 = $request->id_sub_team_2;
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
                $is_hr = true;
            }else{
                $part = 'Karyawan';
                $is_hr = false;
            }
            //$part = 'Karyawan';
            $tim_1 = $request->id_sub_team_1;
            $tim_2 = $request->id_sub_team_2;
        }

        //UPDATE DATA
        $employee->update([
            'id_employee'=>$request->id_employee,
            'name'=>$request->name,
            'id_position'=>$request->id_position,
            'id_sub_team_1'=>$tim_1,
            'id_sub_team_2'=>$tim_2,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'place_birth'=>$request->place_birth,
            'date_birth'=>$request->date_birth,
            'gender'=>$request->gender,
            'religion'=>$request->religion,
            //'photo'=>$request->photo,
            //'is_lead'=>'No',
            'is_hr'=>$is_hr,
		]);
        if(!empty(User::where('id_employee', $employee->id_employee)->first())){ //IF USER EXISTS
            User::where('id_employee', $employee->id_employee)->update([
                //'name'=>$request->name,
                //'nip'=>$request->id_employee,
                'id_employee'=>$request->id_employee,
                'part'=>$part,
            ]);
        }else{
            User::insert([
                'id_user'=>$id_user,
                //'nip'=>$request->id_employee,
                'id_employee'=>$request->id_employee,
                //'name'=>$request->name,
                'username'=>$request->id_employee,
                'password'=>Hash::make('bps3500'),
                'part'=>$part,
            ]);
        }

        //UPDATE IMAGE
        $photo = '';
        $id_employee = Employee::find($employee->id_employee);
        $path_photo = public_path('Images/Portrait/'.$id_employee->photo);
        //dd($path_photo);
        if($request->hasFile('photo')){
            File::delete($path_photo);
            $photo = 'IMG-'.$employee->id_employee.'.'.$request->photo->extension();
            $request->photo->move(public_path('Images/Portrait/'), $photo);
            $employee->update([
                'photo'=>$request['image'] = $photo,
            ]);
        }
        if($request->has('photo_erase')){
            File::delete($path_photo);
            $employee->update([
                'photo'=>null,
            ]);
        }

        //IF LEAD
        /*
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                Employee::where('id_employee', $employee->id_employee)->update([
                    //'is_lead'=>'Yes',
                ]);
            }
        }
        */

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Karyawan',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Karyawan Berhasil ('.$request->name.')',
        ]);
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Pengguna Berhasil ('.$request->name.')',
        ]);

        //RETURN TO VIEW
        if($request->filled('id_sub_team_1')){
            return redirect()
            ->route($redirect_route)
            ->with('success','Ubah Karyawan Berhasil')
            ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
            ->with('code_alert', 1);
        }else{
            return redirect()
            ->route($redirect_route)
            ->with('success','Ubah Karyawan Berhasil')
            ->withInput(['tab_redirect'=>'pills-'.$redirect, 'sub_tab_redirect'=>$redirect.'-'.$tab.'-tab-pane'])
            ->with('code_alert', 1);
        }
    }

    public function destroy(Employee $employee)
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
                    'activity'=>'Karyawan',
                    'progress'=>'Delete',
                    'result'=>'Error',
                    'descriptions'=>'Hapus Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Hapus Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->with('code_alert', 1);
            }
        }

        //GET FOR REDIRECT
        /*
        $redirect = Part::with('team')
        ->whereHas('team', function($query) use($employee){
            $query->with('subteam')->whereHas('subteam', function($query) use($employee){
                $query->where('id_sub_team', $employee->id_sub_team_1);
            });
        })->first();
        $tab = Team::with('subteam')
        ->whereHas('subteam', function($query) use($employee){
            $query->where('id_sub_team', $employee->id_sub_team_1);
        })->latest()->first();
        */

        //DESTROY IMAGE
        $id_employee = Employee::find($employee->id_employee);
        $path_photo = public_path('Images/Portrait/'.$id_employee->photo);
        if(File::exists($path_photo)){
            File::delete($path_photo);
        }

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Karyawan',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Karyawan Berhasil ('.$employee->name.')',
        ]);
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Pengguna Berhasil ('.$employee->name.')',
        ]);

        //DESTROY DATA
        if(!empty(User::where('id_employee', $employee->id_employee)->first())){
            //Log::where('id_user', User::where('nip', $employee->id_employee)->first()->id_user)->delete();
            Log::where('id_user', User::where('id_employee', $employee->id_employee)->first()->id_user)->delete();
        }
        //User::where('nip', $employee->id_employee)->delete();
        User::where('id_employee', $employee->id_employee)->delete();
        Input::where('id_employee', $employee->id_employee)->delete();
        $employee->delete();

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->withInput(['tab_redirect'=>'pills-inactive'])
        ->with('success','Hapus Karyawan Berhasil')
        ->with('code_alert', 1);
    }

    public function retire(Request $request)
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
                    'activity'=>'Karyawan',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Nonaktif Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                //RETURN TO VIEW
                return redirect()
                ->route($redirect_route)
                ->with('fail','Nonaktif Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->with('code_alert', 1);
            }
        }

        //GET FOR REDIRECT
        /*
        $redirect = Part::with('team')
        ->whereHas('team', function($query) use($request){
            $query->with('subteam')->whereHas('subteam', function($query) use($request){
                $query->where('id_sub_team', $request->id_sub_team_1);
            });
        })->first();
        $tab = Team::with('subteam')
        ->whereHas('subteam', function($query) use($request){
            $query->where('id_sub_team', $request->id_sub_team_1);
        })->latest()->first();
        */

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Karyawan',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Nonaktif Karyawan Berhasil ('.$request->name.')',
        ]);

        //UPDATE DATA
        Employee::where('id_employee', $request->id_employee)->update([
            'status'=>'Not Active',
		]);

        //RETURN TO VIEW
        return redirect()
        ->route($redirect_route)
        ->withInput(['tab_redirect'=>'pills-inactive'])
        ->with('success','Nonaktif Karyawan Berhasil')
        ->with('code_alert', 1);
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
        ->where('status', 'Active')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->paginate(10);

        //RETURN TO VIEW
        if(Auth::check()){
            if(Auth::user()->part == "Admin" || Auth::user()->part == "KBPS"){
                return view('Pages.Admin.employee', compact('parts', 'positions', 'teams', 'subteams', 'employees', 'search'));
            }elseif(Auth::user()->part == "Dev"){
                return view('Pages.Developer.employee', compact('parts', 'positions', 'teams', 'subteams', 'employees', 'search'));
            }else{
                return view('Pages.Employee.employee', compact('parts', 'positions', 'teams', 'subteams', 'employees', 'search'));
            }
        }else{
            return view('Pages.Home.employee', compact('search', 'parts', 'positions', 'teams', 'subteams', 'employees'));
        }
    }

    public function import(Request $request): RedirectResponse
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.employees.index';
        }else{
            $redirect_route = 'developer.masters.employees.index';
        }

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
                ->route($redirect_route)
                ->with('fail','Import Karyawan Tidak Berhasil (Proses Verifikasi Sedang Berjalan)')
                ->with('code_alert', 1);
            }
        }

        //ERASE ALL DATA (RESET ONLY)
        if($request->import_method == 'reset'){
            /*
            //CHECK POSITION AND TEAM
            $import_check = New CheckImport($redirect_route);
            $import_check->import($request->file('file'));

            if($import_check->failRedirect() == '0'){
                return redirect()
                ->route($redirect_route)
                ->with('fail','Import Karyawan Tidak Berhasil (Data Karyawan / Tim tidak sama dengan yang terdaftar)')
                ->with('code_alert', 1);
            }
                */

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
            if(Auth::user()->part == 'Dev'){
                if($request->import_method == 'reset'){
                    //LOGOUT
                    User::whereNot('id_user', 'USR-000')->update(['force_logout' => true]); //FUTURE DEVELOPMENT

                    //CREATE A LOG
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
                }else{
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Karyawan',
                        'progress'=>'All',
                        'result'=>'Success',
                        'descriptions'=>'Import Karyawan Berhasil (Update or Create)',
                    ]);

                    //RETURN TO VIEW
                    return redirect()
                    ->route('developer.masters.employees.index')
                    ->with('success','Import Karyawan Berhasil')
                    ->with('code_alert', 1);
                }
            }else{
                if($request->import_method == 'reset'){
                    //LOGOUT
                    User::whereNot('id_user', 'USR-000')->update(['force_logout' => true]); //FUTURE DEVELOPMENT
                    Session::flush();
                    User::whereNot('id_user', 'USR-000')->update(['remember_token' => null]); //FUTURE DEVELOPMENT
                    Auth::logout();
                    request()->session()->invalidate();
                    request()->session()->regenerateToken();
                    //$request->session()->invalidate();
                    //$request->session()->regenerateToken();

                    //CREATE A LOG
                    Log::create([
                        'id_user'=>'USR-000',
                        'activity'=>'Karyawan',
                        'progress'=>'All',
                        'result'=>'Success',
                        'descriptions'=>'Import Karyawan Berhasil (Reset)',
                    ]);

                    //RETURN TO VIEW
                    return redirect()
                    ->route('index')
                    ->with('success','Import Karyawan Berhasil. Demi keamanan, silahkan lakukan login kembali dengan password "bps3500".')
                    ->with('code_alert', 1);
                }else{
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Karyawan',
                        'progress'=>'All',
                        'result'=>'Success',
                        'descriptions'=>'Import Karyawan Berhasil (Update or Create)',
                    ]);

                    //RETURN TO VIEW
                    return redirect()
                    ->route('admin.masters.employees.index')
                    ->with('success','Import Karyawan Berhasil')
                    ->with('code_alert', 1);
                }
            }
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
                ->route($redirect_route)
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
                ->route($redirect_route)
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
                ->route($redirect_route)
                ->with('fail', $message)
                ->with('code_alert', 1);
            }
        }
    }

    public function export(Request $request)
    {
        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Export Karyawan',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Export Karyawan Berhasil',
        ]);

        //GET EXPORT FILE
        return Excel::download(new EmployeesExport, 'EMP-Backup.xlsx');

        //NOTE: NO NEED TO RETURN TO VIEW. LET TOASTS REMIND YOU AFTER EXPORT
    }
}
