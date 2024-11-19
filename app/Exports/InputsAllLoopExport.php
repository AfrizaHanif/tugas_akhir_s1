<?php

namespace App\Exports;

use App\Models\HistoryInput;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InputsAllLoopExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithStrictNullComparison
{
    use Exportable;

    protected $id_period;
    protected $period_name;

    public function __construct($id_period, $period_name)
    {
        $this->id_period = $id_period;
        $this->period_name = $period_name;
    }

    public function query()
    {
        return HistoryInput::query()->where('id_period', $this->id_period);
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

    public function title(): string
    {
        return $this->period_name;
    }
}
