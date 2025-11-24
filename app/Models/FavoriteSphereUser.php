<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class FavoriteSphereUser extends Model
{
    protected $table = 'favorite_sphere_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'sphere_id',
    ];
}
