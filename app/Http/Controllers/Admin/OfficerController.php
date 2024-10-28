<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OfficersExport;
use App\Http\Controllers\Controller;
use App\Imports\OfficersImport;
use App\Imports\OfficersModalImport;
use App\Imports\UserImport;
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
use Exception;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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
        $team_lists = Team::with('part')->whereNotIn('name', ['Developer', 'Pimpinan BPS'])->get();
        $subteams = SubTeam::with('team')->get();
        $officers = Officer::with('position', 'subteam_1', 'subteam_2')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->get();
        $users = User::get();
        //dd($officers);

        //RETURN TO VIEW
        return view('Pages.Admin.officer', compact('parts', 'parts_2', 'positions', 'teams', 'team_lists', 'subteams', 'officers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){
                return redirect()->route('admin.masters.officers.index')->with('fail','Tidak dapat menambahkan pegawai dikarenakan sedang dalam proses verifikasi nilai.')->with('code_alert', 2)->withInput(['tab_redirect'=>'pills-'.$request->id_part])->with('modal_redirect', 'modal-off-create');
            }
        }

        //COMBINE KODE
        $id_user = IdGenerator::generate([
            'table'=>'users',
            'field'=>'id_user',
            'length'=>7,
            'prefix'=>'USR-',
            'reset_on_prefix_change'=>true,
        ]);
        /*
        $total_id = Officer::
        whereDoesntHave('position', function($query){$query->where('name', 'Developer');})
        ->count();
        $count_id = $total_id += 1;
        $str_id = str_pad($count_id, 3, '0', STR_PAD_LEFT);
        $id_officer = "OFF-".$str_id;
        */
        /*
        $id_officer = IdGenerator::generate([
            'table'=>'officers',
            'field'=>'id_officer',
            'length'=>7,
            'prefix'=>'OFF-',
            'reset_on_prefix_change'=>true,
        ]);
        */
        //$id_officer = 'OFF-'.$request->nip;

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'id_officer' => 'unique:officers',
            'name' => 'unique:officers',
            'email' => 'unique:officers',
            'phone' => 'unique:officers',
        ], [
            'id_officer.unique' => 'NIP tidak boleh sama dengan yang terdaftar',
            'name.unique' => 'Nama telah terdaftar',
            'email.unique' => 'E-Mail telah terdaftar',
            'phone.unique' => 'Nomor telepon telah terdaftar',
        ]);
        if ($validator->fails()) {
            return redirect()
            ->route('admin.masters.officers.index')
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$request->id_part])
            ->with('modal_redirect', 'modal-off-create')
            ->with('id_redirect', $request->id_part)
            ->with('code_alert', 2);
        }

        //CHECK LEAD MORE THAN 1
        $count_lead = Officer::with('position')->whereHas('position', function($query){$query->where('name', 'LIKE', 'Kepala%');})->where('id_position', $request->id_position)->count();
        if(!empty($count_lead)){
            if($count_lead > 0){
                return redirect()
                ->route('admin.masters.officers.index')
                ->with('fail','Kepala BPS Jawa Timur / Bagian Umum tidak boleh lebih dari satu pegawai. Jika dikarenakan pindah kerja, mohon untuk mengubah jabatan dari Kepala BPS Jatim / Bagian Umum sebelumnya, lalu ubah pada Kepala BPS Jatim / Bagian Umum terbaru.')
                ->with('modal_redirect', 'modal-off-create')
                ->withInput(['tab_redirect'=>'pills-'.$request->id_part])
                ->with('code_alert', 2);
            }
        }

        //CHECK SAME TEAM
        if($request->id_sub_team_1 == $request->id_sub_team_2){
            return redirect()
            ->route('admin.masters.officers.index')->with('fail','Tim Utama dan Tim Cadangan tidak boleh sama. Jika hanya satu pegawai, pilih Tidak Ada di Tim Cadangan.')
            ->with('code_alert', 2)->withInput(['tab_redirect'=>'pills-'.$request->id_part])
            ->with('modal_redirect', 'modal-off-create')
            ->with('id_redirect', $request->id_part)
            ->with('code_alert', 2);
        }

        //CHECK IF LEAD
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                $part = 'KBPS';
                $tim_1 = 'STM-001';
                $tim_2 = null;
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
            }else{
                $part = 'Pegawai';
            }
            $tim_1 = $request->id_sub_team_1;
            $tim_2 = $request->id_sub_team_2;
        }

        /*
        $check_lead1 = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        $check_lead2 = Position::where('name', 'LIKE', 'Kepala Bagian Umum%')->where('id_position', $request->id_position)->first();
        $check_sdm = Position::where('name', 'LIKE', '%SDM%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead1->id_position)){
            if($check_lead1->id_position == $request->id_position){
                $part = 'KBPS';
                $tim_1 = 'STM-001';
                $tim_2 = null;
            }
        }elseif(!empty($check_lead2->id_position)){
            if($check_lead2->id_position == $request->id_position){
                $part = 'KBU';
                $tim_1 = 'STM-002';
                $tim_2 = $request->id_sub_team_2;
            }
        }elseif(!empty($check_sdm->id_position)){
            if($check_sdm->id_position == $request->id_position){
                $part = 'Admin';
                $tim_1 = $request->id_sub_team_1;
                $tim_2 = $request->id_sub_team_2;
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
            }else{
                $part = 'Pegawai';
            }
            $part = 'Pegawai';
            $tim_1 = $request->id_sub_team_1;
            $tim_2 = $request->id_sub_team_2;
        }
        */

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
            'id_officer'=>$request->id_officer,
            //'nip'=>$request->nip,
            'name'=>$request->name,
            'id_position'=>$request->id_position,
            'id_sub_team_1'=>$tim_1,
            'id_sub_team_2'=>$tim_2,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'place_birth'=>$request->place_birth,
            'date_birth'=>$request->date_birth,
            'gender'=>$request->gender,
            'religion'=>$request->religion,
            'photo'=>$photo,
            //'is_lead'=>'No',
		]);
        User::insert([
            'id_user'=>$id_user,
            'username'=>$request->id_officer,
            'name'=>$request->name,
            'nip'=>$request->id_officer,
            'password'=>Hash::make('bps3500'),
            'part'=>$part,
        ]);

        //IF LEAD
        /*
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                Officer::where('id_officer', $request->id_officer)->update([
                    //'is_lead'=>'Yes',
                ]);
            }
        }
        */

        //GET FOR REDIRECT
        $tab = Team::with('subteam')
        ->whereHas('subteam', function($query) use($request){$query->where('id_sub_team', $request->id_sub_team_1);})->latest()->first();

        //RETURN TO VIEW
        return redirect()
        ->route('admin.masters.officers.index')
        ->withInput(['tab_redirect'=>'pills-'.$request->id_part, 'sub_tab_redirect'=>$request->id_part.'-'.$tab->id_team.'-tab-pane'])
        ->with('success','Tambah Pegawai Berhasil')
        ->with('code_alert', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Officer $officer)
    {
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

        //COMBINE KODE
        $id_user = IdGenerator::generate([
            'table'=>'users',
            'field'=>'id_user',
            'length'=>7,
            'prefix'=>'USR-',
            'reset_on_prefix_change'=>true,
        ]);

        //CHECK STATUS
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){
                return redirect()
                ->route('admin.masters.officers.index')
                ->with('fail','Tidak dapat mengubah pegawai dikarenakan sedang dalam proses verifikasi nilai.')
                ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
                ->with('modal_redirect', 'modal-off-update')
                ->with('id_redirect', $officer->id_officer)
                ->with('code_alert', 2);
            }
        }

        //VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'id_officer' => [Rule::unique('officers')->ignore($officer),],
            'name' => [Rule::unique('officers')->ignore($officer),]
        ], [
            'id_officer.unique' => 'NIP tidak boleh sama dengan yang terdaftar',
            'name.unique' => 'Nama telah terdaftar',
        ]);
        if ($validator->fails()) {
            return redirect()
            ->route('admin.masters.officers.index')
            ->withErrors($validator)
            ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
            ->with('modal_redirect', 'modal-off-update')
            ->with('id_redirect', $officer->id_officer)
            ->with('code_alert', 2);
        }

        //CHECK LEAD MORE THAN 1
        if($request->id_position != $officer->id_position){
            $count_lead = Officer::with('position')->whereHas('position', function($query){$query->where('name', 'LIKE', 'Kepala%');})->where('id_position', $request->id_position)->count();
            //dd($count_lead);
            if(!empty($count_lead)){
                if($count_lead > 0){
                    return redirect()
                    ->route('admin.masters.officers.index')
                    ->with('fail','Kepala BPS Jawa Timur / Bagian Umum tidak boleh lebih dari satu pegawai. Jika dikarenakan pindah kerja, mohon untuk mengubah jabatan dari Kepala BPS Jatim / Bagian Umum sebelumnya, lalu ubah pada Kepala BPS Jatim / Bagian Umum terbaru.')
                    ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
                    ->with('modal_redirect', 'modal-off-update')
                    ->with('id_redirect', $officer->id_officer)
                    ->with('code_alert', 2);
                }
            }
        }

        //CHECK SAME TEAM
        if($request->id_sub_team_1 == $request->id_sub_team_2){
            return redirect()
            ->route('admin.masters.officers.index')
            ->with('fail','Tim Utama dan Tim Cadangan tidak boleh sama. Jika hanya satu pegawai, pilih Tidak Ada di Tim Cadangan.')
            ->withInput(['tab_redirect'=>'pills-'.$request->id_part])
            ->with('modal_redirect', 'modal-off-update')
            ->with('id_redirect', $officer->id_officer)
            ->with('code_alert', 2);
        }

        //CHECK IF LEAD
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                $part = 'KBPS';
                $tim_1 = 'STM-001';
                $tim_2 = null;
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
            }else{
                $part = 'Pegawai';
            }
            $tim_1 = $request->id_sub_team_1;
            $tim_2 = $request->id_sub_team_2;
        }

        /*
        $check_lead1 = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        $check_lead2 = Position::where('name', 'LIKE', 'Kepala Bagian Umum%')->where('id_position', $request->id_position)->first();
        $check_sdm = Position::where('name', 'LIKE', '%SDM%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead1->id_position)){
            if($check_lead1->id_position == $request->id_position){
                $part = 'KBPS';
                $tim_1 = 'STM-001';
                $tim_2 = null;
            }
        }elseif(!empty($check_lead2->id_position)){
            if($check_lead2->id_position == $request->id_position){
                $part = 'KBU';
                $tim_1 = 'STM-002';
                $tim_2 = $request->id_sub_team_2;
            }
        }elseif(!empty($check_sdm->id_position)){
            if($check_sdm->id_position == $request->id_position){
                $part = 'Admin';
                $tim_1 = $request->id_sub_team_1;
                $tim_2 = $request->id_sub_team_2;
            }
        }else{
            if($request->has('is_hr')){
                $part = 'Admin';
            }else{
                $part = 'Pegawai';
            }
            $part = 'Pegawai';
            $tim_1 = $request->id_sub_team_1;
            $tim_2 = $request->id_sub_team_2;
        }
        */

        //UPDATE DATA
        $officer->update([
            'id_officer'=>$request->id_officer,
            'name'=>$request->name,
            'id_position'=>$request->id_position,
            'id_sub_team_1'=>$tim_1,
            'id_sub_team_2'=>$tim_2,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'place_birth'=>$request->place_birth,
            'date_birth'=>$request->date_birth,
            'gender'=>$request->gender,
            'religion'=>$request->religion,
            //'photo'=>$request->photo,
            //'is_lead'=>'No',
		]);
        if(!empty(User::where('nip', $officer->id_officer)->first())){
            User::where('nip', $officer->id_officer)->update([
                'name'=>$request->name,
                'nip'=>$request->id_officer,
                'part'=>$part,
            ]);
        }else{
            User::insert([
                'id_user'=>$id_user,
                'nip'=>$request->id_officer,
                'name'=>$request->name,
                'username'=>$request->id_officer,
                'password'=>Hash::make('bps3500'),
                'part'=>$part,
            ]);
        }

        //UPDATE IMAGE
        $photo = '';
        $id_officer = Officer::find($officer->id_officer);
        $path_photo = public_path('Images/Portrait/'.$id_officer->photo);
        //dd($path_photo);
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
            $officer->update([
                'photo'=>null,
            ]);
        }

        //IF LEAD
        /*
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $request->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $request->id_position){
                Officer::where('id_officer', $officer->id_officer)->update([
                    //'is_lead'=>'Yes',
                ]);
            }
        }
        */

        //RETURN TO VIEW
        return redirect()
        ->route('admin.masters.officers.index')
        ->with('success','Ubah Pegawai Berhasil')
        ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
        ->with('code_alert', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Officer $officer)
    {
        //LATEST PERIODE
        $latest_per = Period::where('progress_status', 'Scoring')->orWhere('progress_status', 'Verifying')->latest()->first();

        //CHECK STATUS
        if(!empty($latest_per)){
            if($latest_per->progress_status == 'Verifying'){
                return redirect()
                ->route('admin.masters.officers.index')
                ->with('fail','Hapus Pegawai Tidak Berhasil (Proses Verifikasi sedang berjalan)')
                ->with('code_alert', 1);
            }
        }

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

        //DESTROY IMAGE
        $id_officer = Officer::find($officer->id_officer);
        $path_photo = public_path('Images/Portrait/'.$id_officer->photo);
        if(File::exists($path_photo)){
            File::delete($path_photo);
        }

        //DESTROY DATA
        User::where('nip', $officer->id_officer)->delete();
        Input::where('id_officer', $officer->id_officer)->delete();
        $officer->delete();

        //RETURN TO VIEW
        return redirect()
        ->route('admin.masters.officers.index')
        ->withInput(['tab_redirect'=>'pills-'.$redirect->id_part, 'sub_tab_redirect'=>$redirect->id_part.'-'.$tab->id_team.'-tab-pane'])
        ->with('success','Hapus Pegawai Berhasil')
        ->with('code_alert', 1);
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

    public function import(Request $request): RedirectResponse
    {
        //ERASE ALL DATA (RESET ONLY)
        if($request->import_method == 'reset'){
            //DELETE ALL DATA (INPUTS AND OFFICERS)
            DB::statement("SET foreign_key_checks=0");
            Input::truncate();
            //User::whereNot('id_user', 'USR-000')->delete();
            Officer::truncate();
            DB::statement("SET foreign_key_checks=1");

            /*
            //IMPORT FILE
            $import = New OfficersModalImport();
            $import->import($request->file('file'));

            //CHECK IF DUPLICATE
            if ($import->failures()->isNotEmpty()) {
                return redirect()->route('admin.masters.officers.index')
                ->withFailures($import->failures())
                ->with('code_alert', 1);
            }else{
                //RETURN TO VIEW
                return redirect()
                ->route('admin.masters.officers.index')
                ->with('success','Import Pegawai Berhasil')
                ->with('code_alert', 1);
            }
            */
        }

        try{
            //IMPORT FILE (OFFICERS)
            $import_off = New OfficersImport($request->import_method);
            $import_off->import($request->file('file'));

            //DELETE USER
            if($request->import_method == 'reset'){
                User::whereNot('id_user', 'USR-000')->delete();
            }

            //IMPORT FILE (USERS)
            $import_usr = New UserImport($request->import_method);
            $import_usr->import($request->file('file'));

            //RETURN TO VIEW
            if(Auth::user()->part == 'Dev'){
                return redirect()
                ->route('index')
                ->with('success','Import Pegawai Berhasil')
                ->with('code_alert', 1);
            }else{
                if($request->import_method == 'reset'){
                    Session::flush();
                    Auth::logout();
                    //request()->session()->invalidate();
                    //request()->session()->regenerateToken();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()
                    ->route('index')
                    ->with('success','Import Pegawai Berhasil. Demi keamanan, silahkan lakukan login kembali dengan password "bps3500".')
                    ->with('code_alert', 1);
                }else{
                    return redirect()
                    ->route('admin.masters.officers.index')
                    ->with('success','Import Pegawai Berhasil')
                    ->with('code_alert', 1);
                }
            }
        }catch (QueryException $ex){
            //GET ERROR MESSAGE AND INFO
            $message = $ex->getMessage();
            $errorCode = $ex->errorInfo[1];

            //RETURN TO VIEW
            if($errorCode == 1062){
                if(Auth::user()->part == 'Dev'){
                    return redirect()
                    ->route('developer.officers.index')
                    ->with('fail', 'Import Pegawai Gagal. Terdapat duplikat data yang ada di Excel Import Pegawai. Silahkan cek file Excel kembali')
                    ->with('code_alert', 1);
                }else{
                    return redirect()
                    ->route('admin.masters.officers.index')
                    ->with('fail', 'Import Pegawai Gagal. Terdapat duplikat data yang ada di Excel Import Pegawai. Silahkan cek file Excel kembali')
                    ->with('code_alert', 1);
                }
            }elseif($errorCode == 1364){
                if(Auth::user()->part == 'Dev'){
                    return redirect()
                    ->route('developer.officers.index')
                    ->with('fail', 'Import Pegawai Gagal. Terdapat kolom yang tidak ada di Excel. Silahkan cek kebutuhan kolom di Modal Import atau hubungi Developer')
                    ->with('code_alert', 1);
                }else{
                    return redirect()
                    ->route('admin.masters.officers.index')
                    ->with('fail', 'Import Pegawai Gagal. Terdapat kolom yang tidak ada di Excel. Silahkan cek kebutuhan kolom di Modal Import atau hubungi Developer')
                    ->with('code_alert', 1);
                }
            }else{
                if(Auth::user()->part == 'Dev'){
                    return redirect()
                    ->route('developer.officers.index')
                    ->with('fail', $message)
                    ->with('code_alert', 1);
                }else{
                    return redirect()
                    ->route('admin.masters.officers.index')
                    ->with('fail', $message)
                    ->with('code_alert', 1);
                }
            }
        }
    }

    public function export(Request $request)
    {
        //GET EXPORT FILE
        return Excel::download(new OfficersExport, 'OFF-Backup.xlsx');

        //NOTE: NO NEED TO RETURN TO VIEW. LET TOASTS REMIND YOU AFTER EXPORT
    }
}
