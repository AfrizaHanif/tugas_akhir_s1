<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Officer;
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
        $users = User::whereNot('id_user', 'USR-000')->get();
        //$parts = Part::whereNot('name', 'Developer')->get();
        $subteams = SubTeam::get();
        /*
        $officers = Officer::with('position', 'subteam_1')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('subteam_1', function($query){
            $query->with('team')->whereHas('team', function($query){
                $query->with('part')->whereHas('part', function($query){
                    $query->where('name', 'Tim Teknis');
                });
            });
        })
        ->get();
        */
        $officers = Officer::with('position', 'subteam_1')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();
        $count_kbps = User::where('part', 'KBPS')->count();

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return view('Pages.Admin.user', compact('users', 'officers', 'subteams', 'count_kbps'));
        }elseif(Auth::user()->part == "Dev"){
            return view('Pages.Developer.user', compact('users', 'officers', 'subteams', 'count_kbps'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //GET DATA
        $officer = Officer::where('id_officer', $request->officer)->first();

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
            'id_officer' => 'unique:users',
            'username' => 'unique:users',
            'email' => 'unique:users',
        ], [
            'id_officer.unique' => 'Satu akun hanya dapat digunakan pada satu pegawai. Pegawai tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);
        $validator = Validator::make($request->all(), [
            //'id_officer' => 'unique:users',
            'username' => 'regex:/^\S*$/u|unique:users',
            'name' => 'unique:users',
            //'email' => 'unique:users',
        ], [
            //'id_officer.unique' => 'Satu akun hanya dapat digunakan pada satu pegawai. Pegawai tersebut telah memiliki akun',
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
        $count_kbps = User::where('part', 'KBPS')->count();
        if($request->part == 'KBPS'){
            if(!empty($count_kbps)){
                if($count_kbps >= 1){
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Pengguna',
                        'progress'=>'Create',
                        'result'=>'Error',
                        'descriptions'=>'Tambah Pengguna Tidak Berhasil (Pengguna KBPS telah terdaftar)',
                    ]);

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


        //CHECK IF LEAD
        /*
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $officer->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $officer->id_position){
                $part = 'KBPS';
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
            }else{
                $part = 'Pegawai';
            }
        }
        */

        //STORE DATA
        /*
        User::insert([
            'id_user'=>$id_user,
            'username'=>$request->username,
            'name'=>$request->name,
            //'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'part'=>$request->part,
            //'id_officer'=>$request->id_officer,
		]);
        */
        User::insert([
            'id_user'=>$id_user,
            'username'=>$officer->id_officer,
            'nip'=>$officer->id_officer,
            'name'=>$officer->name,
            //'email'=>$request->email,
            'password'=>Hash::make('bps3500'),
            'part'=>$request->part,
            //'id_officer'=>$request->id_officer,
		]);

        //RETURN TO VIEW
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Pengguna Berhasil ('.$officer->name.')',
        ]);

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
        //VALIDATE DATA
        /*
        $request->validate([
            'id_officer' => [Rule::unique('users')->ignore($user),],
            'username' => [Rule::unique('users')->ignore($user),],
            'email' => [Rule::unique('users')->ignore($user),],
        ], [
            'id_officer.unique' => 'Satu akun hanya dapat digunakan pada satu pegawai. Pegawai tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);
        */
        /*
        $validator = Validator::make($request->all(), [
            //'id_officer' => [Rule::unique('users')->ignore($user),],
            'username' => [Rule::unique('users')->ignore($user),'regex:/^\S*$/u'],
            'name' => [Rule::unique('users')->ignore($user),],
            //'email' => [Rule::unique('users')->ignore($user),],
        ], [
            //'id_officer.unique' => 'Satu akun hanya dapat digunakan pada satu pegawai. Pegawai tersebut telah memiliki akun',
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
        $count_kbps = User::where('part', 'KBPS')->count();
        if($request->part == 'KBPS'){
            if(!empty($count_kbps)){
                if($count_kbps >= 1){
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Pengguna',
                        'progress'=>'Update',
                        'result'=>'Error',
                        'descriptions'=>'Ubah Pengguna Tidak Berhasil (Pengguna KBPS telah terdaftar)',
                    ]);

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

        //UPDATE DATA
        if($request->password == 'yes') {
            $user->update([
                //'username'=>$request->username,
                //'name'=>$request->name,
                //'email'=>$request->email,
                'password'=>Hash::make('bps3500'),
                'part'=>$request->part,
                //'id_officer'=>$request->id_officer,
            ]);
        }else{
            $user->update([
                //'username'=>$request->username,
                //'name'=>$request->name,
                //'email'=>$request->email,
                'part'=>$request->part,
                //'id_officer'=>$request->id_officer,
            ]);
        }

        //RETURN TO VIEW
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Ubah Pengguna Berhasil ('.$user->name.')',
        ]);

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
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Pengguna',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Pengguna Berhasil ('.$user->name.')',
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
}
