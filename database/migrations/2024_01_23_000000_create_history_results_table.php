<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('id_period', 11);
            $table->char('period_month', 10);
            $table->unsignedSmallInteger('period_year');
            $table->char('period_name', 20);
            $table->char('id_officer', 11);
            //$table->bigInteger('officer_nip');
            $table->string('officer_name', 50);
            $table->string('officer_position', 50);
            $table->string('officer_photo', 300)->nullable();
            $table->decimal('final_score', 8, 3);
            //$table->smallInteger('ckp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_results');
    }
};
