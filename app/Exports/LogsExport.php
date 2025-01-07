<?php

namespace App\Exports;

use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LogsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $current_user;

    public function __construct($current_user)
    {
        $this->current_user = $current_user;
    }

    public function headings(): array
    {
        return [
            'pengguna',
            'aktivitas',
            'proses',
            'hasil',
            'deskripsi',
            'tanggal',
            'waktu',
        ];
    }

    public function query()
    {
        if($this->current_user != 'USR-000'){
            $return =  Log::query()->where('id_user', $this->current_user);
        }else{
            $return =  Log::query();
        }
        return $return;
    }

    public function map($data) : array {
        $date = Carbon::parse($data->created_at)
        ->locale('id')
        ->settings(['formatFunction' => 'translatedFormat'])
        ->format('d/m/Y');
        $time = Carbon::parse($data->created_at)
        ->locale('id')
        ->settings(['formatFunction' => 'translatedFormat'])
        ->format('h:i:s');
        $user = User::where('id_user', $data->id_user)->first();

        return [
            $user->employee->name,
            $data->activity,
            $data->progress,
            $data->result,
            $data->descriptions,
            $date,
            $time,
        ] ;
    }
}
