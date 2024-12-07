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
        Schema::create('users', function (Blueprint $table) {
            //$table->id();
            $table->char('id_user', 11)->primary();
            $table->char('nip', 11)->unique();
            //$table->foreign('id_officer')->references('id_officer')->on('officers');
            $table->string('username', 20)->unique();
            $table->string('name', 50)->unique();
            //$table->string('email', 30)->unique();
            //$table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('part', 30);
            $table->boolean('first_time_login')->default(true);
            $table->boolean('force_logout')->default(false); //FUTURE DEVELOPMENT
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
