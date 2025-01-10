<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Period;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('progress_status', 'Finished')->get();
        $results = Result::with('employee')->orderBy('count', 'DESC')->offset(0)->limit(1)->get();
        $employees = Employee::with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->where('status', 'Active')
        ->get();

        return view('Pages.Employee.result', compact('periods', 'results', 'employees'));
    }
}
