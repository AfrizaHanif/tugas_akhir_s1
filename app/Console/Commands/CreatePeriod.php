<?php

namespace App\Console\Commands;

use App\Models\Period;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CreatePeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-period';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $now = Carbon::now('Asia/Jakarta');
        $first = $now->firstOfMonth();
        $month = $now->month;
        $year = $now->year;

        if($now != $first){
            $str_month = str_pad($month, 2, '0', STR_PAD_LEFT);
            $str_year = substr($year, -2);
            $id_period = "PRD-".$str_month.'-'.$str_year;

            $name_month = '';
            if($month == 1){
                $name_month = 'Januari';
            }elseif($month == 2){
                $name_month = 'Februari';
            }elseif($month == 3){
                $name_month = 'Maret';
            }elseif($month == 4){
                $name_month = 'April';
            }elseif($month == 5){
                $name_month = 'Mei';
            }elseif($month == 6){
                $name_month = 'Juni';
            }elseif($month == 7){
                $name_month = 'Juli';
            }elseif($month == 8){
                $name_month = 'Agustus';
            }elseif($month == 9){
                $name_month = 'September';
            }elseif($month == 10){
                $name_month = 'Oktober';
            }elseif($month == 11){
                $name_month = 'November';
            }elseif($month == 12){
                $name_month = 'Desember';
            }

            Period::insert([
                'id_period'=>$id_period,
                'name'=>$name_month.' '.$year,
                'month'=>$name_month,
                'year'=>$year,
                'status'=>'Pending',
            ]);
        }
    }
}
