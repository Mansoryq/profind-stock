<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'city',
        'type', // государственный, частный
        'website',
        'description',
        'is_active',
        'logo',
        'contact_phone',
        'contact_email',
        'address',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all specialities for this university
     */
    public function specialities()
    {
        return $this->hasMany(Speciality::class);
    }

    /**
     * Get active specialities
     */
    public function activeSpecialities()
    {
        return $this->specialities()->where('is_active', true);
    }

    /**
     * Scope to get only active universities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by city
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope to filter by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
