<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OfficersExport;
use App\Http\Controllers\Controller;
use App\Imports\OfficersImport;
use App\Models\Position;
use App\Models\Input;
use App\Models\Officer;
use App\Models\Part;
use App\Models\Performance;
use App\Models\Period;
use App\Models\Presence;
use App\Models\SubTeam;
use App\Models\Team;
use App\Models\User;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //GET DATA
        $parts = Part::whereNot('name', 'Developer')->get();
        $parts_2 = Part::whereNotIn('name', ['Developer', 'Kepemimpinan'])->get();
        $positions = Position::whereNot('name', 'Developer')->get();
        $teams = Team::with('part')->get();
        $subteams = SubTeam::with('team')->get();
        $officers = Officer::with('position', 'subteam_1', 'subteam_2')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();
        //dd($officers);

        //RETURN TO VIEW
        return view('Pages.Admin.officer', compact('parts', 'parts_2', 'positions', 'teams', 'subteams', 'officers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //CHECK STATUS
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Validating')->latest()->first();
        if(!empty($latest_per)){
            if($latest_per->status == 'Validating'){
                return redirect()->route('admin.masters.officers.index')->with('fail','Tidak dapat menambahkan pegawai dikarenakan sedang dalam proses validasi nilai.')->with('code_alert', 1)->withInput(['tab_redirect'=>'pills-'.$request->id_part])->with('modal_redirect', 'modal-off-create');
            }
        }

        //COMBINE KODE
        /*
        $total_id = Officer::
        whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_officer = "OFF-".$str_id;
        */
        $id_officer = IdGenerator::generate([
            'table'=>'officers',
            'field'=>'id_officer',
            'length'=>7,
            'prefix'=>'OFF-',
            'reset_on_prefix_change'=>true,
        ]);

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'nip' => 'unique:officers',
            'name' => 'unique:officers',
        ], [
            'nip.unique' => 'NIP tidak boleh sama dengan yang terdaftar',
            'name.unique' => 'Nama telah terdaftar',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.masters.officers.index')->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$request->id_part])->with('modal_redirect', 'modal-off-create');
        }

        //CHECK LEAD MORE THAN 1
        $count_lead = Officer::with('position')->whereHas('position', function($query){$query->where('name', 'LIKE', 'Kepala%');})->where('id_position', $request->id_position)->count();
        if(!empty($count_lead)){
            if($count_lead > 0){
                return redirect()->route('admin.masters.officers.index')->with('fail','Kepala BPS Jawa Timur / Bagian Umum tidak boleh lebih dari satu pegawai. Jika dikarenakan pindah kerja, mohon untuk mengubah jabatan dari Kepala BPS Jatim / Bagian Umum sebelumnya, lalu ubah pada Kepala BPS Jatim / Bagian Umum terbaru.')->with('code_alert', 1)->withInput(['tab_redirect'=>'pills-'.$request->id_part])->with('modal_redirect', 'modal-off-create');
            }
        }

        //CHECK SAME TEAM
        if($request->id_sub_team_1 == $request->id_sub_team_2){
            return redirect()->route('admin.masters.officers.index')->with('fail','Tim Utama dan Tim Cadangan tidak boleh sama. Jika hanya satu pegawai, pilih Tidak Ada di Tim Cadangan.')->with('code_alert', 1)->withInput(['tab_redirect'=>'pills-'.$request->id_part])->with('modal_redirect', 'modal-off-create');
        }

        //UPLOAD PHOTO
        $photo = '';
        if($request->photo){
            $photo = 'IMG-'.$request->id_officer.'.'.$request->photo->extension();
            $request->photo->move(public_path('Images/Portrait/'), $photo);
        }else{
            //SKIP
        }

        //STORE DATA
        Officer::insert([
            'id_officer'=>$id_officer,
            'nip'=>$request->nip,
            'name'=>$request->name,
            'id_position'=>$request->id_position,
            'id_sub_team_1'=>$request->id_sub_team_1,
            'id_sub_team_2'=>$request->id_sub_team_2,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'place_birth'=>$request->place_birth,
            'date_birth'=>$request->date_birth,
            'gender'=>$request->gender,
            'religion'=>$request->religion,
            'photo'=>$photo,
            'is_lead'=>'No',
		]);

        //IF LEAD
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                Officer::where('id_officer', $id_officer)->update([
                    'is_lead'=>'Yes',
                ]);
            }
        }

        //GET FOR REDIRECT
        $tab = Team::with('subteam')
        ->whereHas('subteam', function($query) use($request){$query->where('id_sub_team', $request->id_sub_team_1);})->latest()->first();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'sub_tab_redirect'=>$request->id_part.'-'.$tab->id_team.'-tab-pane'])->with('success','Tambah Pegawai Berhasil')->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Officer $officer)
    {
        //CHECK STATUS
        $latest_per = Period::where('status', 'Scoring')->orWhere('status', 'Validating')->latest()->first();
        if(!empty($latest_per)){
            if($latest_per->status == 'Validating'){
                return redirect()->route('admin.masters.officers.index')->with('fail','Tidak dapat mengubah pegawai dikarenakan sedang dalam proses validasi nilai.')->with('code_alert', 1)->withInput(['tab_redirect'=>'pills-'.$officer->id_part])->with('modal_redirect', 'modal-off-create');
            }
        }

        //GET FOR REDIRECT
        $redirect = Part::with('team')
        ->whereHas('team', function($query) use($request){
            $query->with('subteam')->whereHas('subteam', function($query) use($request){
                $query->where('id_sub_team', $request->id_sub_team_1);
            });
        })->first();
        $tab = Team::with('subteam')
        ->whereHas('subteam', function($query) use($request){
            $query->where('id_sub_team', $request->id_sub_team_1);
        })->latest()->first();
        //dd($redirect->id_part);

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'nip' => [Rule::unique('officers')->ignore($officer),],
            'name' => [Rule::unique('officers')->ignore($officer),]
        ], [
            'nip.unique' => 'NIP tidak boleh sama dengan yang terdaftar',
            'name.unique' => 'Nama telah terdaftar',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.masters.officers.index')->withErrors($validator)->withInput(['tab_redirect'=>'pills-'.$officer->id_part])->with('modal_redirect', 'modal-off-update')->with('id_redirect', $officer->id_officer);
        }

        //CHECK LEAD MORE THAN 1
        if($request->id_position != $officer->id_position){
            $count_lead = Officer::with('position')->whereHas('position', function($query){$query->where('name', 'LIKE', 'Kepala%');})->where('id_position', $request->id_position)->count();
            //dd($count_lead);
            if(!empty($count_lead)){
                if($count_lead > 0){
                    return redirect()->route('admin.masters.officers.index')->with('fail','Kepala BPS Jawa Timur / Bagian Umum tidak boleh lebih dari satu pegawai. Jika dikarenakan pindah kerja, mohon untuk mengubah jabatan dari Kepala BPS Jatim / Bagian Umum sebelumnya, lalu ubah pada Kepala BPS Jatim / Bagian Umum terbaru.')->with('code_alert', 1)->withInput(['tab_redirect'=>'pills-'.$request->id_part])->with('modal_redirect', 'modal-off-create');
                }
            }
        }

        //CHECK SAME TEAM
        if($request->id_sub_team_1 == $request->id_sub_team_2){
            return redirect()->route('admin.masters.officers.index')->with('fail','Tim Utama dan Tim Cadangan tidak boleh sama. Jika hanya satu pegawai, pilih Tidak Ada di Tim Cadangan.')->with('code_alert', 1)->withInput(['tab_redirect'=>'pills-'.$request->id_part])->with('modal_redirect', 'modal-off-create');
        }

        //UPDATE DATA
        $officer->update([
            'nip'=>$request->nip,
            'name'=>$request->name,
            'id_position'=>$request->id_position,
            'id_sub_team_1'=>$request->id_sub_team_1,
            'id_sub_team_2'=>$request->id_sub_team_2,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'place_birth'=>$request->place_birth,
            'date_birth'=>$request->date_birth,
            'gender'=>$request->gender,
            'religion'=>$request->religion,
            'is_lead'=>'No',
		]);

        //UPDATE IMAGE
        $photo = '';
        $id_officer = Officer::find($officer->id_officer);
        $path_photo = public_path('Images/Portrait/'.$id_officer->photo);
        if($request->hasFile('photo')){
            File::delete($path_photo);
            $photo = 'IMG-'.$officer->id_officer.'.'.$request->photo->extension();
            $request->photo->move(public_path('Images/Portrait/'), $photo);
            $officer->update([
                'photo'=>$request['image'] = $photo,
            ]);
        }
        if($request->has('photo_erase')){
            File::delete($path_photo);
        }

        //IF LEAD
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                Officer::where('id_officer', $officer->id_officer)->update([
                    'is_lead'=>'Yes',
                ]);
            }
        }

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Ubah Pegawai Berhasil')->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Officer $officer)
    {
        //GET FOR REDIRECT
        $redirect = Part::with('team')
        ->whereHas('team', function($query) use($officer){
            $query->with('subteam')->whereHas('subteam', function($query) use($officer){
                $query->where('id_sub_team', $officer->id_sub_team_1);
            });
        })->first();
        $tab = Team::with('subteam')
        ->whereHas('subteam', function($query) use($officer){
            $query->where('id_sub_team', $officer->id_sub_team_1);
        })->latest()->first();

        //CHECK DATA
        if(Input::where('id_officer', $officer->id_officer)->exists()) {
            return redirect()->route('admin.masters.officers.index')->with('fail', 'Hapus Pegawai Tidak Berhasil (Terhubung dengan tabel Input)')->with('code_alert', 1);
        }elseif(User::where('id_officer', $officer->id_officer)->exists()){
            return redirect()->route('admin.masters.officers.index')->with('fail', 'Hapus Pegawai Tidak Berhasil (Terhubung dengan tabel Pengguna / Akun)')->with('code_alert', 1);
        }else{
            //CLEAR
        }

        //DESTROY IMAGE
        $id_officer = Officer::find($officer->id_officer);
        $path_photo = public_path('Images/Portrait/'.$id_officer->photo);
        if(File::exists($path_photo)){
            File::delete($path_photo);
        }

        //DESTROY DATA
        $officer->delete();

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])->with('success','Hapus Pegawai Berhasil')->with('code_alert', 1);
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
        return view('Pages.Admin.officer', compact('parts', 'positions', 'teams', 'subteams', 'officers', 'search'));
    }

    public function import(Request $request)
    {
        //IMPORT FILE
        Excel::import(new OfficersImport, $request->file('file'));

        //RETURN TO VIEW
        return redirect()->route('admin.masters.officers.index')->with('success','Import Pegawai Berhasil')->with('code_alert', 1);
    }

    public function export(Request $request)
    {
        //GET EXPORT FILE
        return Excel::download(new OfficersExport, 'OFF-Backup.xlsx');

        //NOTE: NO NEED TO RETURN TO VIEW. LET TOASTS REMIND YOU AFTER EXPORT
    }
}
