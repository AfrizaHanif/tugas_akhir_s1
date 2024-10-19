<?php

namespace App\Exports;

use App\Models\Officer;
use App\Models\Part;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class OfficersExport implements FromQuery, WithHeadings, WithMapping, WithStrictNullComparison
{
    use Exportable;

    public function headings(): array
    {
        return [
            'nip',
            'nama',
            'jabatan',
            'subtim1',
            'subtim2',
            'email',
            'telp',
            'tmplahir',
            'tgllahir',
            'jk',
            'agama',
            'foto',
            'is_hr',
        ];
    }

    public function query()
    {
        return Officer::query()->with('position')
        ->whereDoesntHave('position', function($query){
            $query->where('name', 'Developer');
        })
        ->orderBy('id_officer', 'ASC');
    }

    public function map($data) : array {
        if(!empty(User::where('nip', $data->id_officer)->first()->part)){
            if(User::where('nip', $data->id_officer)->first()->part == 'Admin'){
                $is_hr = 'Ya';
            }else{
                $is_hr = 'Tidak';
            }
        }else{
            $is_hr = 'Tidak';
        }

        return [
            $data->id_officer,
            $data->name,
            $data->position->name,
            $data->subteam_1->name,
            $data->subteam_2->name ?? '',
            $data->email,
            $data->phone,
            $data->place_birth,
            $data->date_birth,
            $data->gender,
            $data->religion,
            $data->photo,
            $is_hr,
        ] ;
    }
}
