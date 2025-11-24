<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $gender
 * @property-read Sphere[]|Collection $favouriteSpheres
 * @property-read Profession[]|Collection $favouriteProfessions
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'language',
        'favorite_professions',
        'favorite_spheres',
        'school',
        'class',
        'gender',
        'phone',
        'is_pupil'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'favorite_professions' => 'array',
            'favorite_spheres' => 'array',
        ];
    }

    /**
     * Get the user answers for the user.
     */
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Get the test sessions for the user.
     */
    public function testSessions()
    {
        return $this->hasMany(TestSession::class);
    }

    public function favouriteSpheres(): BelongsToMany
    {
        return $this->belongsToMany(Sphere::class, FavoriteSphereUser::class);
    }

    public function favouriteProfessions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, FavoriteProfessionUser::class);
    }
}
