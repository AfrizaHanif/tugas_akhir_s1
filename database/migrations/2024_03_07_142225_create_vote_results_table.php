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
        Schema::create('vote_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('id_period', 11);
            $table->foreign('id_period')->references('id_period')->on('periods');
            $table->char('id_officer', 11);
            $table->foreign('id_officer')->references('id_officer')->on('officers');
            $table->char('id_vote_criteria', 11);
            $table->foreign('id_vote_criteria')->references('id_vote_criteria')->on('vote_criterias');
            $table->unsignedSmallInteger('final_vote');
            //$table->decimal('final_score', 8, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_results');
    }
};
