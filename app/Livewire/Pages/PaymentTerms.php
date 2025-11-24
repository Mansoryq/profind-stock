<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;

class PaymentTerms extends Component
{
    #[Title("Условия оплаты и возврата денежных средств - ProfGid")]

    public function render()
    {
        return view("livewire.pages.payment-terms");
    }
}