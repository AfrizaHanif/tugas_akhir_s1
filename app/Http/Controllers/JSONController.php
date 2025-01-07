<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class JSONController extends Controller
{
    public function autocomplete(Request $request)
    {
        $data = Employee::select('name')
        ->where('name','like',"%{$request->search}%")
        ->get();

        return response()->json($data);
    }
}
