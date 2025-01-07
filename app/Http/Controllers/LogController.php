<?php

namespace App\Http\Controllers;

use App\Exports\LogsExport;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LogController extends Controller
{
    public function index()
    {
        //GET DATA
        if(Auth::user()->id_user == 'USR-000'){
            $logs = Log::orderBy('created_at', 'DESC')->get();
        }else{
            $logs = Log::where('id_user', Auth::user()->id_user)->orderBy('created_at', 'DESC')->get();
        }

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin" || Auth::user()->part == "KBPS"){
            return view('Pages.Admin.log', compact('logs'));
        }elseif(Auth::user()->part == "Dev"){
            return view('Pages.Developer.log', compact('logs'));
        }else{
            return view('Pages.Employee.log', compact('logs'));
        }
    }

    public function export()
    {
        //GET DATA
        $current_user = Auth::user()->id_user; //GET CURRENT USER

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Export Logs',
            'progress'=>'View',
            'result'=>'Success',
            'descriptions'=>'Export Logs Berhasil',
        ]);

        //GET EXPORT FILE
        return Excel::download(new LogsExport($current_user), 'LOG-Backup.xlsx');

        //NOTE: NO NEED TO RETURN TO VIEW. LET TOASTS REMIND YOU AFTER EXPORT
    }
}
