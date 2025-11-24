<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $name_kz
 * @property string $description
 * @property string $description_kz
 * @property string $short_description
 * @property string $short_description_kz
 * @property-read string $localizedName
 * @property-read string $localizedDescription
 * @property-read string $localizedShortDescription
 */
class Talent extends Model
{
    protected $fillable = [
        'name',
        'name_kz',
        'description',
        'description_kz',
        'short_description',
        'short_description_kz',
        'advice',
        'icon',
        'talent_domain_id'
    ];

    protected $casts = [
        'advice' => 'json',
    ];

    protected $table = 'talents';


    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(TalentDomain::class, 'talent_domain_id');
    }

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class)
            ->withPivot('coefficient')
            ->withTimestamps();
    }

    public function spheres(): BelongsToMany
    {
        return $this->belongsToMany(Sphere::class)
            ->withPivot('coefficient')
            ->withTimestamps();
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

    public function getLocalizedShortDescriptionAttribute(): string
    {
        $locale = app()->getLocale();

        return match($locale) {
            'kk' => $this->short_description_kz ?? $this->short_description,
            default => $this->short_description
        };
    }
}
