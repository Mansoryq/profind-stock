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
        // Для SQLite нужно пересоздать таблицу с правильными foreign keys
        Schema::dropIfExists('profession_talent');
        
        Schema::create('profession_talent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profession_id')->constrained('professions')->onDelete('cascade');
            $table->foreignId('talent_id')->constrained('talents')->onDelete('cascade');
            $table->decimal('coefficient', 3, 2); // Коэффициент от 0.01 до 9.99
            $table->timestamps();
            
            $table->unique(['profession_id', 'talent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profession_talent');
        
        // Восстанавливаем старую версию с неправильными foreign keys
        Schema::create('profession_talent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profession_id')->constrained()->onDelete('cascade');
            $table->foreignId('talent_id')->constrained()->onDelete('cascade');
            $table->decimal('coefficient', 3, 2);
            $table->timestamps();
            
            $table->unique(['profession_id', 'talent_id']);
        });
    }
};
