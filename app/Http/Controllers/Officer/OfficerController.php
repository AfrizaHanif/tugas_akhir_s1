<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Officer;
use App\Models\Part;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        $parts = Part::whereNot('name', 'Developer')->get();
        $departments = Department::whereNot('name', 'Developer')->get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();
        return view('Pages.Officer.officer', compact('parts', 'departments', 'officers'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $parts = Part::whereNot('name', 'Developer')->get();
        $departments = Department::get();
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->paginate(10);
        return view('Pages.Officer.officer', compact('parts', 'departments', 'officers', 'search'));
    }
}
