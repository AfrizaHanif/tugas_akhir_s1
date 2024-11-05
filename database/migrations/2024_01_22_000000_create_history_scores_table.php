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
        Schema::create('history_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('id_period', 11);
            $table->char('period_name', 20);
            $table->char('period_month', 10);
            $table->unsignedSmallInteger('period_num_month');
            $table->unsignedSmallInteger('period_year');
            $table->char('id_officer', 11);
            //$table->bigInteger('officer_nip');
            $table->string('officer_name', 50);
            $table->string('officer_position', 50);
            $table->char('id_team', 20);
            $table->string('team_name', 50);
            $table->char('id_sub_team', 20);
            $table->string('sub_team_1_name', 50);
            $table->string('sub_team_2_name', 50)->nullable();
            $table->decimal('final_score', 8, 3);
            $table->smallInteger('second_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_scores');
    }
};
