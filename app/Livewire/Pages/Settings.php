<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Settings extends Component
{
    public $notifications_email = true;
    public $notifications_app = true;
    public $language = 'ru';
    public $theme = 'light';

    public function mount()
    {
        $this->language = session('locale', config('app.fallback_locale', 'ru'));
        
        if (Auth::check()) {
            $user = Auth::user();
            $this->language = $user->language ?? $this->language;
        }
    }

    public function updateSettings()
    {
        $this->validate([
            'language' => 'required|in:ru,kk,en',
            'theme' => 'required|in:light,dark',
            'notifications_email' => 'boolean',
            'notifications_app' => 'boolean',
        ]);

        // Save language preference
        session(['locale' => $this->language]);
        App::setLocale($this->language);

        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'language' => $this->language
            ]);
        }

        session()->flash('message', __('messages.settings_saved'));
    }

    public function render()
    {
        return view('livewire.pages.settings');
    }
}
