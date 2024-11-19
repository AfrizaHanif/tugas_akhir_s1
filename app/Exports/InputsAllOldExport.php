<?php

namespace App\Exports;

use App\Models\HistoryInput;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InputsAllOldExport implements FromQuery, WithMultipleSheets, WithStrictNullComparison
{
    use Exportable;
    protected $periods;

    public function __construct()
    {
        $this->periods = HistoryInput::select('id_period', 'period_name')->groupBy('id_period', 'period_name')->orderBy('period_year', 'ASC')->orderBy('period_num_month', 'ASC')->get();
    }

    public function query()
    {
        return HistoryInput::query();
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->periods as $period) {
            $id_period = $period->id_period;
            $period_name = $period->period_name;
            $sheets[] = new InputsAllLoopExport($id_period, $period_name);
        }

        return $sheets;
    }
}
