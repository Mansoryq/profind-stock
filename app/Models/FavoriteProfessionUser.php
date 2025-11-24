<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class FavoriteProfessionUser extends Model
{
    protected $table = 'favorite_profession_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'profession_id',
    ];
}
