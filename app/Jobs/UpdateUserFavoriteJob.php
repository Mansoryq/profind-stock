<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateUserFavoriteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    public function __construct(
        public int $userId,
        public string $type, // 'sphere' or 'profession'
        public int $itemId,
        public bool $isAdding // true for add, false for remove
    ) {}

    public function handle(): void
    {
        try {
            $user = User::find($this->userId);

            if (!$user) {
                Log::warning("User not found for favorite update", ['user_id' => $this->userId]);
                return;
            }

            if ($this->type === 'sphere') {
                $this->updateSphereFavorite($user);
            } elseif ($this->type === 'profession') {
                $this->updateProfessionFavorite($user);
            }

            // Clear relevant cache
            Cache::forget("user_{$this->userId}_favorite_{$this->type}s");

        } catch (\Exception $e) {
            Log::error("Failed to update user favorite", [
                'user_id' => $this->userId,
                'type' => $this->type,
                'item_id' => $this->itemId,
                'is_adding' => $this->isAdding,
                'error' => $e->getMessage()
            ]);

            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    private function updateSphereFavorite(User $user): void
    {
        $exists = $user->favouriteSpheres()->where('sphere_id', $this->itemId)->exists();

        if ($this->isAdding && !$exists) {
            $user->favouriteSpheres()->attach($this->itemId);
        } elseif (!$this->isAdding && $exists) {
            $user->favouriteSpheres()->detach($this->itemId);
        }
    }

    private function updateProfessionFavorite(User $user): void
    {
        $exists = $user->favouriteProfessions()->where('profession_id', $this->itemId)->exists();

        if ($this->isAdding && !$exists) {
            $user->favouriteProfessions()->attach($this->itemId);
        } elseif (!$this->isAdding && $exists) {
            $user->favouriteProfessions()->detach($this->itemId);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("UpdateUserFavoriteJob failed permanently", [
            'user_id' => $this->userId,
            'type' => $this->type,
            'item_id' => $this->itemId,
            'is_adding' => $this->isAdding,
            'error' => $exception->getMessage()
        ]);
    }
}
