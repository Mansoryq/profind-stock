<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $question
 * @property string $question_kz
 * @property int $talent_id
 * @property int $intellect_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Talent $talent
 * @property-read Intellect $intellect
 * @property-read string $localizedQuestion
 */
class Answer extends Model
{
    protected $fillable = [
        'question',
        'question_kz',
        'talent_id',
        'intellect_id'
    ];

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function intellect(): BelongsTo
    {
        return $this->belongsTo(Intellect::class);
    }

    public function getLocalizedQuestionAttribute(): string
    {
        $locale = app()->getLocale();

        return match($locale) {
            'kk' => $this->question_kz ?? $this->question,
            default => $this->question
        };
    }
}
