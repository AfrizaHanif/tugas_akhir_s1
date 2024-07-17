<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Part;
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
        $officers = Officer::with('department', 'subteam_1')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->whereDoesntHave('subteam_1', function($query){
            $query->with('team')->whereHas('team', function($query){
                $query->with('part')->whereHas('part', function($query){
                    $query->where('name', 'Tim Teknis');
                });
            });
        })
        ->get();

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return view('Pages.Admin.user', compact('users', 'officers', 'subteams'));
        }elseif(Auth::user()->part == "Dev"){
            return view('Pages.Developer.user', compact('users', 'officers', 'subteams'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        */
        $validator = Validator::make($request->all(), [
            'id_officer' => 'unique:users',
            'username' => 'unique:users',
            'email' => 'unique:users',
        ], [
            'id_officer.unique' => 'Satu akun hanya dapat digunakan pada satu pegawai. Pegawai tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);
        if ($validator->fails()) {
            if(Auth::user()->part == "Admin"){
                return redirect()->route('admin.masters.users.index')->withErrors($validator)->with('modal_redirect', 'modal-usr-create');
            }elseif(Auth::user()->part == "Dev"){
                return redirect()->route('developer.masters.users.index')->withErrors($validator)->with('modal_redirect', 'modal-usr-create');
            }
        }

        //STORE DATA
        User::insert([
            'id_user'=>$id_user,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'part'=>$request->part,
            'id_officer'=>$request->id_officer,
		]);

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()->route('admin.masters.users.index')->with('success','Tambah Pengguna Berhasil')->with('code_alert', 1);
        }elseif(Auth::user()->part == "Dev"){
            return redirect()->route('developer.masters.users.index')->with('success','Tambah Pengguna Berhasil')->with('code_alert', 1);
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
        $validator = Validator::make($request->all(), [
            'id_officer' => [Rule::unique('users')->ignore($user),],
            'username' => [Rule::unique('users')->ignore($user),],
            'email' => [Rule::unique('users')->ignore($user),],
        ], [
            'id_officer.unique' => 'Satu akun hanya dapat digunakan pada satu pegawai. Pegawai tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);
        if ($validator->fails()) {
            if(Auth::user()->part == "Admin"){
                return redirect()->route('admin.masters.users.index')->withErrors($validator)->with('modal_redirect', 'modal-usr-update')->with('id_redirect', $user->id_user);
            }elseif(Auth::user()->part == "Dev"){
                return redirect()->route('developer.masters.users.index')->withErrors($validator)->with('modal_redirect', 'modal-usr-update')->with('id_redirect', $user->id_user);
            }
        }

        //UPDATE DATA
        if($request->filled('password')) {
            $user->update([
                'username'=>$request->username,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'part'=>$request->part,
                'id_officer'=>$request->id_officer,
            ]);
        } else {
            $user->update([
                'username'=>$request->username,
                'email'=>$request->email,
                'part'=>$request->part,
                'id_officer'=>$request->id_officer,
            ]);
        }

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()->route('admin.masters.users.index')->with('success','Ubah Pengguna Berhasil')->with('code_alert', 1);
        }elseif(Auth::user()->part == "Dev"){
            return redirect()->route('developer.masters.users.index')->with('success','Ubah Pengguna Berhasil')->with('code_alert', 1);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //DELETE DATA
        $user->delete();

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()->route('admin.masters.users.index')->with('success','Hapus Pengguna Berhasil')->with('code_alert', 1);
        }elseif(Auth::user()->part == "Dev"){
            return redirect()->route('developer.masters.users.index')->with('success','Hapus Pengguna Berhasil')->with('code_alert', 1);
        }
    }
}
