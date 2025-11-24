<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'answer_value',
        'response_time_seconds',
        'answered_at',
        'test_session_id'
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    /**
     * Связь с сессией теста
     */
    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'test_session_id', 'session_id');
    }

    /**
     * Связь с пользователем
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с вопросом (таблица answers)
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'question_id');
    }

    /**
     * Получить талант через вопрос
     */
    public function talent()
    {
        return $this->question->talent ?? null;
    }

    /**
     * Scope для получения ответов конкретной сессии теста
     */
    public function scopeForTestSession($query, $sessionId)
    {
        return $query->where('test_session_id', $sessionId);
    }

    /**
     * Scope для получения ответов конкретного пользователя
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
