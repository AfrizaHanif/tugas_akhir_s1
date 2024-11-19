<?php

namespace App\Exports;

use App\Models\HistoryInput;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InputsOldExport implements FromQuery, WithHeadings, WithMapping, WithStrictNullComparison
{
    use Exportable;

    protected $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function query()
    {
        return HistoryInput::query()->where('id_period', $this->period);
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Pegawai',
            'Kriteria',
            'Nilai Asli',
            'Nilai Konversi',
        ];
    }

    public function map($data) : array {
        return [
            $data->id_officer,
            $data->officer_name,
            $data->criteria_name,
            $data->input,
            $data->input_raw,
        ] ;
    }

    /*
    public function collection()
    {
        return HistoryInput::all();
    }
        */
}
