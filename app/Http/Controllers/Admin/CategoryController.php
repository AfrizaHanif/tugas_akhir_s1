<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Crips;
use App\Models\Criteria;
use App\Models\Input;
use App\Models\Log;
use App\Models\Period;
use App\Models\SubCriteria;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Kategori',
                    'progress'=>'Create',
                    'result'=>'Error',
                    'descriptions'=>'Tambah Kategori Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tidak dapat melakukan penambahan kategori dikarenakan sedang dalam proses verifikasi nilai.')
                ->with('code_alert', 1);
            }
        }

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => 'unique:categories',
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Kategori',
                'progress'=>'Create',
                'result'=>'Error',
                'descriptions'=>'Tambah Kategori Tidak Berhasil (Beberapa Data Telah Terdaftar di Database)',
            ]);

            return redirect()
            ->route('admin.masters.criterias.index')
            ->withErrors($validator)
            ->with('modal_redirect', 'modal-cat-create');
        }

        //STORE DATA
        Category::insert([
            'id_category'=>$id_category,
            'name'=>$request->name,
            //'type'=>$request->type,
            'source'=>$request->source,
		]);

        //RETURN TO VIEW
        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Kategori',
            'progress'=>'Create',
            'result'=>'Success',
            'descriptions'=>'Tambah Kategori Berhasil ('.$request->name.')',
        ]);

        if(!empty($latest_per) && $latest_per->progress_status == 'Scoring'){
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withInput(['tab_redirect'=>'pills-'.$id_category])
            ->with('success','Tambah Kategori Berhasil. Jika diperlukan, silahkan lakukan Import ulang')
            ->with('code_alert', 1);
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withInput(['tab_redirect'=>'pills-'.$id_category])
            ->with('success','Tambah Kategori Berhasil')
            ->with('code_alert', 1);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Kategori',
                    'progress'=>'Update',
                    'result'=>'Error',
                    'descriptions'=>'Ubah Kategori Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                return redirect()
                ->route('admin.masters.criterias.index')
                ->withInput(['tab_redirect'=>'pills-'.$category->id_category])
                ->with('fail','Tidak dapat melakukan perubahan kategori dikarenakan sedang dalam proses verifikasi nilai.')
                ->with('code_alert', 1);
            }
        }

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique('categories')->ignore($category),],
        ], [
            'name.unique' => 'Nama telah terdaftar sebelumnya',
        ]);
        if ($validator->fails()) {
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Kategori',
                'progress'=>'Update',
                'result'=>'Error',
                'descriptions'=>'Ubah Kategori Tidak Berhasil (Beberapa Data Telah Terdaftar di Database)',
            ]);

            return redirect()
            ->route('admin.masters.criterias.index')
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$category->id_category])
            ->with('modal_redirect', 'modal-cat-update')
            ->with('id_redirect', $category->id_category);
        }

        //STORE DATA
        $category->update([
            'name'=>$request->name,
            //'type'=>$request->type,
            'source'=>$request->source,
		]);

        //RETURN TO VIEW
        if(!empty($latest_per) && $latest_per->progress_status == 'Scoring'){
            Log::create([
                'id_user'=>Auth::user()->id_user,
                'activity'=>'Kategori',
                'progress'=>'Update',
                'result'=>'Success',
                'descriptions'=>'Ubah Kategori Berhasil ('.$request->name.')',
            ]);

            return redirect()
            ->route('admin.masters.criterias.index')
            ->withInput(['tab_redirect'=>'pills-'.$category->id_category])
            ->with('success','Ubah Kategori Berhasil. Jika diperlukan, silahkan lakukan Import ulang.')
            ->with('code_alert', 1);
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withInput(['tab_redirect'=>'pills-'.$category->id_category])
            ->with('success','Ubah Kategori Berhasil')
            ->with('code_alert', 1);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){
                Log::create([
                    'id_user'=>Auth::user()->id_user,
                    'activity'=>'Kategori',
                    'progress'=>'Delete',
                    'result'=>'Error',
                    'descriptions'=>'Hapus Kategori Tidak Berhasil (Proses Verifikasi Sedang Berjalan)',
                ]);

                return redirect()
                ->route('admin.masters.criterias.index')
                ->with('fail','Tidak dapat melakukan penghapusan kategori dikarenakan sedang dalam proses verifikasi nilai.')
                ->with('code_alert', 1);
            }
        }

        //CHECK DATA
        /*
        if(Criteria::where('id_category', $category->id_category)->exists()) {
            return redirect()
            ->route('admin.masters.criterias.index')
            ->withInput(['tab_redirect'=>'pills-'.$category->id_category])
            ->with('fail', 'Hapus Kategori Tidak Berhasil (Untuk keamanan, silahkan hapus kriteria terlebih dahulu.)');
        }else{
            //CLEAR
        }
            */

        //DESTROY DATA
        $loop_criteria = Criteria::where('id_category', $category->id_category)->get();
        foreach($loop_criteria as $criteria){
            Crips::where('id_criteria', $criteria->id_criteria)->delete();
            Input::where('id_criteria', $criteria->id_criteria)->delete();
        }

        Log::create([
            'id_user'=>Auth::user()->id_user,
            'activity'=>'Kriteria',
            'progress'=>'Delete',
            'result'=>'Success',
            'descriptions'=>'Hapus Kriteria Berhasil ('.$category->name.')',
        ]);

        Criteria::where('id_category', $category->id_category)->delete();
        $category->delete();

        //RETURN TO VIEW
        if(!empty($latest_per) && $latest_per->progress_status == 'Scoring'){
            return redirect()
            ->route('admin.masters.criterias.index')
            ->with('success','Hapus Kategori Berhasil. Jika diperlukan, silahkan lakukan Import ulang')
            ->with('code_alert', 1);
        }else{
            return redirect()
            ->route('admin.masters.criterias.index')
            ->with('success','Hapus Kategori Berhasil')
            ->with('code_alert', 1);
        }
    }
}
