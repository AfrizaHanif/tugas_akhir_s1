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
        Schema::create('sub_criterias', function (Blueprint $table) {
            $table->char('id_sub_criteria', 11)->primary();
            $table->char('id_criteria', 11);
            $table->foreign('id_criteria')->references('id_criteria')->on('criterias');
            $table->string('name', 50);
            $table->double('weight');
            $table->char('attribute', 11);
            $table->smallInteger('level');
            $table->char('need', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_criterias');
    }
};
