<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $this->validate($request, [
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if(is_numeric($request->input('login'))){
            $login_type = 'nip';
        }else{
            $login_type = 'username';
        }
        //dd($login_type);

        $request->merge([
            $login_type => $request->input('login')
        ]);

        //REMEMBER ME
        $remember = $request->has('remember_me') ? true : false;

        if (Auth::attempt($request->only($login_type, 'password'), $remember)) {
            //REGENERATE SESSION
            //dd(auth()->user()->part);
            $request->session()->regenerate();

            //RETURN TO VIEW
            if (Auth::user()->first_time_login) {
                //$first_time_login = true;
                User::where('id_user', Auth::user()->id_user)->update([
                    'first_time_login'=>false
                ]);
                if(auth()->user()->part == "Pegawai"){
                    return redirect()->route('officer')
                    ->withSuccess('Selamat Datang!')
                    ->with('modal_redirect', 'modal-dsh-first');
                }elseif(auth()->user()->part == "Dev"){
                    return redirect()->route('developer')
                    ->withSuccess('Selamat Datang!')
                    ->with('modal_redirect', 'modal-dsh-first');
                }else{
                    return redirect()->route('admin')
                    ->withSuccess('Selamat Datang!')
                    ->with('modal_redirect', 'modal-dsh-first');
                }
            } else {
                //$first_time_login = false;
                if(auth()->user()->part == "Pegawai"){
                    return redirect()->route('officer')->withSuccess('Selamat Datang!');
                }elseif(auth()->user()->part == "Dev"){
                    return redirect()->route('developer')->withSuccess('Selamat Datang!');
                }else{
                    return redirect()->route('admin')->withSuccess('Selamat Datang!');
                }
            }
        }
        return redirect()->route('login')->withErrors([
            'login' => 'Username / NIP yang anda masukkan salah.',
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
