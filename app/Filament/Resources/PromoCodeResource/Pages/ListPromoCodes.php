<?php

namespace App\Filament\Resources\PromoCodeResource\Pages;

use App\Filament\Resources\PromoCodeResource;
use App\Models\PromoCode;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPromoCodes extends ListRecords
{
    protected static string $resource = PromoCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Создать промокод'),

            Actions\Action::make('generateBatch')
                ->label('Создать несколько')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\TextInput::make('count')
                        ->label('Количество промокодов')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100)
                        ->default(10),

                    \Filament\Forms\Components\Textarea::make('description_template')
                        ->label('Шаблон описания')
                        ->placeholder('Промокод #%d')
                        ->helperText('Используйте %d для номера промокода'),
                ])
                ->action(function (array $data) {
                    $count = $data['count'];
                    $template = $data['description_template'] ?? 'Промокод #%d';

                    for ($i = 1; $i <= $count; $i++) {
                        PromoCode::create([
                            'code' => PromoCode::generateCode(),
                            'description' => sprintf($template, $i),
                            'is_active' => true,
                        ]);
                    }

                    $this->notify('success', "Успешно создано {$count} промокодов");
                })
                ->requiresConfirmation()
                ->modalHeading('Создать несколько промокодов')
                ->modalDescription('Будут созданы промокоды с уникальными 6-значными кодами'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Все')
                ->badge(PromoCode::count()),

            'active' => Tab::make('Активные')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true))
                ->badge(PromoCode::where('is_active', true)->count())
                ->badgeColor('success'),

            'unused' => Tab::make('Неиспользованные')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_used', false))
                ->badge(PromoCode::where('is_used', false)->count())
                ->badgeColor('info'),

            'available' => Tab::make('Доступные')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)->where('is_used', false))
                ->badge(PromoCode::where('is_active', true)->where('is_used', false)->count())
                ->badgeColor('primary'),

            'used' => Tab::make('Использованные')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_used', true))
                ->badge(PromoCode::where('is_used', true)->count())
                ->badgeColor('warning'),
        ];
    }
}
