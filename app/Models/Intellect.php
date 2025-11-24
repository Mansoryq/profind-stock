<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $name_kz
 * @property string $description
 * @property string $description_kz
 * @property-read string $localizedName
 * @property-read string $localizedDescription
 */
class Intellect extends Model
{
    protected $table = 'intellects';

    protected $fillable = [
        'name',
        'name_kz',
        'description',
        'description_kz',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return match($locale) {
            'kk' => $this->name_kz ?? $this->name,
            default => $this->name
        };
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        $locale = app()->getLocale();

        return match($locale) {
            'kk' => $this->description_kz ?? $this->description,
            default => $this->description
        };
    }
}
