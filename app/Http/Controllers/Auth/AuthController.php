<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        //RETURN TO VIEW
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
        //GET AND VALIDATE LOGIN INFO
        $credentials = $request->validate([
            'username' => ['required'],
            //'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        //dd($credentials); //Check Bug

        //REMEMBER ME
        $remember = $request->has('remember_me') ? true : false;

        if (Auth::attempt($credentials, $remember)) {
            //REGENERATE SESSION
            //dd(auth()->user()->part);
            $request->session()->regenerate();

            //RETURN TO VIEW
            if(auth()->user()->part == "Pegawai"){
                return redirect()->route('officer')->withSuccess('Selamat Datang!');
            }elseif(auth()->user()->part == "Dev"){
                return redirect()->route('developer')->withSuccess('Selamat Datang!');
            }else{
                return redirect()->route('admin')->withSuccess('Selamat Datang!');
            }
        }
        return redirect()->route('login')->withErrors([
            'username' => 'User Name yang anda masukkan salah.',
            //'email' => 'E-Mail yang anda masukkan salah.',
            'password' => 'Password yang anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(){
        //LOGOUT ACCOUNT
        //auth()->logout();
        Auth::logout();
        //request()->session()->flush();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        //RETURN TO VIEW
        return redirect()->route('index')->withSuccess('Keluar Berhasil.');
    }
}
