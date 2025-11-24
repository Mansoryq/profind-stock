<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Для SQLite нужно пересоздать таблицу
        Schema::dropIfExists('answers_temp');
        
        // Создаем временную таблицу без колонок value и order
        Schema::create('answers_temp', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->unsignedBigInteger('talent_id');
            $table->timestamps();
            
            $table->foreign('talent_id')->references('id')->on('talents')->onDelete('cascade');
        });
        
        // Копируем данные
        DB::statement('INSERT INTO answers_temp (id, question, talent_id, created_at, updated_at) SELECT id, question, talent_id, created_at, updated_at FROM answers');
        
        // Удаляем старую таблицу
        Schema::dropIfExists('answers');
        
        // Переименовываем новую таблицу
        Schema::rename('answers_temp', 'answers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Для отката добавляем колонки обратно
        Schema::table('answers', function (Blueprint $table) {
            $table->integer('value')->default(0);
            $table->integer('order')->default(0);
        });
    }
};
