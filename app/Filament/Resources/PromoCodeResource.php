<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoCodeResource\Pages;
use App\Models\PromoCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromoCodeResource extends Resource
{
    protected static ?string $model = PromoCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Промокоды';

    protected static ?string $modelLabel = 'Промокод';

    protected static ?string $pluralModelLabel = 'Промокоды';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Информация о промокоде')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Код')
                            ->required()
                            ->length(6)
                            ->placeholder('123456')
                            ->helperText('Код должен состоять из 6 символов')
                            ->default(fn() => PromoCode::generateCode())
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'length' => 'Код должен быть длиной 6 символов',
                            ]),
//                        Forms\Components\Select::make('type')
//                            ->label('Тип')
//                            ->reactive()
//                            ->options(['full' => 'Полная оплата', 'half' => 'Половина оплаты']),

                        Forms\Components\TextInput::make('discount')
                            ->label('Процент скидки')
                            ->default(0),

                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->placeholder('Описание промокода...')
                            ->rows(3),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true)
                            ->helperText('Неактивные промокоды нельзя использовать'),
                    ]),

                Forms\Components\Section::make('Настройки использования')
                    ->schema([
                        Forms\Components\Checkbox::make('unlimited_uses')
                            ->label('Неограниченное количество использований')
                            ->default(false)
                            ->live()
                            ->helperText('Включите, чтобы промокод можно было использовать неограниченное количество раз'),

//                        Forms\Components\TextInput::make('max_uses')
//                            ->label('Максимальное количество использований')
//                            ->numeric()
//                            ->default(1)
//                            ->minValue(1)
//                            ->maxValue(1000)
//                            ->helperText('Сколько раз можно использовать этот промокод')
//                            ->disabled(fn (Get $get) => $get('unlimited_uses'))
//                            ->dehydrated(fn (Get $get) => !$get('unlimited_uses'))
//                            ->required(fn (Get $get) => !$get('unlimited_uses')),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Дата истечения')
                            ->nullable()
                            ->helperText('Оставьте пустым для бессрочного промокода')
                            ->minDate(now()),
                    ]),
//                    ->visible(fn (callable $get) => $get('type') === 'full'),

                Forms\Components\Section::make('Статистика использования')
                    ->schema([
                        Forms\Components\TextInput::make('current_uses')
                            ->label('Текущее количество использований')
                            ->numeric()
                            ->disabled()
                            ->default(0)
                            ->helperText('Автоматически увеличивается при использовании'),

                        Forms\Components\Placeholder::make('remaining_uses')
                            ->label('Оставшееся количество использований')
                            ->content(fn ($record) => $record ? $record->remaining_uses : 'Н/Д')
                            ->visible(fn ($record) => $record?->exists),

                        Forms\Components\Toggle::make('is_used')
                            ->label('Полностью использован')
                            ->disabled()
                            ->helperText('Автоматически устанавливается при достижении лимита'),

                        Forms\Components\Select::make('used_by')
                            ->label('Последний пользователь')
                            ->relationship('usedBy', 'email')
                            ->disabled()
                            ->placeholder('Не использован'),

                        Forms\Components\DateTimePicker::make('used_at')
                            ->label('Дата последнего использования')
                            ->disabled()
                            ->placeholder('Не использован'),
                    ])
                    ->visible(fn($record) => $record?->exists),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Код')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Код скопирован!')
                    ->weight('medium')
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Описание')
                    ->searchable()
                    ->limit(50)
                    ->placeholder('Без описания'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'full' => 'Полная оплата',
                            'half' => 'Половина оплаты',
                            default => ucfirst($state),
                        };
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('usage_progress')
                    ->label('Использование')
                    ->state(function ($record) {
                        $current = $record->current_uses;
                        $max = $record->max_uses;
                        $percentage = $max > 0 ? round(($current / $max) * 100) : 0;
                        return "{$current}/{$max} ({$percentage}%)";
                    })
                    ->badge()
                    ->color(function ($record) {
                        $percentage = $record->max_uses > 0 ? ($record->current_uses / $record->max_uses) * 100 : 0;
                        if ($percentage >= 100) return 'danger';
                        if ($percentage >= 75) return 'warning';
                        if ($percentage >= 50) return 'info';
                        return 'success';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('remaining_uses')
                    ->label('Осталось')
                    ->state(fn ($record) => $record->remaining_uses)
                    ->badge()
                    ->color(function ($record) {
                        $remaining = $record->remaining_uses;
                        if ($remaining == 0) return 'danger';
                        if ($remaining <= 5) return 'warning';
                        return 'success';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Истекает')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Бессрочно')
                    ->color(function ($record) {
                        if (!$record->expires_at) return 'success';
                        $daysUntilExpiry = now()->diffInDays($record->expires_at, false);
                        if ($daysUntilExpiry < 0) return 'danger';
                        if ($daysUntilExpiry <= 7) return 'warning';
                        return 'success';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Пользователи')
                    ->counts('users')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('used_at')
                    ->label('Последнее использование')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Не использован')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Активность')
                    ->placeholder('Все')
                    ->trueLabel('Только активные')
                    ->falseLabel('Только неактивные'),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Доступность')
                    ->placeholder('Все')
                    ->trueLabel('Доступные для использования')
                    ->falseLabel('Недоступные')
                    ->queries(
                        true: fn (Builder $query) => $query->whereRaw('current_uses < COALESCE(max_uses, 1)')
                            ->where('is_active', true)
                            ->where(function($q) {
                                $q->whereNull('expires_at')
                                  ->orWhere('expires_at', '>', now());
                            }),
                        false: fn (Builder $query) => $query->where(function($q) {
                            $q->whereRaw('current_uses >= COALESCE(max_uses, 1)')
                              ->orWhere('is_active', false)
                              ->orWhere('expires_at', '<=', now());
                        })
                    ),

                Tables\Filters\Filter::make('expires_soon')
                    ->label('Истекают скоро')
                    ->query(fn (Builder $query) => $query->where('expires_at', '<=', now()->addDays(7))
                        ->where('expires_at', '>', now()))
                    ->toggle(),

                Tables\Filters\Filter::make('expired')
                    ->label('Истекшие')
                    ->query(fn (Builder $query) => $query->where('expires_at', '<=', now()))
                    ->toggle(),

                Tables\Filters\SelectFilter::make('max_uses')
                    ->label('Тип использования')
                    ->options([
                        '1' => 'Одноразовые',
                        '2-10' => 'Ограниченные (2-10)',
                        '11-100' => 'Массовые (11-100)',
                        '100+' => 'Неограниченные (100+)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!$data['value']) return $query;

                        return match($data['value']) {
                            '1' => $query->where('max_uses', 1),
                            '2-10' => $query->whereBetween('max_uses', [2, 10]),
                            '11-100' => $query->whereBetween('max_uses', [11, 100]),
                            '100+' => $query->where('max_uses', '>', 100),
                            default => $query,
                        };
                    }),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('reset_usage')
                    ->label('Сбросить использования')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function ($record) {
                        $record->users()->detach();
                        $record->update([
                            'current_uses' => 0,
                            'is_used' => false,
                            'used_by' => null,
                            'used_at' => null,
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Сбросить использования')
                    ->modalDescription('Это действие удалит всю историю использования промокода. Продолжить?')
                    ->visible(fn ($record) => $record->current_uses > 0),

                Tables\Actions\Action::make('extend_expiry')
                    ->label('Продлить срок')
                    ->icon('heroicon-o-calendar-days')
                    ->color('info')
                    ->form([
                        Forms\Components\DateTimePicker::make('new_expires_at')
                            ->label('Новая дата истечения')
                            ->required()
                            ->minDate(now()),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(['expires_at' => $data['new_expires_at']]);
                    })
                    ->visible(fn ($record) => $record->expires_at),

                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('Активировать')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_active' => true]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Активировать промокоды')
                        ->modalDescription('Вы уверены, что хотите активировать выбранные промокоды?'),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Деактивировать')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_active' => false]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Деактивировать промокоды')
                        ->modalDescription('Вы уверены, что хотите деактивировать выбранные промокоды?'),

                    Tables\Actions\BulkAction::make('extend_expiry')
                        ->label('Продлить срок')
                        ->icon('heroicon-o-calendar-days')
                        ->color('info')
                        ->form([
                            Forms\Components\DateTimePicker::make('new_expires_at')
                                ->label('Новая дата истечения')
                                ->required()
                                ->minDate(now()),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update(['expires_at' => $data['new_expires_at']]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Продлить срок действия')
                        ->modalDescription('Установить новую дату истечения для выбранных промокодов?'),

                    Tables\Actions\BulkAction::make('reset_usage')
                        ->label('Сбросить использования')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->users()->detach();
                                $record->update([
                                    'current_uses' => 0,
                                    'is_used' => false,
                                    'used_by' => null,
                                    'used_at' => null,
                                ]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Сбросить использования')
                        ->modalDescription('Это действие удалит всю историю использования выбранных промокодов. Продолжить?'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromoCodes::route('/'),
            'create' => Pages\CreatePromoCode::route('/create'),
            'view' => Pages\ViewPromoCode::route('/{record}'),
            'edit' => Pages\EditPromoCode::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::available()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::available()->count();
        if ($count == 0) return 'danger';
        if ($count < 10) return 'warning';
        return 'success';
    }
}
