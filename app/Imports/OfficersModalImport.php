<?php

namespace App\Imports;

use App\Models\Officer;
use App\Models\Position;
use App\Models\SubTeam;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OfficersModalImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $positions, $subteams;

    public function __construct()
    {
        $this->positions = Position::get();
        $this->subteams = SubTeam::get();
    }

    public function model(array $row)
    {
        //GET DATA
        $positions = $this->positions->where('name', $row['jabatan'])->first();
        $subteams1 = $this->subteams->where('name', $row['subtim1'])->first();
        $subteams2 = $this->subteams->where('name', $row['subtim2'])->first();

        //IF LEAD
        $is_lead = 'No';
        $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $positions->id_position)->first();
        if(!empty($check_lead->id_position)){
            if($check_lead->id_position == $positions->id_position){
                $is_lead = 'Yes';
            }
        }

        return new Officer([
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
            'is_lead'=>$is_lead,
            'photo'=>$row['foto'],
        ]);
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

    public function customValidationMessages()
    {
        return [
            '*.nip' => 'NIP tidak boleh sama dengan yang terdaftar',
            '*.nama' => 'Nama telah terdaftar',
            '*.email' => 'E-Mail telah terdaftar',
            '*.telp' => 'Nomor telepon telah terdaftar',
        ];
    }
}
