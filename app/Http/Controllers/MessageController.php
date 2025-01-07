<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Message;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        //GET DATA
        $messages = Message::get(); //GET MESSAGES

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin" || Auth::user()->part == "KBPS"){
            return view('Pages.Admin.message', compact('messages'));
        }elseif(Auth::user()->part == "Dev"){
            return view('Pages.Developer.message', compact('messages'));
        }else{
            return view('Pages.Employee.message', compact('messages'));
        }
    }

    public function store_in(Request $request)
    {
        //GET ID EMPLOYEE
        $id_employee = Employee::where('id_employee', $request->id_employee)->first()->id_employee;

        //STORE DATA
        Message::insert([
            'id_employee'=>$id_employee,
            //'employee_nip'=>$request->employee_nip,
            //'employee_name'=>$request->employee_name,
            'message_in'=>$request->message_in,
            'type'=>$request->type,
		]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Feedback',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Kirim Pesan Berhasil',
        ]);

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()->route('admin.messages.index')->with('success','Kirim Pesan Berhasil. Terima kasih atas Feedback yang anda berikan kepada Developer.')->with('code_alert', 1);
        }else{
            return redirect()->route('employee.messages.index')->with('success','Kirim Pesan Berhasil. Terima kasih atas Feedback yang anda berikan kepada Developer.')->with('code_alert', 1);
        }
    }

    public function store_out(Request $request, $id)
    {
        //UPDATE DATA
        Message::where('id', $id)->update([
            'message_out'=>$request->message_out,
        ]);

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Feedback',
            'progress'=>'Update',
            'result'=>'Success',
            'descriptions'=>'Balas Pesan Berhasil',
        ]);

        //RETURN TO VIEW
        return redirect()->route('developer.messages.index')->with('success','Balas Pesan Berhasil')->with('code_alert', 1);
    }

    public function destroy(Message $message)
    {
        //DELETE DATA
        $message->delete();

        //CREATE A LOG
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Feedback',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Pesan Berhasil',
        ]);

        //RETURN TO VIEW
        return redirect()->route('developer.messages.index')->with('success','Hapus Pesan Berhasil')->with('code_alert', 1);
    }
}
