<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNot('id_user', 'USR-000')->get();
        $officers = Officer::with('department')->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})->get();
        return view('Pages.Admin.user', compact('users', 'officers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $total_id = User::whereNot('id_user', 'USR-000')->count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_user = "USR-".$str_id;

        //VALIDATE DATA
        $request->validate([
            'id_officer' => 'unique:users',
            'username' => 'unique:users',
            'email' => 'unique:users',
        ], [
            'id_officer.unique' => 'Satu akun hanya dapat digunakan pada satu pegawai. Pegawai tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);

        //STORE DATA
        User::insert([
            'id_user'=>$id_user,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>$request->password,
            'part'=>$request->part,
            'id_officer'=>$request->id_officer,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.users.index')->with('success','Tambah Pengguna Berhasil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //VALIDATE DATA
        $request->validate([
            'id_officer' => [Rule::unique('users')->ignore($user),],
            'username' => [Rule::unique('users')->ignore($user),],
            'email' => [Rule::unique('users')->ignore($user),],
        ], [
            'id_officer.unique' => 'Satu akun hanya dapat digunakan pada satu pegawai. Pegawai tersebut telah memiliki akun',
            'username.unique' => 'Username tidak boleh sama dengan yang terdaftar',
            'email.unique' => 'E-Mail tidak boleh sama dengan yang terdaftar',
        ]);

        //UPDATE DATA
        $user->update([
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>$request->password,
            'part'=>$request->part,
            'id_officer'=>$request->id_officer,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.users.index')->with('success','Ubah Pengguna Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //DELETE DATA
        $user->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.users.index')->with('success','Hapus Pengguna Berhasil');
    }
}
