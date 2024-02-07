<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Presence;
use App\Models\Result;
use App\Models\Officer;
use App\Models\Period;
use App\Models\SubCriteria;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = Period::orderBy('id_period', 'ASC')->where('status', 'Finish')->get();
        $results = Result::with('officer')->orderBy('final_score', 'DESC')->get();
        $officers = Officer::with('department')->whereDoesntHave('department', function($query){$query->where('name', 'Developer');})->get();
        return view('Pages.Home.result', compact('periods', 'results', 'officers'));
    }
}
