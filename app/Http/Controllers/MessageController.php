<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        //GET DATA
        $messages = Message::get();

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return view('Pages.Admin.message', compact('messages'));
        }elseif(Auth::user()->part == "Dev"){
            return view('Pages.Developer.message', compact('messages'));
        }else{
            return view('Pages.Officer.message', compact('messages'));
        }
    }

    public function store_in(Request $request)
    {
        //GET ID OFFICER
        //$id_officer = Officer::where('id_officer', $request->id_officer)->first()->id_officer;

        //STORE DATA
        Message::insert([
            //'id_officer'=>$id_officer,
            //'officer_nip'=>$request->officer_nip,
            'officer_name'=>$request->officer_name,
            'message_in'=>$request->message_in,
            'type'=>$request->type,
		]);

        //RETURN TO VIEW
        if(Auth::user()->part == "Admin"){
            return redirect()->route('admin.messages.index')->with('success','Kirim Pesan Berhasil. Terima kasih atas Feedback yang anda berikan kepada Developer.')->with('code_alert', 1);
        }else{
            return redirect()->route('officer.messages.index')->with('success','Kirim Pesan Berhasil. Terima kasih atas Feedback yang anda berikan kepada Developer.')->with('code_alert', 1);
        }
    }

    public function store_out(Request $request, $id)
    {
        //UPDATE DATA
        Message::where('id', $id)->update([
            'message_out'=>$request->message_out,
        ]);

        //RETURN TO VIEW
        return redirect()->route('developer.messages.index')->with('success','Balas Pesan Berhasil')->with('code_alert', 1);
    }

    public function destroy(Message $message)
    {
        //DELETE DATA
        $message->delete();

        //RETURN TO VIEW
        return redirect()->route('developer.messages.index')->with('success','Hapus Pesan Berhasil')->with('code_alert', 1);
    }
}
