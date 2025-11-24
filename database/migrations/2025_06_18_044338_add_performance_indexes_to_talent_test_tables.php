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
        // Добавляем индексы для оптимизации запросов тестирования талантов
        
        // Индексы для test_sessions
        Schema::table('test_sessions', function (Blueprint $table) {
            if (!$this->indexExists('test_sessions', 'test_sessions_user_id_status_index')) {
                $table->index(['user_id', 'status']);
            }
            if (!$this->indexExists('test_sessions', 'test_sessions_user_id_completed_at_index')) {
                $table->index(['user_id', 'completed_at']);
            }
            if (!$this->indexExists('test_sessions', 'test_sessions_session_id_index')) {
                $table->index('session_id');
            }
        });
        
        // Индексы для user_answers
        Schema::table('user_answers', function (Blueprint $table) {
            if (!$this->indexExists('user_answers', 'user_answers_test_session_id_question_id_index')) {
                $table->index(['test_session_id', 'question_id']);
            }
            if (!$this->indexExists('user_answers', 'user_answers_question_id_index')) {
                $table->index('question_id');
            }
        });
        
        // Индексы для answers
        Schema::table('answers', function (Blueprint $table) {
            if (!$this->indexExists('answers', 'answers_talent_id_index')) {
                $table->index('talent_id');
            }
        });
        
        // Индексы для talents
        Schema::table('talents', function (Blueprint $table) {
            if (!$this->indexExists('talents', 'talents_domain_id_index')) {
                $table->index('domain_id');
            }
        });
        
        // Индексы для profession_talent pivot table (если существует)
        if (Schema::hasTable('profession_talent')) {
            Schema::table('profession_talent', function (Blueprint $table) {
                if (!$this->indexExists('profession_talent', 'profession_talent_profession_id_index')) {
                    $table->index('profession_id');
                }
                if (!$this->indexExists('profession_talent', 'profession_talent_talent_id_index')) {
                    $table->index('talent_id');
                }
                if (!$this->indexExists('profession_talent', 'profession_talent_profession_id_talent_id_index')) {
                    $table->index(['profession_id', 'talent_id']);
                }
            });
        }
        
        // Индексы для professions
        Schema::table('professions', function (Blueprint $table) {
            if (!$this->indexExists('professions', 'professions_sphere_id_index')) {
                $table->index('sphere_id');
            }
        });
    }
    
    /**
     * Проверяет существование индекса
     */
    private function indexExists($table, $indexName)
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        if ($connection->getDriverName() === 'sqlite') {
            // Для SQLite проверяем через sqlite_master
            $indexes = $connection->select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name=? AND name=?", [$table, $indexName]);
            return count($indexes) > 0;
        } else {
            // Для MySQL
            $indexes = $connection->select("SELECT * FROM information_schema.statistics WHERE table_schema=? AND table_name=? AND index_name=?", [$databaseName, $table, $indexName]);
            return count($indexes) > 0;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем добавленные индексы
        
        Schema::table('test_sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['user_id', 'completed_at']);
            $table->dropIndex(['session_id']);
        });
        
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropIndex(['test_session_id', 'question_id']);
            $table->dropIndex(['question_id']);
        });
        
        Schema::table('answers', function (Blueprint $table) {
            $table->dropIndex(['talent_id']);
        });
        
        Schema::table('talents', function (Blueprint $table) {
            $table->dropIndex(['domain_id']);
        });
        
        if (Schema::hasTable('profession_talent')) {
            Schema::table('profession_talent', function (Blueprint $table) {
                $table->dropIndex(['profession_id']);
                $table->dropIndex(['talent_id']);
                $table->dropIndex(['profession_id', 'talent_id']);
            });
        }
        
        Schema::table('professions', function (Blueprint $table) {
            $table->dropIndex(['sphere_id']);
        });
    }
};
