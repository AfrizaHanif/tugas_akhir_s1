<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Period;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('progress_status', 'Finished')->get();
        $results = Result::with('officer')->orderBy('count', 'DESC')->offset(0)->limit(1)->get();
        $officers = Officer::with('position')->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})->get();

        return view('Pages.Officer.result', compact('periods', 'results', 'officers'));
    }
}
