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
        Schema::create('criterias', function (Blueprint $table) {
            $table->char('id_criteria', 20)->primary();
            $table->char('id_category', 11);
            $table->foreign('id_category')->references('id_category')->on('categories');
            $table->string('name', 50);
            $table->double('weight');
            $table->char('attribute', 11);
            $table->smallInteger('level');
            $table->smallInteger('max');
            $table->char('need', 10);
            $table->char('source', 25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterias');
    }
};
