<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $completed_at
 */
class TestSession extends Model
{
    protected $fillable = [
        "session_id",
        "user_id",
        "payment_status",
        "payment_amount",
        "payment_transaction_id",
        "receipt_image",
        "payer_name",
        "promo_code",
        "payment_confirmed_at",
        "selected_plan",
        "total_questions",
        "answered_questions",
        "completion_percentage",
        "status",
        "started_at",
        "completed_at",
        "total_time_spent",
        "average_response_time",
        "order_id",
        "order_password",
    ];

    protected $casts = [
        "payment_confirmed_at" => "datetime",
        "started_at" => "datetime",
        "completed_at" => "datetime",
        "payment_amount" => "decimal:2",
        "completion_percentage" => "decimal:2",
        "average_response_time" => "decimal:2",
    ];

    /**
     * Связь с пользователем
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с ответами пользователя
     */
    public function userAnswers(): HasMany
    {
        return $this->hasMany(
            UserAnswer::class,
            "test_session_id",
            "session_id"
        );
    }

    /**
     * Обновить прогресс сессии
     */
    public function updateProgress(): void
    {
        $answeredCount = $this->userAnswers()->count();
        $this->update([
            "answered_questions" => $answeredCount,
            "completion_percentage" =>
                $this->total_questions > 0
                    ? round(($answeredCount / $this->total_questions) * 100, 2)
                    : 0,
            "status" => $answeredCount > 0 ? "in_progress" : "started",
        ]);

        // Если все вопросы отвечены, помечаем как завершенную
        if (
            $answeredCount >= $this->total_questions &&
            $this->total_questions > 0
        ) {
            $this->update([
                "status" => "completed",
                "completed_at" => now(),
            ]);
        }
    }

    /**
     * Обновить временные метрики
     */
    public function updateTimeMetrics(): void
    {
        $answers = $this->userAnswers;
        if ($answers->count() > 0) {
            $totalTime = $answers->sum("response_time_seconds");
            $averageTime = $answers->avg("response_time_seconds");

            $this->update([
                "total_time_spent" => $totalTime,
                "average_response_time" => round($averageTime, 2),
            ]);
        }
    }

    /**
     * Проверить, оплачена ли сессия
     */
    public function isPaid(): bool
    {
        return in_array($this->payment_status, ["completed", "free"]);
    }

    /**
     * Получить статус прогресса
     */
    public function getProgressStatusAttribute(): string
    {
        if ($this->completion_percentage == 0) {
            return "Не начат";
        } elseif ($this->completion_percentage < 100) {
            return "В процессе (" . $this->completion_percentage . "%)";
        } else {
            return "Завершен";
        }
    }

    /**
     * Scope для оплаченных сессий
     */
    public function scopePaid($query)
    {
        return $query->whereIn("payment_status", ["completed", "free"]);
    }

    /**
     * Scope для завершенных сессий
     */
    public function scopeCompleted($query)
    {
        return $query->where("status", "completed");
    }
}
