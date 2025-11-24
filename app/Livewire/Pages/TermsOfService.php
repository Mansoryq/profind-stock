<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;

class TermsOfService extends Component
{
    #[Title("Договор оферты - ProfGid")]

    public function render()
    {
        return view("livewire.pages.terms-of-service");
    }
}
