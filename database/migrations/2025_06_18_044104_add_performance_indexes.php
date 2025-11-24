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
        // Добавляем индексы для таблицы test_sessions
        Schema::table('test_sessions', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'completed_at']);
            $table->index('session_id');
            $table->index(['status', 'completed_at']);
        });

        // Добавляем индексы для таблицы user_answers
        Schema::table('user_answers', function (Blueprint $table) {
            $table->index(['test_session_id', 'created_at']);
            $table->index('question_id');
        });

        // Добавляем индексы для таблицы answers
        Schema::table('answers', function (Blueprint $table) {
            $table->index('talent_id');
        });

        // Добавляем индексы для таблицы talents
        Schema::table('talents', function (Blueprint $table) {
            $table->index('domain_id');
        });

        // Добавляем индексы для связей many-to-many
        if (Schema::hasTable('profession_talent')) {
            Schema::table('profession_talent', function (Blueprint $table) {
                $table->index('talent_id');
                $table->index('profession_id');
            });
        }

        if (Schema::hasTable('professions')) {
            Schema::table('professions', function (Blueprint $table) {
                $table->index('sphere_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем индексы для таблицы test_sessions
        Schema::table('test_sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['user_id', 'completed_at']);
            $table->dropIndex(['session_id']);
            $table->dropIndex(['status', 'completed_at']);
        });

        // Удаляем индексы для таблицы user_answers
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropIndex(['test_session_id', 'created_at']);
            $table->dropIndex(['question_id']);
        });

        // Удаляем индексы для таблицы answers
        Schema::table('answers', function (Blueprint $table) {
            $table->dropIndex(['talent_id']);
        });

        // Удаляем индексы для таблицы talents
        Schema::table('talents', function (Blueprint $table) {
            $table->dropIndex(['domain_id']);
        });

        // Удаляем индексы для связей many-to-many
        if (Schema::hasTable('profession_talent')) {
            Schema::table('profession_talent', function (Blueprint $table) {
                $table->dropIndex(['talent_id']);
                $table->dropIndex(['profession_id']);
            });
        }

        if (Schema::hasTable('professions')) {
            Schema::table('professions', function (Blueprint $table) {
                $table->dropIndex(['sphere_id']);
            });
        }
    }
};
