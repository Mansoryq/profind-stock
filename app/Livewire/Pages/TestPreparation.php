<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class TestPreparation extends Component
{
    public function startTest()
    {
        // Переходим к тесту
        return redirect()->route('test');
    }

    public function render()
    {
        return view('livewire.pages.test-preparation');
    }
}
