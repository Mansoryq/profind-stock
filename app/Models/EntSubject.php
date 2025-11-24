<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get subjects commonly used together
     */
    public static function getCommonCombinations()
    {
        return [
            'Техническое направление' => ['Математика', 'Физика', 'Информатика'],
            'Медицинское направление' => ['Математика', 'Биология', 'Химия'],
            'Экономическое направление' => ['Математика', 'География', 'Всемирная история'],
            'Гуманитарное направление' => ['История Казахстана', 'География', 'Всемирная история'],
            'Педагогическое направление' => ['История Казахстана', 'География', 'Литература'],
        ];
    }
}
