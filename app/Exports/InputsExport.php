<?php

namespace App\Exports;

use App\Models\Input;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InputsExport implements FromQuery, WithHeadings, WithMapping, WithStrictNullComparison
{
    use Exportable;

    protected $latest_per;

    public function __construct($latest_per)
    {
        $this->latest_per = $latest_per->id_period;
    }

    public function query()
    {
        return Input::query()->where('id_period', $this->latest_per);
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Karyawan',
            'Kriteria',
            'Nilai Asli',
            'Nilai Konversi',
        ];
    }

    public function map($data) : array {
        return [
            $data->employee->id_employee,
            $data->employee->name,
            $data->criteria->name,
            $data->input_raw,
            $data->input,
        ] ;
    }

    /*
    public function collection()
    {
        return Input::all();
    }
        */
}
