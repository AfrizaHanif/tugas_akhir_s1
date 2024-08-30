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
        Schema::create('history_input_raws', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('id_period', 11);
            $table->char('period_name', 20);
            $table->char('id_officer', 11);
            $table->bigInteger('officer_nip');
            $table->string('officer_name', 50);
            $table->string('officer_position', 50);
            $table->char('id_category', 11);
            $table->string('category_name', 20);
            $table->char('id_criteria', 20);
            $table->string('criteria_name', 50);
            $table->double('weight');
            $table->char('attribute', 11);
            $table->smallInteger('level');
            $table->smallInteger('max');
            $table->char('is_lead', 10);
            $table->smallInteger('input');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_input_raws');
    }
};
