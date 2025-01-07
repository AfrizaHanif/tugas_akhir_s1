<?php

namespace App\Imports;

use App\Models\Position;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PositionImport implements ToCollection, SkipsEmptyRows, SkipsOnError, SkipsOnFailure, WithHeadingRow
{
    use Importable, SkipsErrors, SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            //GENERATE CODE
            $id_position = IdGenerator::generate([
                'table'=>'positions',
                'field'=>'id_position',
                'length'=>7,
                'prefix'=>'POS-',
                'reset_on_prefix_change'=>true,
            ]);

            //UPDATE OR CREATE DATA
            Position::firstOrCreate([
                'name' => $row['nama'],
            ],[
                'id_position' => $id_position,
            ]);
        }
    }

}
