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
        Schema::create('spheres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_kz')->nullable();
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_kz')->nullable();
            $table->text('description_en')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex цвет для UI
            $table->string('icon')->nullable(); // Иконка для сферы
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spheres');
    }
};
