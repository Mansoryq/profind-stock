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
        Schema::create('specialities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->string('faculty');
            $table->integer('duration_years')->default(4);
            $table->enum('degree_type', ['бакалавр', 'магистр']);
            $table->enum('language', ['казахский', 'русский', 'английский']);
            $table->boolean('is_active')->default(true);
            $table->integer('grant_count')->default(0);
            $table->integer('passing_score')->default(0);
            $table->string('subject_1'); // обязательный предмет 1
            $table->string('subject_2'); // обязательный предмет 2  
            $table->string('subject_3')->nullable(); // предмет по выбору 1
            $table->string('subject_4')->nullable(); // предмет по выбору 2
            $table->string('subject_5')->nullable(); // предмет по выбору 3
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialities');
    }
};
