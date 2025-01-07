<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->part != "Karyawan" && Auth::user()->part != "Dev") {
            //FUTURE DEVELOPMENT
            if(Auth::check() && Auth::user()->force_logout == true){
                User::where('id_user', Auth::user()->id_user)->update(['force_logout' => false]);
                Session::flush();
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return redirect()
                ->route('index')
                ->with('success','Anda telah dikeluarkan secara otomatis dari sistem. Silahkan login kembali.')
                ->with('code_alert', 1);
            }
            return $next($request);
        }

        return redirect('/');
    }
}
