<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ProfessionTalent extends Model
{
    protected $table = 'profession_talent';

    protected $fillable = [
        'profession_id',
        'talent_id',
        'coefficient',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'coefficient' => 'float'
    ];
}
