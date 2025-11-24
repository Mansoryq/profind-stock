<?php

namespace App\Livewire;

use Livewire\Component;

class PaymentStatusDemo extends Component
{
    public $sessionId = null;
    public $plan = 'talents';
    public $testSession = null;
    public $pin1;
    public $pin2;
    public $pin3;
    public $pin4;
    public $pin5;
    public $pin6;
    public $paymentConfirmed = false;
    public $showPromoCodeForm = false;

    public $plans = [
        "talents" => [
            "name" => "Таланты",
            "price" => 3000,
            "currency" => "тг",
        ],
        "talents_spheres" => [
            "name" => "Таланты + Топ сферы",
            "price" => 6000,
            "currency" => "тг",
        ],
        "talents_spheres_professions" => [
            "name" => "Таланты + Топ сферы + Топ профессии",
            "price" => 9000,
            "currency" => "тг",
        ],
    ];

    protected $listeners = ["refreshPaymentStatus"];

    public function mount()
    {
        // Демо-режим: все��да без активной сессии
        $this->sessionId = null;
        $this->plan = 'talents';
        $this->testSession = null;
        $this->paymentConfirmed = false;
    }

    public function refreshPaymentStatus()
    {
        // В демо-режиме ничего не обновляем
        return;
    }

    public function getPromoCode()
    {
        $this->showPromoCodeForm = true;

        // Prepare WhatsApp link but don't redirect
        $phone = "+77072170555";
        $message = urlencode(
            "Здравствуйте! Хочу получить промокод для профориентационного теста."
        );
        $whatsappUrl = "https://wa.me/$phone?text=$message";

        // Emit event to open WhatsApp in new tab via JavaScript
        $this->dispatch("openWhatsApp", $whatsappUrl);
    }

    public function confirmPayment()
    {
        // В демо-режиме перенаправляем на тест
        session()->flash('info', 'Это демо-версия. Для подтверждения оплаты необходимо сначала пройти тест талантов');
        return redirect()->route('talent-test');
    }

    public function getStatusText()
    {
        // В демо-режиме всегда показываем "Тест не пройден"
        return "Тест не пройден";
    }

    public function getStatusColor()
    {
        // В демо-режиме всегда серый цвет
        return "text-gray-600 bg-gray-100";
    }

    public function render()
    {
        return view("livewire.payment-status-demo");
    }
}