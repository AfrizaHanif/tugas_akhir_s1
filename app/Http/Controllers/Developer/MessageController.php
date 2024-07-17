<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        //GET DATA
        $messages = Message::get();

        //RETURN TO VIEW
        return view('Pages.Developer.message', compact('messages'));
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, Message $message)
    {

    }

    public function destroy(Message $message)
    {

    }
}
