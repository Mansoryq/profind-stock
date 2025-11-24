<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserDropdown extends Component
{
    public $userProfessionsCount = 0;
    public $userSpheresCount = 0;

    public function mount()
    {
        if (Auth::check()) {
            $this->loadUserProfessions();
            $this->loadUserSpheres();
        }
    }

    public function loadUserProfessions(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $this->userProfessionsCount = $user->favouriteProfessions->count();
    }

    public function loadUserSpheres(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $this->userSpheresCount = $user->favouriteSpheres->count();
    }

    public function render()
    {
        return view('livewire.parts.user-dropdown', [
            'favoriteProfessionsCount' => $this->userProfessionsCount,
            'favoriteSpheresCount' => $this->userSpheresCount,
        ]);
    }
}
