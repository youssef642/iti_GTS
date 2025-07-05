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
        Schema::create('job_posts', function (Blueprint $table) {
               $table->id();
           $table->string('title');
    $table->text('description');
    $table->text('requirements')->nullable();
    $table->text('responsibilities')->nullable();
    $table->decimal('min_salary', 10, 2)->nullable();
    $table->decimal('max_salary', 10, 2)->nullable();
    $table->string('location')->nullable();
    $table->string('type')->default('Full Time');
    $table->boolean('is_remote')->default(false);
    $table->string('experience')->nullable();
    $table->boolean('published')->default(true);
    $table->date('expires_at')->nullable();
    $table->string('status')->default('active');
    $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
