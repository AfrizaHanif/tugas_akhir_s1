<?php

namespace App\Exports;

use App\Models\Input;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InputsExport implements FromQuery, FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function query()
    {
        return Input::query()->where('id_period', $this->period);
    }

    public function headings(): array
    {
        return [
            'nip',
            'nama_pegawai',
            'kriteria',
            'input',
        ];
    }

    public function map($data) : array {
        return [
            $data->officer->nip,
            $data->officer->name,
            $data->criteria->name,
            $data->input,
        ] ;
    }

    public function collection()
    {
        return Input::all();
    }
}
