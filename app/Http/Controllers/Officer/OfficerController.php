<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Officer;
use App\Models\Part;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        $parts = Part::whereNot('name', 'Developer')->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();
        return view('Pages.Officer.officer', compact('parts', 'positions', 'officers'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $parts = Part::whereNot('name', 'Developer')->get();
        $positions = Position::get();
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->paginate(10);
        return view('Pages.Officer.officer', compact('parts', 'positions', 'officers', 'search'));
    }
}
