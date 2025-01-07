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
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('id_employee', 11);
            $table->foreign('id_employee')->references('id_employee')->on('employees');
            //$table->char('employee_nip', 11);
            //$table->string('employee_name', 50);
            //$table->string('employee_position', 50);
            $table->char('type', 15);
            $table->string('message_in', 200);
            $table->string('message_out', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
