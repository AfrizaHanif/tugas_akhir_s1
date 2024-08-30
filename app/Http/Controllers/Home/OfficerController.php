<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Officer;
use App\Models\Part;
use App\Models\SubTeam;
use App\Models\Team;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $teams = Team::with('part')->get();
        $subteams = SubTeam::with('team')->get();
        $officers = Officer::with('position', 'subteam_1', 'subteam_2')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();

        //RETURN TO VIEW
        return view('Pages.Home.officer', compact('parts', 'positions', 'teams', 'subteams', 'officers'));
    }

    public function search(Request $request)
    {
        //GET DATA
        $search = $request->search;
        $parts = Part::whereNot('name', 'Developer')->get();
        $positions = Position::get();
        $teams = Team::with('part')->get();
        $subteams = SubTeam::with('team')->get();

        //GET SEARCH QUERY
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->paginate(10);

        //RETURN TO DATA
        return view('Pages.Home.officer', compact('search', 'parts', 'positions', 'teams', 'subteams', 'officers'));
    }
}
