<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Category;
use App\Models\Crips;
use App\Models\Period;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //GET DATA
        $categories = Category::get();
        $criterias = Criteria::get();
        $crips = Crips::orderBy('value_from', 'ASC')->get();
        //dd($crips);

        //RETURN TO VIEW
        return view('Pages.Admin.criteria', compact('categories', 'criterias', 'crips'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //CHECK STATUS
        $latest_per = Period::latest();
        if($latest_per->status == 'Validating'){
            return redirect()->route('admin.masters.officers.index')->with('fail','Tidak dapat menambahkan pegawai dikarenakan sedang dalam proses validasi nilai.')->with('code_alert', 1)->withInput(['tab_redirect'=>'pills-'.$request->id_category])->with('modal_redirect', 'modal-crt-create');
        }

        //COMBINE KODE
        /*
        $total_id = Criteria::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        */
        $str_cat = substr($request->id_category, 4); //CRT-000-xxx (CAT-CRT)
        $id_criteria = IdGenerator::generate([
            'table'=>'criterias',
            'field'=>'id_criteria',
            'length'=>7,
            //'length'=>11,
            'prefix'=>'CRT-',
            //'prefix'=>'CRT-'.$str_cat.'-',
            'reset_on_prefix_change'=>true,
        ]);
        dd($id_criteria);
        //$id_criteria = $gen_cri.'-'.$str_cat;

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => 'unique:criterias',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.masters.criterias.index')->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$request->id_category])->with('modal_redirect', 'modal-crt-create');
        }

        //CONVERT TO PERCENTAGE
        $weight = $request->weight / 100;

        //MODIFY SOURCE TEXT
        $str_src_trim = trim($request->source);
        $str_src_replace = str_replace(' ', '_', $str_src_trim);
        $source = strtolower($str_src_replace);

        //STORE DATA
        Criteria::insert([
            'id_criteria'=>$id_criteria,
            'id_category'=>$request->id_category,
            'name'=>$request->name,
            'weight'=>$weight,
            'attribute'=>$request->attribute,
            'level'=>$request->level,
            'need'=>$request->need,
            'source'=>$source,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$request->id_category])->with('success','Tambah Kriteria Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Criteria $criteria)
    {
        //CHECK STATUS
        $latest_per = Period::latest();
        if($latest_per->status == 'Validating'){
            return redirect()->route('admin.masters.officers.index')->with('fail','Tidak dapat menambahkan pegawai dikarenakan sedang dalam proses validasi nilai.')->with('code_alert', 1)->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])->with('modal_redirect', 'modal-crt-update');
        }

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('criterias')->ignore($criteria),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.masters.criterias.index')->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])->with('modal_redirect', 'modal-crt-update')->with('id_redirect', $criteria->id_category);
        }

        //CONVERT TO PERCENTAGE
        $weight = $request->weight / 100;

        //MODIFY SOURCE TEXT
        $str_src_trim = trim($request->source);
        $str_src_replace = str_replace(' ', '_', $str_src_trim);
        $source = strtolower($str_src_replace);

        //STORE DATA
        $criteria->update([
            'name'=>$request->name,
            'weight'=>$weight,
            'attribute'=>$request->attribute,
            'level'=>$request->level,
            'need'=>$request->need,
            'source'=>$source,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])->with('success','Ubah Kriteria Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criteria $criteria)
    {
        //DESTROY DATA
        $criteria->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$criteria->id_category])->with('success','Hapus Kriteria Berhasil')->with('code_alert', 1);
    }
}
