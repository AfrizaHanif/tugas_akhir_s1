<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Officer;
use App\Models\Part;
use App\Models\SubTeam;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $parts_2 = Part::whereNotIn('name', ['Developer', 'Kepemimpinan'])->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $kbps_positions = Position::whereNot('name', 'Developer')->where('id_position', 'DPT-001')->get();
        $umum_positions = Position::whereNot('name', 'Developer')->whereNot('id_position', 'DPT-001')->get();
        $off_positions = Position::whereNot('name', 'Developer')->whereNotIn('id_position', ['DPT-001', 'DPT-002'])->get();
        $teams = Team::with('part')->get();
        $team_lists = Team::with('part')->whereNotIn('name', ['Developer', 'Pimpinan BPS'])->get();
        $subteams = SubTeam::with('team')->get();
        $officers = Officer::with('position', 'subteam_1', 'subteam_2')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();
        $users = User::get();
        //dd($officers);

        //RETURN TO VIEW
        return view('Pages.Developer.officer', compact('parts', 'parts_2', 'positions', 'kbps_positions', 'umum_positions', 'off_positions', 'teams', 'team_lists', 'subteams', 'officers', 'users'));
    }

    public function search(Request $request)
    {
        //GET DATA
        $search = $request->search;
        $parts = Part::whereNot('name', 'Developer')->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $teams = Team::with('part')->get();
        $subteams = SubTeam::with('team')->get();

        //GET SEARCH QUERY
        $officers = Officer::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->paginate(10);

        //RETURN TO VIEW
        return view('Pages.Developer.officer', compact('parts', 'positions', 'teams', 'subteams', 'officers', 'search'));
    }
}
