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
        $this->periods = HistoryInput::join('periods', 'periods.id_period', '=', 'history_inputs.id_period')->select('periods.id_period', 'periods.name')->groupBy('periods.id_period', 'periods.name')->orderBy('year', 'ASC')->orderBy('periods.num_month', 'ASC')->get();
    }

    public function query()
    {
        return HistoryInput::query();
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->periods as $period) {
            $id_period = $period->period->id_period;
            $period_name = $period->period->name;
            $sheets[] = new InputsAllLoopExport($id_period, $period_name);
        }

        return $sheets;
    }
}
