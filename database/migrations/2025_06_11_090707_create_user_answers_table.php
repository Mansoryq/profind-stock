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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained('answers')->onDelete('cascade'); // ссылка на таблицу answers (вопросы)
            $table->integer('answer_value'); // значение ответа (1-5)
            $table->integer('response_time_seconds'); // время ответа в секундах
            $table->timestamp('answered_at'); // когда был дан ответ
            $table->string('test_session_id')->nullable(); // ID сессии теста для группировки
            $table->timestamps();
            
            // Индексы для быстрого поиска
            $table->index(['user_id', 'test_session_id']);
            $table->index('answered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
