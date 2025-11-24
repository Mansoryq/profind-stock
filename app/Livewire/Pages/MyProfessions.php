<?php

namespace App\Livewire\Pages;

use App\Jobs\UpdateUserFavoriteJob;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MyProfessions extends Component
{
    public $favoriteProfessions = [];

    public function mount()
    {
        $this->loadFavoriteProfessions();
    }

    public function loadFavoriteProfessions()
    {
        /** @var User $user */
        $user = Auth::user();

        // Load favorite professions with their relationships
        $this->favoriteProfessions = $user->favouriteProfessions()
            ->with(['sphere' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->get();
    }

    public function toggleProfessionLike($professionId, $isAdding = true)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) return;

        // Dispatch job to update database
        UpdateUserFavoriteJob::dispatch($user->id, 'profession', $professionId, $isAdding);

        // Clear cache to ensure fresh data on next load
        Cache::forget("user_{$user->id}_favorite_professions");

        // Dispatch browser event for confirmation
        $this->dispatch('favoriteUpdated', [
            'type' => 'profession',
            'id' => $professionId,
            'action' => $isAdding ? 'added' : 'removed'
        ]);
    }

    // Keep the old method for backward compatibility if needed
    public function removeProfession($professionId)
    {
        $this->toggleProfessionLike($professionId, false);
    }

    public function render()
    {
        return view('livewire.pages.my-professions');
    }
}
