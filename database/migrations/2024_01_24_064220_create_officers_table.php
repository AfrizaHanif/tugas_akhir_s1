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
        Schema::create('officers', function (Blueprint $table) {
            $table->char('id_officer', 11)->primary();
            //$table->integer('nip_bps')->unique();
            //$table->bigInteger('nip')->unique();
            $table->string('name', 50)->unique();
            //$table->smallInteger('org_code');
            $table->char('id_department', 10);
            $table->foreign('id_department')->references('id_department')->on('departments');
            $table->char('id_part', 10);
            $table->foreign('id_part')->references('id_part')->on('parts');
            //$table->string('status', 10);
            //$table->string('last_group', 7);
            //$table->string('last_education', 20);
            $table->string('place_birth', 30);
            $table->date('date_birth');
            $table->string('gender', 15);
            $table->string('religion', 10);
            //$table->unsignedBigInteger('id_user')->nullable();
            //$table->foreign('id_user')->references('id')->on('users');
            $table->string('photo', 300)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
