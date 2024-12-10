<?php

namespace App\Imports;

use App\Models\Log;
use App\Models\Position;
use App\Models\Officer;
use App\Models\SubTeam;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OfficersImport implements ToCollection, SkipsEmptyRows, SkipsOnError, WithHeadingRow, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $positions, $subteams, $import_method;

    public function __construct($import_method)
    {
        $this->positions = Position::get();
        $this->subteams = SubTeam::get();
        $this->import_method = $import_method;
    }

    public function collection(Collection $rows)
    {
        //VALIDATE DATA
        /*
        Validator::make($rows->toArray(), [
            '*.nip' => ['unique:officers,id_officer'],
            '*.nama' => ['unique:officers,name'],
            '*.email' => ['unique:officers,email'],
            '*.telp' => ['unique:officers,phone'],
        ])->validate();
        */

        foreach ($rows as $row)
        {
            //GET DATA
            $positions = $this->positions->where('name', $row['jabatan'])->first();
            $subteams1 = $this->subteams->where('name', $row['subtim1'])->first();
            $subteams2 = $this->subteams->where('name', $row['subtim2'])->first();

            //IF LEAD
            /*
            //$is_lead = 'No';
            $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $positions->id_position)->first();
            if(!empty($check_lead->id_position)){
                if($check_lead->id_position == $positions->id_position){
                    //$is_lead = 'Yes';
                }
            }
            */

            if($this->import_method == 'reset'){
                //IMPORT DATA
                if(!empty($positions->id_position) && !empty($subteams1->id_sub_team)){
                    Officer::insert([
                        'id_officer' => $row['nip'],
                        'name'=>$row['nama'],
                        'id_position'=>$positions->id_position,
                        'id_sub_team_1'=>$subteams1->id_sub_team,
                        'id_sub_team_2'=>$subteams2->id_sub_team ?? null,
                        'place_birth'=>$row['tmplahir'],
                        'date_birth'=>$row['tgllahir'],
                        'email'=>$row['email'],
                        'phone'=>$row['telp'],
                        'gender'=>$row['jk'],
                        'religion'=>$row['agama'],
                        //'is_lead'=>$is_lead,
                        'photo'=>$row['foto'],
                    ]);
                }else{
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>'System',
                        'activity'=>'Pegawai',
                        'progress'=>'Import',
                        'result'=>'Error',
                        'descriptions'=>'Import Pegawai Gagal (Data Jabatan / Tim tidak sama dengan yang terdaftar) ('.$row['nama'].') (Jabatan: '.$row['jabatan'].') (Tim: '.$row['subtim1'].')',
                    ]);
                }
            }else{
                //IMPORT DATA
                if(!empty($positions->id_position) && !empty($subteams1->id_sub_team)){
                    Officer::updateOrInsert([
                        'id_officer' => $row['nip'],
                        'name'=>$row['nama'],
                        'email'=>$row['email'],
                        'phone'=>$row['telp'],
                    ],[
                        'id_officer' => $row['nip'],
                        'name'=>$row['nama'],
                        'id_position'=>$positions->id_position,
                        'id_sub_team_1'=>$subteams1->id_sub_team,
                        'id_sub_team_2'=>$subteams2->id_sub_team ?? null,
                        'email'=>$row['email'],
                        'phone'=>$row['telp'],
                        'place_birth'=>$row['tmplahir'],
                        'date_birth'=>$row['tgllahir'],
                        'gender'=>$row['jk'],
                        'religion'=>$row['agama'],
                        //'is_lead'=>$is_lead,
                        'photo'=>$row['foto'],
                    ]);
                }else{
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Pegawai',
                        'progress'=>'Import',
                        'result'=>'Error',
                        'descriptions'=>'Import Pegawai Gagal (Data Jabatan / Tim tidak sama dengan yang terdaftar) ('.$row['nama'].') (Jabatan: '.$row['jabatan'].') (Tim: '.$row['subtim1'].')',
                    ]);
                }
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.nip' => ['unique:officers,id_officer'],
            '*.nama' => ['unique:officers,name'],
            '*.email' => ['unique:officers,email'],
            '*.telp' => ['unique:officers,phone'],
        ];
    }
}
