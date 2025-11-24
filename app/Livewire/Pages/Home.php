<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Home extends Component
{
    public $plans = [
        'talents' => [
            'name' => 'Таланты',
            'price' => 3000,
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
            'name' => 'Таланты + Топ сферы',
            'price' => 6000,
            'currency' => 'тг',
            'features' => [
                'Все возможности тарифа "Таланты"',
                'Анализ топ-8 подходящих сфер',
                'Рекомендации по выбору сферы',
                'Карьерные пути',
                'Расширенный PDF отчет'
            ],
            'color' => 'blue',
            'popular' => true
        ],
        'talents_spheres_professions' => [
            'name' => 'Таланты + Топ сферы + Топ профессии',
            'price' => 9000,
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

    public function render()
    {
        return view('livewire.pages.home');
    }
}
