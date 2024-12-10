<?php

namespace App\Imports;

use App\Models\Log;
use App\Models\Position;
use App\Models\SubTeam;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CheckImport implements ToCollection, SkipsEmptyRows, SkipsOnError, WithHeadingRow, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $positions, $subteams, $redirect_route;
    private $check = 0;

    public function __construct($redirect_route)
    {
        $this->positions = Position::get();
        $this->subteams = SubTeam::get();
        $this->redirect_route = $redirect_route;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            foreach($this->positions as $position){
                if($row['jabatan'] != $position->name){
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Pegawai',
                        'progress'=>'Import',
                        'result'=>'Error',
                        'descriptions'=>'Import Pegawai Gagal (Data Pegawai tidak sama dengan yang terdaftar) ('.$row['nama'].') ('.$row['jabatan'].' <=> '.$position->name.')',
                    ]);

                    $this->check = '1';
                    $this->failRedirect();
                }else{
                    $this->check = '0';
                    break;
                }
            }

            foreach($this->subteams as $subteam){
                if($row['subtim1'] != $subteam->name){
                    //CREATE A LOG
                    Log::create([
                        'id_user'=>Auth::user()->id_user,
                        'activity'=>'Pegawai',
                        'progress'=>'Import',
                        'result'=>'Error',
                        'descriptions'=>'Import Pegawai Gagal (Data Tim tidak sama dengan yang terdaftar) ('.$row['nama'].') ('.$row['subtim1'].' <=> '.$subteam->name.')',
                    ]);

                    $this->check = '1';
                    $this->failRedirect();
                }else{
                    $this->check = '0';
                    break;
                }
            }
        }
    }

    public function failRedirect(): int
    {
        return $this->check;
    }
}
