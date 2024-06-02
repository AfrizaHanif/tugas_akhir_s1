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
        Schema::create('history_vote_checks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('id_period', 11);
            $table->char('period_name', 20);
            $table->char('id_officer', 11);
            $table->string('officer_name', 50);
            $table->string('officer_department', 50);
            $table->char('id_vote_criteria', 11);
            $table->string('vote_criteria_name', 50);
            $table->char('officer_selected', 11);
            $table->string('officer_selected_name', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_vote_checks');
    }
};
