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
        Schema::create('beta_presences', function (Blueprint $table) {
            $table->char('id_presence', 20)->primary();
            $table->char('id_period', 11);
            $table->foreign('id_period')->references('id_period')->on('periods');
            $table->char('id_officer', 11);
            $table->foreign('id_officer')->references('id_officer')->on('officers');
            $table->char('id_sub_criteria', 11);
            $table->foreign('id_sub_criteria')->references('id_sub_criteria')->on('sub_criterias');
            $table->smallInteger('input');
            $table->char('status', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beta_presences');
    }
};
