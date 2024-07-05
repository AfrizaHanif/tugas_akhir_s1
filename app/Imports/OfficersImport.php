<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Officer;
use App\Models\SubTeam;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;

class OfficersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $departments, $subteams;

    public function __construct()
    {
        $this->departments = Department::get();
        $this->subteams = SubTeam::get();
    }

    public function rules(): array
    {
        return [
            '*.nip' => ['unique:officers,nip'],
            '*.name' => ['unique:officers,name'],
        ];
    }

    public function customValidationMessages()
    {
    return [
        '*.nip' => 'NIP tidak boleh sama',
        '*.name' => 'Nama Pegawai tidak boleh sama',
    ];
    }

    public function model(array $row)
    {
        //COMBINE KODE
        /*
        $total_id = Officer::
        whereDoesntHave('department', function($query){$query->where('name', 'Developer');})
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

        $departments = $this->departments->where('name', $row['jabatan'])->first();
        $subteams1 = $this->subteams->where('name', $row['subtim1'])->first();
        $subteams2 = $this->subteams->where('name', $row['subtim2'])->first();

        return new Officer([
            'id_officer'=>$id_officer,
            'nip'=>$row['nip'],
            'name'=>$row['nama'],
            'id_department'=>$departments->id_department,
            'id_sub_team_1'=>$subteams1->id_sub_team,
            'id_sub_team_2'=>$subteams2->id_sub_team ?? null,
            'place_birth'=>$row['tmplahir'],
            'date_birth'=>$row['tgllahir'],
            'gender'=>$row['jk'],
            'religion'=>$row['agama'],
            'is_lead'=>'No',
            'photo'=>'',
        ]);
    }
}
