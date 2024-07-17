<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Officer;
use App\Models\Part;
use App\Models\SubTeam;
use App\Models\Team;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $departments = Department::whereNot('name', 'Developer')->get();
        $teams = Team::with('part')->get();
        $subteams = SubTeam::with('team')->get();
        $officers = Officer::with('department', 'subteam_1', 'subteam_2')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->get();
        //dd($officers);

        //RETURN TO VIEW
        return view('Pages.Developer.officer', compact('parts', 'departments', 'teams', 'subteams', 'officers'));
    }

    public function search(Request $request)
    {
        //GET DATA
        $search = $request->search;
        $parts = Part::whereNot('name', 'Developer')->get();
        $departments = Department::whereNot('name', 'Developer')->get();
        $teams = Team::with('part')->get();
        $subteams = SubTeam::with('team')->get();

        //GET SEARCH QUERY
        $officers = Officer::with('department')
        ->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->paginate(10);

        //RETURN TO VIEW
        return view('Pages.Developer.officer', compact('parts', 'departments', 'teams', 'subteams', 'officers', 'search'));
    }
}
