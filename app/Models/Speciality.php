<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Speciality extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'university_id',
        'name',
        'code',
        'description',
        'faculty',
        'duration_years',
        'degree_type', // бакалавр, магистр
        'language', // казахский, русский, английский
        'is_active',
        'grant_count',
        'passing_score',
        'subject_1', // обязательный предмет 1
        'subject_2', // обязательный предмет 2
        'subject_3', // предмет по выбору 1
        'subject_4', // предмет по выбору 2
        'subject_5', // предмет по выбору 3
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_years' => 'integer',
        'grant_count' => 'integer',
        'passing_score' => 'integer',
    ];

    /**
     * Get the university that owns this speciality
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get all required subjects for this speciality
     */
    public function getRequiredSubjects()
    {
        return array_filter([
            $this->subject_1,
            $this->subject_2,
            $this->subject_3,
            $this->subject_4,
            $this->subject_5,
        ]);
    }

    /**
     * Check if this speciality matches the given subjects
     */
    public function matchesSubjects($subjects)
    {
        $requiredSubjects = $this->getRequiredSubjects();
        
        // Проверяем, что все обязательные предметы есть в списке
        foreach ($requiredSubjects as $required) {
            if (!in_array($required, $subjects)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Scope to get only active specialities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by university
     */
    public function scopeByUniversity($query, $universityId)
    {
        return $query->where('university_id', $universityId);
    }

    /**
     * Scope to filter by degree type
     */
    public function scopeByDegreeType($query, $degreeType)
    {
        return $query->where('degree_type', $degreeType);
    }

    /**
     * Scope to filter by language
     */
    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope to filter by subjects
     */
    public function scopeBySubjects($query, $subjects)
    {
        return $query->where(function($q) use ($subjects) {
            foreach ($subjects as $subject) {
                $q->orWhere('subject_1', $subject)
                  ->orWhere('subject_2', $subject)
                  ->orWhere('subject_3', $subject)
                  ->orWhere('subject_4', $subject)
                  ->orWhere('subject_5', $subject);
            }
        });
    }
}
