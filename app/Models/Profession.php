<?php

namespace App\Models;

use Google\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $name_kz
 * @property string $description
 * @property string $description_kz
 * @property int $rating
 * @property int $man
 * @property int $woman
 * @property-read string $localizedName
 * @property-read string $localizedDescription
 * @property-read Intellect[]|Collection $intellects
 * @property-read Talent[]|Collection $talents
 */
class Profession extends Model
{
    protected $fillable = [
        'name',
        'name_kz',
        'description',
        'description_kz',
        'sphere_id',
        'is_active',
        'rating',
        'man',
        'woman'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Получить сферу, к которой принадлежит профессия
     */
    public function sphere(): BelongsTo
    {
        return $this->belongsTo(Sphere::class);
    }

    /**
     * Получить таланты, связанные с профессией
     */
    public function talents(): BelongsToMany
    {
        return $this->belongsToMany(Talent::class)
                    ->withPivot('coefficient')
                    ->withTimestamps();
    }

    public function intellects(): BelongsToMany
    {
        return $this->belongsToMany(
            Intellect::class,
            'profession_talent',
        )
            ->withPivot('coefficient')
            ->withTimestamps();
    }

    /**
     * Scope для активных профессий
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Получить локализованное название
     */
    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();

        return match($locale) {
            'kk' => $this->name_kz ?? $this->name,
            default => $this->name
        };
    }

    /**
     * Получить локализованное описание
     */
    public function getLocalizedDescriptionAttribute()
    {
        $locale = app()->getLocale();

        return match($locale) {
            'kk' => $this->description_kz ?? $this->description,
            default => $this->description
        };
    }
}
