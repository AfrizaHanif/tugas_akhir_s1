<?php

namespace App\Exports;

use App\Models\HistoryInput;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InputsOldExport implements FromQuery, FromCollection, WithHeadings, WithMapping
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
            'Input',
        ];
    }

    public function map($data) : array {
        return [
            $data->officer_nip,
            $data->officer_name,
            $data->criteria_name,
            $data->input,
        ] ;
    }

    public function collection()
    {
        return HistoryInput::all();
    }
}
