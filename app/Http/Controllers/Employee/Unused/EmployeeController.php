<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Part;
use App\Models\SubTeam;
use App\Models\Team;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $parts_2 = Part::whereNotIn('name', ['Developer', 'Kepemimpinan'])->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $teams = Team::with('part')->get();
        $team_lists = Team::with('part')->whereNotIn('name', ['Developer', 'Pimpinan BPS'])->get();
        $subteams = SubTeam::with('team')->get();
        $employees = Employee::with('position', 'subteam_1', 'subteam_2')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('status', 'Active')
        ->get();
        //dd($employees);

        //RETURN TO VIEW
        return view('Pages.Employee.employee', compact('parts', 'parts_2', 'positions', 'teams', 'team_lists', 'subteams', 'employees'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $parts = Part::whereNot('name', 'Developer')->get();
        $positions = Position::get();
        $employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('name','like',"%".$search."%")
        ->where('status', 'Active')
        ->paginate(10);
        return view('Pages.Employee.employee', compact('parts', 'positions', 'employees', 'search'));
    }
}
