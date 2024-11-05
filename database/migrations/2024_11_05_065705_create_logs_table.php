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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->char('id_user', 11);
            $table->char('page', 20);
            $table->char('progress', 15);
            $table->char('result', 15);
            $table->string('descriptions', 200)->nullable();
=======
            $table->string('page', 20);
            $table->string('category', 15);
            $table->string('details', 200)->nullable();
>>>>>>> 72a5ec8aae76a27a257191e2b80824d87045dc00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
