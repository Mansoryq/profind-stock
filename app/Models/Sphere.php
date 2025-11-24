<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read string $localizedName
 * @property-read string $localizedDescription
 * @property-read Profession[]|Collection $professions
 */
class Sphere extends Model
{
    protected $fillable = [
        'name',
        'name_kz',
        'name_en',
        'description',
        'description_kz',
        'description_en',
        'color',
        'icon',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Получить профессии, принадлежащие этой сфере
     */
    public function professions(): HasMany
    {
        return $this->hasMany(Profession::class);
    }

    /**
     * Получить активные профессии
     */
    public function activeProfessions(): HasMany
    {
        return $this->professions()->where('is_active', true);
    }

    /**
     * Получить таланты, связанные со сферой
     */
    public function talents(): BelongsToMany
    {
        return $this->belongsToMany(Talent::class)
                    ->withPivot('coefficient')
                    ->withTimestamps();
    }

    /**
     * Scope для активных сфер
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope для сортировки
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Получить локализованное название
     */
    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();

        return match($locale) {
            'kk' => $this->name_kz ?? $this->name,
            'en' => $this->name_en ?? $this->name,
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
            'en' => $this->description_en ?? $this->description,
            default => $this->description
        };
    }

    public function favouritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, FavoriteSphereUser::class);
    }
}
