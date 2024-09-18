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
        Schema::create('sub_teams', function (Blueprint $table) {
            $table->char('id_sub_team', 20)->primary();
            $table->char('id_team', 20);
            $table->foreign('id_team')->references('id_team')->on('teams');
            $table->string('name', 50);
            //$table->string('description', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_teams');
    }
};
