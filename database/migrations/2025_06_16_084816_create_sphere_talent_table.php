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
        Schema::create('sphere_talent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sphere_id')->constrained('spheres')->onDelete('cascade');
            $table->foreignId('talent_id')->constrained('talents')->onDelete('cascade');
            $table->decimal('coefficient', 3, 2); // Коэффициент от 0.01 до 9.99
            $table->timestamps();
            
            $table->unique(['sphere_id', 'talent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sphere_talent');
    }
};
