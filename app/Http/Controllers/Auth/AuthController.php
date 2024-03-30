<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()){
            if(Auth::user()->part != "Pegawai"){
                return view('Pages.Admin.dashboard');
            }else{
                return view('Pages.Officer.dashboard');
            }
        }
        else{
            return view('Pages.Auth.login');
        }
    }

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
        //$remember_me = $request->has('remember_me') ? true : false;
        //dd($credentials); //Check Bug
        if (Auth::attempt($credentials)) {
            //dd(auth()->user()->part);
            $request->session()->regenerate();
            if(auth()->user()->part == "Pegawai"){
                return redirect()->route('officer')->withSuccess('Selamat Datang!');
            }else{
                return redirect()->route('admin')->withSuccess('Selamat Datang!');
            }
        }
        return redirect()->route('login')->withErrors([
            'email' => 'E-Mail yang anda masukkan salah.',
            'password' => 'Password yang anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(){
        auth()->logout();
        //Auth::logout();
        //request()->session()->flush();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('index')->withSuccess('Keluar Berhasil.');
    }
}
