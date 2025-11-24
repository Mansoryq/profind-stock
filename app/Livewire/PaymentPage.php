<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TestSession;
use Illuminate\Support\Facades\Auth;

class PaymentPage extends Component
{
    public $sessionId;
    public $testSession;

    public $plans = [
        'talents' => [
            'name' => 'Мои Таланты',
            'price' => 15000,
            'currency' => 'тг',
            'features' => [
                'Полный анализ всех 34 талантов',
                'Сильные и слабые стороны',
                'Рекомендации по развитию',
                'PDF отчет'
            ],
            'color' => 'blue',
            'popular' => false
        ],
        'talents_spheres' => [
            'name' => 'Мои Профессии',
            'price' => 15000,
            'currency' => 'тг',
            'features' => [
                'Все возможности тарифа "Таланты"',
                'Анализ топ-8 подходящих сфер',
                'Рекомендации по выбору сферы',
                'Карьерные пути',
                'Расширенный PDF отчет'
            ],
            'color' => 'blue',
            'popular' => false
        ],
        'talents_spheres_professions' => [
            'name' => 'Мои таланты + Профессии',
            'price' => 20000,
            'currency' => 'тг',
            'features' => [
                'Все возможности предыдущих тарифов',
                'Анализ топ-8 подходящих профессий',
                'Персональная консультация',
                'Полный PDF отчет'
            ],
            'color' => 'purple',
            'popular' => false
        ]
    ];

    public function mount($sessionId = null)
    {
        $this->sessionId = $sessionId ?? session('last_test_session_id');

        // Если sessionId отсутствует, показываем примерную страницу
        if (!$this->sessionId) {
            // Устанавливаем флаг для отображения примерной страницы
            $this->testSession = null;
            return;
        }

        if ($this->sessionId) {
            $this->testSession = TestSession::query()->where('session_id', $this->sessionId)->first();
        }

        if (!$this->testSession) {
            session()->flash('error', 'Сессия теста не найдена');
            return redirect()->route('talent-test');
        }
    }

    public function selectPlan($planKey)
    {
        $plan = $this->plans[$planKey];

        // Если нет активной сессии теста, перенаправляем на тест
        if (!$this->testSession) {
            session()->flash('info', 'Для оплаты необходимо сначала пройти тест талантов');
            return redirect()->route('talent-test');
        }

        // Обновляем сессию с выбранным планом
        $this->testSession->update([
            'selected_plan' => $planKey,
            'payment_status' => 'pending',
            'payment_amount' => $plan['price']
        ]);

        // Перенаправляем на страницу статуса оплаты
        return redirect()->route('payment-status', ['sessionId' => $this->sessionId, 'plan' => $planKey]);
    }

    public function render()
    {
        return view('livewire.payment-page');
    }
}
