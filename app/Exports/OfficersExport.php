<?php

namespace App\Exports;

use App\Models\Officer;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OfficersExport implements FromQuery, FromCollection, WithHeadings, WithMapping
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
            'tmplahir',
            'tgllahir',
            'jk',
            'agama',
        ];
    }

    public function query()
    {
        return Officer::query()->with('position')
        ->whereDoesntHave('position', function($query){$query->where('name', 'Developer');});
    }

    public function map($data) : array {
        return [
            $data->nip,
            $data->name,
            $data->position->name,
            $data->subteam_1->name,
            $data->subteam_2->name ?? '',
            $data->place_birth,
            $data->date_birth,
            $data->gender,
            $data->religion,
        ] ;
    }

    public function collection()
    {
        return Officer::all();
    }
}
