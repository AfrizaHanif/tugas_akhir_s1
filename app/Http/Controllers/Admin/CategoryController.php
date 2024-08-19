<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Criteria;
use App\Models\SubCriteria;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //COMBINE KODE
        /*
        $total_id = Category::count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_category = "CAT-".$str_id;
        */
        $id_category = IdGenerator::generate([
            'table'=>'categories',
            'field'=>'id_category',
            'length'=>7,
            'prefix'=>'CAT-',
            'reset_on_prefix_change'=>true,
        ]);

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => 'unique:categories',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.masters.criterias.index')->withErrors($validator)->with('modal_redirect', 'modal-cat-create');
        }

        //STORE DATA
        Category::insert([
            'id_category'=>$id_category,
            'name'=>$request->name,
            //'type'=>$request->type,
            'source'=>$request->source,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$id_category])->with('success','Tambah Kategori Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('categories')->ignore($category),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.masters.criterias.index')->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$category->id_category])->with('modal_redirect', 'modal-cat-update')->with('id_redirect', $category->id_category);
        }

        //STORE DATA
        $category->update([
            'name'=>$request->name,
            //'type'=>$request->type,
            'source'=>$request->source,
		]);

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$category->id_category])->with('success','Ubah Kategori Berhasil')->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //CHECK DATA
        if(Criteria::where('id_category', $category->id_category)->exists()) {
            return redirect()->route('admin.masters.criterias.index')->withInput(['tab_redirect'=>'pills-'.$category->id_category])->with('fail', 'Hapus Kategori Tidak Berhasil (Terhubung dengan tabel Kriteria)');
        }else{
            //CLEAR
        }

        //DESTROY DATA
        $category->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.criterias.index')->with('success','Hapus Kategori Berhasil')->with('code_alert', 1);
    }
}
