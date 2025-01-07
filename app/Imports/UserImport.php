<?php

namespace App\Imports;

use App\Models\Log;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Collection;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $positions, $import_method;

    public function __construct($import_method)
    {
        $this->positions = Position::get();
        $this->import_method = $import_method;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row){
            //GET DATA
            $positions = $this->positions->where('name', $row['jabatan'])->first();

            //CHECK IF LEAD
            if(!empty($positions->id_position)){
                $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $positions->id_position)->first();
                if(!empty($check_lead->id_position)){
                    if($check_lead->id_position == $positions->id_position){
                        $part = 'KBPS';
                    }
                }else{
                    if($row['is_hr'] == 'Ya'){
                        $part = 'Admin';
                    }else{
                        $part = 'Karyawan';
                    }
                }
            }

            /*
            $check_lead1 = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $positions->id_position)->first();
            $check_lead2 = Position::where('name', 'LIKE', 'Kepala Bagian Umum%')->where('id_position', $positions->id_position)->first();
            $check_sdm = Position::where('name', 'LIKE', '%SDM%')->where('id_position', $positions->id_position)->first();
            if(!empty($check_lead1->id_position)){
                if($check_lead1->id_position == $positions->id_position){
                    $part = 'KBPS';
                }
            }elseif(!empty($check_lead2->id_position)){
                if($check_lead2->id_position == $positions->id_position){
                    $part = 'KBU';
                }
            }elseif(!empty($check_sdm->id_position)){
                if($check_sdm->id_position == $positions->id_position){
                    $part = 'Admin';
                }
            }else{
                $part = 'Karyawan';
            }
            */

            //GENERATE ID
            $id_user = IdGenerator::generate([
                'table'=>'users',
                'field'=>'id_user',
                'length'=>7,
                'prefix'=>'USR-',
                'reset_on_prefix_change'=>true,
            ]);

            if($this->import_method == 'reset'){
                if(!empty($positions->id_position)){
                    User::insert([
                        'id_user'=>$id_user,
                        'id_employee'=>$row['nip'],
                        'username'=>$row['nip'],
                        //'name'=>$row['nama'],
                        //'nip'=>$row['nip'],
                        'password'=>Hash::make('bps3500'),
                        'part'=>$part,
                    ]);
                }else{
                    Log::create([
                        'id_user'=>'System',
                        'activity'=>'Pengguna',
                        'progress'=>'Create',
                        'result'=>'Error',
                        'descriptions'=>'Tambah Pengguna Gagal (Terdapat proses penambahan karyawan melalui import yang gagal.) ('.$row['nama'].')',
                    ]);
                }
            }else{
                if(!empty($positions->id_position)){
                    User::firstOrCreate([
                        //'nip'=>$row['nip'],
                        'id_employee'=>$row['nip'],
                    ],[
                        'id_user'=>$id_user,
                        'username'=>$row['nip'],
                        //'name'=>$row['nama'],
                        'password'=>Hash::make('bps3500'),
                        'part'=>$part,
                    ]);
                }else{
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Pengguna',
                        'progress'=>'Create',
                        'result'=>'Error',
                        'descriptions'=>'Tambah Pengguna Gagal (Terdapat proses penambahan karyawan melalui import yang gagal.) ('.$row['nama'].')',
                    ]);
                }
            }
        }
    }

    public function rules(): array
    {
        return [

        ];
    }
}
