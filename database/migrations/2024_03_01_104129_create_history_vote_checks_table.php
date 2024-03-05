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
            $table->char('period_name', 20);
            $table->string('officer_name', 50);
            $table->string('officer_selected', 50);
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
