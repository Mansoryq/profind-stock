<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'is_used',
        'used_by',
        'used_at',
        'description',
        'is_active',
        'max_uses',
        'current_uses',
        'expires_at',
        'type',
        'discount',
        'unlimited_uses'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'is_active' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'max_uses' => 'integer',
        'current_uses' => 'integer',
        'discount' => 'integer',
        'unlimited_uses' => 'integer'
    ];

    /**
     * Get the user who used this promo code
     */
    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Get all users who have used this promo code
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'promo_code_uses')
                    ->withPivot('used_at')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active promo codes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only unused promo codes
     */
    public function scopeUnused($query)
    {
        return $query->where('current_uses', '<', $this->max_uses ?? 1);
    }

    /**
     * Scope to get available promo codes (active and not expired)
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    })
                    ->whereRaw('current_uses < COALESCE(max_uses, 1)');
    }

    /**
     * Check if promo code is available for use
     */
    public function isAvailable()
    {
        return $this->is_active &&
               ($this->expires_at === null || $this->expires_at > now()) &&
               ($this->current_uses < ($this->max_uses ?? 1));
    }

    /**
     * Check if user has already used this promo code
     */
    public function hasBeenUsedBy($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }

    /**
     * Mark promo code as used by a user
     */
    public function markAsUsed($userId)
    {
        // Attach user to promo code
//        if (!$this->hasBeenUsedBy($userId)) {
//            $this->users()->attach($userId, [
//                'used_at' => now(),
//            ]);

            // Increment current uses
            $this->increment('current_uses');

            // Update legacy fields for backwards compatibility
            $this->update([
                'is_used' => 1,
                'used_by' => $userId,
                'used_at' => now(),
            ]);
//        }
    }

    /**
     * Get remaining uses
     */
    public function getRemainingUsesAttribute()
    {
        return max(0, ($this->max_uses ?? 1) - $this->current_uses);
    }

    /**
     * Check if promo code is exhausted
     */
    public function isExhausted()
    {
        return $this->current_uses >= ($this->max_uses ?? 1);
    }

    /**
     * Generate a random 6-digit promo code
     */
    public static function generateCode()
    {
        do {
            $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
