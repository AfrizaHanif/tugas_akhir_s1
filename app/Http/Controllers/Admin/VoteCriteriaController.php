<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoteCriteria;
use Illuminate\Http\Request;
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
        $request->validate([
            'name' => 'unique:criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        //STORE DATA
        VoteCriteria::insert([
            'id_vote_criteria'=>$id_vote_criteria,
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.vote-criterias.index')->with('success','Tambah Kriteria Berhasil');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VoteCriteria $votecriteria)
    {
        //VALIDATE DATA
        $request->validate([
            'name' => [Rule::unique('vote-criterias')->ignore($votecriteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);

        //STORE DATA
        $votecriteria->update([
            'name'=>$request->name,
		]);

        //RETURN TO VIEW
        return redirect()->route('masters.vote-criterias.index')->with('success','Ubah Kriteria Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VoteCriteria $votecriteria)
    {
        //DESTROY DATA
        $votecriteria->delete();

        //RETURN TO VIEW
        return redirect()->route('masters.vote-criterias.index')->with('success','Hapus Kriteria Berhasil');
    }
}
