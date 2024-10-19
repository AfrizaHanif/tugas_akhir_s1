<?php

namespace App\Imports;

use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Collection;
use Haruncpi\LaravelIdGenerator\IdGenerator;
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
            $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $positions->id_position)->first();
            if(!empty($check_lead->id_position)){
                if($check_lead->id_position == $positions->id_position){
                    $part = 'KBPS';
                }
            }else{
                if($row['is_hr'] == 'Ya'){
                    $part = 'Admin';
                }else{
                    $part = 'Pegawai';
                }
            }

            //GENERATE ID
            $id_user = IdGenerator::generate([
                'table'=>'users',
                'field'=>'id_user',
                'length'=>7,
                'prefix'=>'USR-',
                'reset_on_prefix_change'=>true,
            ]);

            if($this->import_method == 'reset'){
                User::insert([
                    'id_user'=>$id_user,
                    'username'=>$row['nip'],
                    'name'=>$row['nama'],
                    'nip'=>$row['nip'],
                    'password'=>'$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
                    'part'=>$part,
                ]);
            }else{
                User::firstOrCreate([
                    'nip'=>$row['nip'],
                ],[
                    'id_user'=>$id_user,
                    'username'=>$row['nip'],
                    'name'=>$row['nama'],
                    'password'=>'$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
                    'part'=>$part,
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [

        ];
    }
}
