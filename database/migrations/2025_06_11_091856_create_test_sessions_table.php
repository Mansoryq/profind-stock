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
        Schema::create('test_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique(); // UUID сессии теста
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Статус оплаты
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'free'])->default('pending');
            $table->decimal('payment_amount', 8, 2)->nullable(); // Сумма оплаты
            $table->string('payment_transaction_id')->nullable(); // ID транзакции
            $table->timestamp('paid_at')->nullable(); // Время оплаты
            
            // Прогресс теста
            $table->integer('total_questions')->default(0); // Общее количество вопросов
            $table->integer('answered_questions')->default(0); // Количество отвеченных вопросов
            $table->decimal('completion_percentage', 5, 2)->default(0); // Процент завершения
            
            // Статус сессии
            $table->enum('status', ['started', 'in_progress', 'completed', 'abandoned'])->default('started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Временные метрики
            $table->integer('total_time_spent')->default(0); // Общее время в секундах
            $table->decimal('average_response_time', 8, 2)->default(0); // Среднее время ответа
            
            $table->timestamps();
            
            // Индексы
            $table->index(['user_id', 'status']);
            $table->index('payment_status');
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_sessions');
    }
};
