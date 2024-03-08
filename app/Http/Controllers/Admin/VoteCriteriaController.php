<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoteCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VoteCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criterias = VoteCriteria::get();
        return view('Pages.Admin.vote-crit', compact('criterias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        $total_id = VoteCriteria::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_vote_criteria = "VCR-".$str_id;

        //VALIDATE DATA
        /*
        $request->validate([
            'name' => 'unique:vote_criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => 'unique:vote_criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('masters.vote-criterias.index')->withErrors($validator)->with('modal_redirect', 'modal-vcr-create');
        }

        //STORE DATA
        VoteCriteria::insert([
            'id_vote_criteria'=>$id_vote_criteria,
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.vote-criterias.index')->with('success','Tambah Kriteria Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VoteCriteria $votecriteria)
    {
        //VALIDATE DATA
        /*
        $request->validate([
            'name' => [Rule::unique('vote_criterias')->ignore($votecriteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('vote_criterias')->ignore($votecriteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        if ($validator->fails()) {
            return redirect()->route('masters.vote-criterias.index')->withErrors($validator)->with('modal_redirect', 'modal-vcr-update')->with('id_redirect', $votecriteria->id_vote_criteria);
        }

        //STORE DATA
        $votecriteria->update([
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.vote-criterias.index')->with('success','Ubah Kriteria Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VoteCriteria $votecriteria)
    {
        //DESTROY DATA
        $votecriteria->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.vote-criterias.index')->with('success','Hapus Kriteria Berhasil')->with('code_alert', 1);
    }
}
