<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Employee;
use App\Models\Part;
use App\Models\Position;
use App\Models\SubTeam;
use App\Models\User;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //GET DATA
        $users = User::whereNot('id_user', 'USR-000')->get(); //GET USERS
        //$parts = Part::whereNot('name', 'Developer')->get();
        $subteams = SubTeam::get(); //GET SUB TEAMS
        /*
        $employees = Employee::with('position', 'subteam_1')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('subteam_1', function($query){
            $query->with('team')->whereHas('team', function($query){
                $query->with('part')->whereHas('part', function($query){
                    $query->where('name', 'Tim Teknis');
                });
            });
        })
        ->where('status', 'Active')
        ->get();
        */
        $employees = Employee::with('position', 'subteam_1')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        //->where('status', 'Active')
        ->get(); //GET EMPLOYEES
        $count_kbps = User::where('part', 'KBPS')->count(); //COUNT KBPS

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return view('Pages.Admin.user', compact('users', 'employees', 'subteams', 'count_kbps'));
        }elseif(Auth::user()->part == "Dev"){
            return view('Pages.Developer.user', compact('users', 'employees', 'subteams', 'count_kbps'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.users.index';
        }else{
            $redirect_route = 'developer.masters.users.index';
        }

        //GET DATA
        $employee = Employee::where('id_employee', $request->id_employee)
        //->where('status', 'Active')
        ->first(); //GET EMPLOYEES

        //COMBINE KODE
        /*
        $total_id = User::whereNot('id_user', 'USR-000')->count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_user = "USR-".$str_id;
        */
        $id_user = IdGenerator::generate([
            'table'=>'users',
            'field'=>'id_user',
            'length'=>7,
            'prefix'=>'USR-',
            'reset_on_prefix_change'=>true,
        ]);

        //VALIDATE DATA
        /*
        $request->validate([
            'id_employee' => 'unique:users',
            'username' => 'unique:users',
            'email' => 'unique:users',
        ], [
            'id_employee.unique' => 'Satu akun hanya dapat digunakan pada satu karyawan. Karyawan tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);
        $validator = Validator::make($request->all(), [
            //'id_employee' => 'unique:users',
            'username' => 'regex:/^\S*$/u|unique:users',
            'name' => 'unique:users',
            //'email' => 'unique:users',
        ], [
            //'id_employee.unique' => 'Satu akun hanya dapat digunakan pada satu karyawan. Karyawan tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'username.regex' => 'Username tidak boleh mengandung spasi',
            'name.unique' => 'Nama tidak boleh sama dengan yang terdaftar',
            //'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);
        if ($validator->fails()) {
            if(Auth::user()->part == "Admin"){
                return redirect()
                ->route('admin.masters.users.index')
                ->withErrors($validator)
                ->with('modal_redirect', 'modal-usr-create')
                ->with('code_alert', 2);
            }elseif(Auth::user()->part == "Dev"){
                return redirect()
                ->route('developer.masters.users.index')
                ->withErrors($validator)
                ->with('modal_redirect', 'modal-usr-create')
                ->with('code_alert', 2);
            }
        }
        */

        //CHECK LEAD MORE THAN 1
        $count_kbps = User::where('part', 'KBPS')->count(); //COUNT KBPS
        if($request->part == 'KBPS'){
            if(!empty($count_kbps)){
                if($count_kbps >= 1){
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Pengguna',
                        'progress'=>'Create',
                        'result'=>'Error',
                        'descriptions'=>'Tambah Pengguna Tidak Berhasil (Pengguna KBPS telah terdaftar)',
                    ]);

                    //RETURN TO VIEW
                    if(Auth::user()->part == "Admin"){
                        return redirect()
                        ->route('admin.masters.users.index')
                        ->with('fail','Tambah Pengguna Gagal (User Kepala BPS Jawa Timur tidak boleh lebih dari satu)')
                        ->with('modal_redirect', 'modal-usr-create')
                        ->with('code_alert', 2);
                    }elseif(Auth::user()->part == "Dev"){
                        return redirect()
                        ->route('developer.masters.users.index')
                        ->with('fail','Tambah Pengguna Gagal (User Kepala BPS Jawa Timur tidak boleh lebih dari satu)')
                        ->with('modal_redirect', 'modal-usr-create')
                        ->with('code_alert', 2);
                    }
                }
            }
        }

        //CHECK SUITABLE USER
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $employee->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $employee->id_position){
                $part = 'KBPS';
            }
        }else{
            if($employee->is_hr == true){
                $part = 'Admin';
            }else{
                $part = 'Karyawan';
            }
        }
        //dd($part);

        //STORE DATA
        /*
        User::insert([
            'id_user'=>$id_user,
            'username'=>$request->username,
            'name'=>$request->name,
            //'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'part'=>$request->part,
            //'id_employee'=>$request->id_employee,
		]);
        */
        User::insert([
            'id_user'=>$id_user,
            'id_employee'=>$employee->id_employee,
            'username'=>$employee->id_employee,
            //'nip'=>$employee->id_employee,
            //'name'=>$employee->name,
            //'email'=>$request->email,
            'password'=>Hash::make('bps3500'),
            'part'=>$part,
            //'id_employee'=>$request->id_employee,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Pengguna Berhasil ('.$employee->name.')',
        ]);

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()
            ->route('admin.masters.users.index')
            ->with('success','Tambah Pengguna Berhasil. Password default dari pengguna ini adalah "bps3500"')
            ->with('code_alert', 1);
        }elseif(Auth::user()->part == "Dev"){
            return redirect()
            ->route('developer.masters.users.index')
            ->with('success','Tambah Pengguna Berhasil. Password default dari pengguna ini adalah "bps3500"')
            ->with('code_alert', 1);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.users.index';
        }else{
            $redirect_route = 'developer.masters.users.index';
        }

        //VALIDATE DATA
        /*
        $request->validate([
            'id_employee' => [Rule::unique('users')->ignore($user),],
            'username' => [Rule::unique('users')->ignore($user),],
            'email' => [Rule::unique('users')->ignore($user),],
        ], [
            'id_employee.unique' => 'Satu akun hanya dapat digunakan pada satu karyawan. Karyawan tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);
        */
        /*
        $validator = Validator::make($request->all(), [
            //'id_employee' => [Rule::unique('users')->ignore($user),],
            'username' => [Rule::unique('users')->ignore($user),'regex:/^\S*$/u'],
            'name' => [Rule::unique('users')->ignore($user),],
            //'email' => [Rule::unique('users')->ignore($user),],
        ], [
            //'id_employee.unique' => 'Satu akun hanya dapat digunakan pada satu karyawan. Karyawan tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'username.regex' => 'Username tidak boleh mengandung spasi',
            'name.unique' => 'Nama tidak boleh sama dengan yang terdaftar',
            //'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);
        if ($validator->fails()) {
            if(Auth::user()->part == "Admin"){
                return redirect()
                ->route('admin.masters.users.index')
                ->withErrors($validator)
                ->with('modal_redirect', 'modal-usr-update')
                ->with('id_redirect', $user->id_user)
                ->with('code_alert', 2);
            }elseif(Auth::user()->part == "Dev"){
                return redirect()
                ->route('developer.masters.users.index')
                ->withErrors($validator)
                ->with('modal_redirect', 'modal-usr-update')
                ->with('id_redirect', $user->id_user)
                ->with('code_alert', 2);
            }
        }
        */

        //CHECK LEAD MORE THAN 1
        $count_kbps = User::where('part', 'KBPS')->count(); //COUNT KBPS
        if($request->part != $user->part){
            if($request->part == 'KBPS'){
                if(!empty($count_kbps)){
                    if($count_kbps >= 1){
                        //CREATE A LOG
                        Log::create([
                            'id_user'=>Auth::user()->id_user,
                            'activity'=>'Pengguna',
                            'progress'=>'Update',
                            'result'=>'Error',
                            'descriptions'=>'Ubah Pengguna Tidak Berhasil (Pengguna KBPS telah terdaftar)',
                        ]);

                        //RETURN TO VIEW
                        if(Auth::user()->part == "Admin"){
                            return redirect()
                            ->route('admin.masters.users.index')
                            ->with('fail','Ubah Pengguna Gagal (User Kepala BPS Jawa Timur tidak boleh lebih dari satu)')
                            ->with('modal_redirect', 'modal-usr-update')
                            ->with('id_redirect', $user->id_user)
                            ->with('code_alert', 2);
                        }elseif(Auth::user()->part == "Dev"){
                            return redirect()
                            ->route('developer.masters.users.index')
                            ->with('fail','Ubah Pengguna Gagal (User Kepala BPS Jawa Timur tidak boleh lebih dari satu)')
                            ->with('modal_redirect', 'modal-usr-update')
                            ->with('id_redirect', $user->id_user)
                            ->with('code_alert', 2);
                        }
                    }
                }
            }
        }

        //UPDATE DATA
        if($request->password == 'yes') {
            $user->update([
                //'username'=>$request->username,
                //'name'=>$request->name,
                //'email'=>$request->email,
                'password'=>Hash::make('bps3500'),
                'part'=>$request->part,
                //'id_employee'=>$request->id_employee,
            ]);
        }else{
            $user->update([
                //'username'=>$request->username,
                //'name'=>$request->name,
                //'email'=>$request->email,
                'part'=>$request->part,
                //'id_employee'=>$request->id_employee,
            ]);
        }

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Pengguna Berhasil ('.$user->employee->name.')',
        ]);

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()
            ->route('admin.masters.users.index')
            ->with('success','Ubah Pengguna Berhasil')
            ->with('code_alert', 1);
        }elseif(Auth::user()->part == "Dev"){
            return redirect()
            ->route('developer.masters.users.index')
            ->with('success','Ubah Pengguna Berhasil')
            ->with('code_alert', 1);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.users.index';
        }else{
            $redirect_route = 'developer.masters.users.index';
        }

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Pengguna Berhasil ('.$user->employee->name.')',
        ]);

        //DELETE DATA
        Log::where('id_user', $user->id_user)->delete();
        $user->delete();

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()
            ->route('admin.masters.users.index')
            ->with('success','Hapus Pengguna Berhasil')
            ->with('code_alert', 1);
        }elseif(Auth::user()->part == "Dev"){
            return redirect()
            ->route('developer.masters.users.index')
            ->with('success','Hapus Pengguna Berhasil')
            ->with('code_alert', 1);
        }
    }

    public function password($user)
    {
        //SET REDIRECT
        $redirect_route = '';
        if(Auth::user()->part == "Admin"){
            $redirect_route = 'admin.masters.users.index';
        }else{
            $redirect_route = 'developer.masters.users.index';
        }

        //GET USER
        $selected_user = User::where('id_user', $user)->first();

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Reset Password Pengguna Berhasil ('.$selected_user->employee->name.')',
        ]);

        //DELETE DATA
        User::where('id_user', $user)->update([
            'password'=>Hash::make('bps3500'),
        ]);

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()
            ->route('admin.masters.users.index')
            ->with('success','Reset Password Pengguna Berhasil')
            ->with('code_alert', 1);
        }elseif(Auth::user()->part == "Dev"){
            return redirect()
            ->route('developer.masters.users.index')
            ->with('success','Reset Password Pengguna Berhasil')
            ->with('code_alert', 1);
        }
    }
}
