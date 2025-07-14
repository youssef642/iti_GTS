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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('cover_image')->nullable();
            $table->integer('team_size')->nullable();
            $table->date('founded')->nullable();
            $table->string('instagram')->nullable();
            $table->string('about')->nullable();


           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'cover_image',
                'type',
                'team_size',
                'founded',
                'instagram',
                'about',
                'facebook',
                'linkedin',
            ]);
        });
    }
};
