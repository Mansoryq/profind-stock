<?php

namespace App\Livewire\Parts;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Header extends Component
{
    public $locale;
    public $userProfessionsCount = 0;
    public $userSpheresCount = 0;

    public function mount()
    {
        if (Auth::check()) {
            $this->loadUserProfessions();
            $this->loadUserSpheres();
        }
    }

    public function loadUserProfessions()
    {
        /** @var User $user */
        $user = Auth::user();
        $this->userProfessionsCount= $user->favouriteProfessions->count();
    }

    public function loadUserSpheres()
    {
        /** @var User $user */
        $user = Auth::user();
        $this->userSpheresCount = $user->favouriteSpheres->count();
    }

    public function render()
    {
        return view('livewire.parts.header', [
            'favoriteProfessionsCount' => $this->userProfessionsCount,
            'favoriteSpheresCount' => $this->userSpheresCount,
        ]);
    }
}
