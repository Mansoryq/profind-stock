<?php

namespace App\Livewire\Pages;

use App\Jobs\UpdateUserFavoriteJob;
use App\Models\Profession;
use App\Models\User;
use Livewire\Component;
use App\Models\Sphere;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProfessionMap extends Component
{
    public $search = '';
    public $sortBy = 'sort_order';
    public $sortDirection = 'asc';
    public $showInactive = false;

    public function mount()
    {
        // Pre-load cache if needed
        $this->loadUserFavorites();
    }

    private function loadUserFavorites()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Cache the favorites for better performance
            Cache::remember(
                "user_{$user->id}_favorite_spheres",
                300, // 5 minutes
                fn() => $user->favouriteSpheres()->pluck('spheres.id')->toArray()
            );

            Cache::remember(
                "user_{$user->id}_favorite_professions",
                300, // 5 minutes
                fn() => $user->favouriteProfessions()->pluck('professions.id')->toArray()
            );
        }
    }

    public function likeSphere($sphereId, $isAdding = true)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) return;

        // Dispatch job to update database
        UpdateUserFavoriteJob::dispatch($user->id, 'sphere', $sphereId, $isAdding);

        // Clear cache to ensure fresh data on next load
        Cache::forget("user_{$user->id}_favorite_spheres");

        // Optionally dispatch browser event for confirmation
        $this->dispatch('favoriteUpdated', [
            'type' => 'sphere',
            'id' => $sphereId,
            'action' => $isAdding ? 'added' : 'removed'
        ]);
    }

    public function likeProfession($professionId, $isAdding = true): void
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) return;

        // Dispatch job to update database
        UpdateUserFavoriteJob::dispatch($user->id, 'profession', $professionId, $isAdding);

        // Clear cache to ensure fresh data on next load
        Cache::forget("user_{$user->id}_favorite_professions");

        // Optionally dispatch browser event for confirmation
        $this->dispatch('favoriteUpdated', [
            'type' => 'profession',
            'id' => $professionId,
            'action' => $isAdding ? 'added' : 'removed'
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Sphere::query()
            ->with(['professions' => function($q) {
                if (!$this->showInactive) {
                    $q->where('is_active', true);
                }
                $q->orderBy('name');
            }])
            ->withCount(['professions' => function($q) {
                if (!$this->showInactive) {
                    $q->where('is_active', true);
                }
            }]);

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('name_kz', 'like', '%' . $this->search . '%')
                    ->orWhere('name_en', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if (!$this->showInactive) {
            $query->where('is_active', true);
        }

        $query->orderBy('name');

        /** @var Sphere $spheres */
        $spheres = $query->get();

        // Get user favorites from cache
        $userFavoriteSpheres = [];
        $userFavoriteProfessions = [];

        if (Auth::check()) {
            $user = Auth::user();
            $userFavoriteSpheres = Cache::get("user_{$user->id}_favorite_spheres", []);
            $userFavoriteProfessions = Cache::get("user_{$user->id}_favorite_professions", []);
        }

        // Add favorite information
        $spheres = $spheres->map(function(Sphere $sphere) use ($userFavoriteSpheres, $userFavoriteProfessions) {
            $sphere->is_favorite = in_array($sphere->id, $userFavoriteSpheres);
            $sphere->professions->map(function(Profession $profession) use ($userFavoriteProfessions) {
                $profession->is_favourite = in_array($profession->id, $userFavoriteProfessions);
            });
            return $sphere;
        });

        return view('livewire.pages.profession-map', [
            'spheres' => $spheres
        ]);
    }
}
