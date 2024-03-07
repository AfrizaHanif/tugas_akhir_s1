<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;

class JSONController extends Controller
{
    public function autocomplete(Request $request)
    {
        $data = Officer::select('name')
        ->where('name','like',"%{$request->search}%")
        ->get();

        return response()->json($data);
    }
}
