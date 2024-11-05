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
        Schema::create('crips', function (Blueprint $table) {
            $table->char('id_crips', 20)->primary();
            $table->char('id_criteria', 20);
            $table->foreign('id_criteria')->references('id_criteria')->on('criterias');
            $table->string('name', 20);
            //$table->string('description', 50)->nullable();
            $table->char('value_type', 10);
            $table->smallInteger('value_from');
            $table->smallInteger('value_to')->nullable();
            $table->smallInteger('score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crips');
    }
};
