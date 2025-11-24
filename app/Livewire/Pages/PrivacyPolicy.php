<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;

class PrivacyPolicy extends Component
{
    #[Title("Политика конфиденциальности - ProfGid")]

    public function render()
    {
        return view("livewire.pages.privacy-policy");
    }
}
