<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentDomain extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    protected $table = 'talent_domains';

    public function talents(): HasMany
    {
        return $this->hasMany(Talent::class);
    }
}
