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
        $table = Schema::table('students', function (Blueprint $table) {
            $table->string('duration_track')->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('age')->nullable();
            $table->string('phone')->nullable();

          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
