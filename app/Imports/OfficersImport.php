<?php

namespace App\Imports;

use App\Models\Position;
use App\Models\Officer;
use App\Models\SubTeam;
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

class OfficersImport implements ToCollection, SkipsEmptyRows, SkipsOnError, SkipsOnFailure, WithHeadingRow
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $positions, $subteams;

    public function __construct()
    {
        $this->positions = Position::get();
        $this->subteams = SubTeam::get();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $id_officer = IdGenerator::generate([
                'table'=>'officers',
                'field'=>'id_officer',
                'length'=>7,
                'prefix'=>'OFF-',
                'reset_on_prefix_change'=>true,
            ]);

            $positions = $this->positions->where('name', $row['jabatan'])->first();
            $subteams1 = $this->subteams->where('name', $row['subtim1'])->first();
            $subteams2 = $this->subteams->where('name', $row['subtim2'])->first();

            //IF LEAD
            $check_lead = Position::where('name', 'LIKE', 'Kepala BPS%')->where('id_position', $positions->id_position)->first();
            $is_lead = '';
            if(!empty($check_lead->id_position)){
                if($check_lead->id_position == $positions->id_position){
                    $is_lead = 'Yes';
                }else{
                    $is_lead = 'No';
                }
            }

            //IMPORT DATA
            Officer::updateOrCreate([
                'nip'=>$row['nip'],
            ],[
                'id_officer' => $id_officer,
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
    }
}
