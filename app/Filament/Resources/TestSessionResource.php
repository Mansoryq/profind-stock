<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestSessionResource\Pages;
use App\Models\TestSession;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TestSessionResource extends Resource
{
    protected static ?string $model = TestSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Сессии тестов';

    protected static ?string $modelLabel = 'Сессия теста';

    protected static ?string $pluralModelLabel = 'Сессии тестов';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('session_id')
                            ->label('ID сессии')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\Select::make('user_id')
                            ->label('Пользователь')
                            ->relationship('user', 'name')
                            ->required(),
                            
                        Forms\Components\Select::make('status')
                            ->label('Статус сессии')
                            ->options([
                                'started' => 'Начата',
                                'in_progress' => 'В процессе',
                                'completed' => 'Завершена',
                                'abandoned' => 'Прервана',
                            ])
                            ->required(),
                            
                        Forms\Components\DateTimePicker::make('started_at')
                            ->label('Время начала'),
                            
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Время завершения'),
                    ])->columns(2),

                Forms\Components\Section::make('Прогресс теста')
                    ->schema([
                        Forms\Components\TextInput::make('total_questions')
                            ->label('Всего вопросов')
                            ->numeric()
                            ->default(0),
                            
                        Forms\Components\TextInput::make('answered_questions')
                            ->label('Отвечено вопросов')
                            ->numeric()
                            ->default(0),
                            
                        Forms\Components\TextInput::make('completion_percentage')
                            ->label('Процент завершения')
                            ->numeric()
                            ->suffix('%')
                            ->maxValue(100)
                            ->default(0),
                    ])->columns(3),

                Forms\Components\Section::make('Оплата')
                    ->schema([
                        Forms\Components\Select::make('payment_status')
                            ->label('Статус оплаты')
                            ->options([
                                'pending' => 'Ожидает оплаты',
                                'review' => 'Проверка оплаты',
                                'completed' => 'Оплата подтверждена',
                                'failed' => 'Ошибка оплаты',
                            ])
                            ->required(),
                            
                        Forms\Components\Select::make('selected_plan')
                            ->label('Выбранный тариф')
                            ->options([
                                'talents' => 'Таланты (3000 тг)',
                                'talents_spheres' => 'Таланты + Топ сферы (6000 тг)',
                                'talents_spheres_professions' => 'Таланты + Топ сферы + Топ профессии (9000 тг)',
                            ]),
                            
                        Forms\Components\TextInput::make('payment_amount')
                            ->label('Сумма оплаты')
                            ->numeric()
                            ->step(0.01)
                            ->prefix('₸'),
                            
                        Forms\Components\TextInput::make('payer_name')
                            ->label('Имя плательщика')
                            ->maxLength(255)
                            ->helperText('Имя человека, который произвел оплату'),
                            
                        Forms\Components\DateTimePicker::make('payment_confirmed_at')
                            ->label('Время подтверждения оплаты'),
                            
                        Forms\Components\TextInput::make('payment_transaction_id')
                            ->label('ID транзакции')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Временные метрики')
                    ->schema([
                        Forms\Components\TextInput::make('total_time_spent')
                            ->label('Общее время (сек)')
                            ->numeric()
                            ->default(0),
                            
                        Forms\Components\TextInput::make('average_response_time')
                            ->label('Среднее время ответа (сек)')
                            ->numeric()
                            ->step(0.01)
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_id')
                    ->label('ID сессии')
                    ->limit(8)
                    ->tooltip(fn (TestSession $record): string => $record->session_id)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Статус')
                    ->colors([
                        'secondary' => 'started',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'abandoned',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'started' => 'Начата',
                        'in_progress' => 'В процессе',
                        'completed' => 'Завершена',
                        'abandoned' => 'Прервана',
                        default => $state,
                    }),
                    
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Оплата')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'review',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Ожидает',
                        'review' => 'Проверка',
                        'completed' => 'Подтверждена',
                        'failed' => 'Ошибка',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('selected_plan')
                    ->label('Тариф')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'talents' => 'Таланты',
                        'talents_spheres' => 'Таланты + Сферы',
                        'talents_spheres_professions' => 'Полный',
                        default => 'Не выбран',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('payer_name')
                    ->label('Плательщик')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: false),
                    
                Tables\Columns\TextColumn::make('answered_questions')
                    ->label('Ответы')
                    ->formatStateUsing(fn (TestSession $record): string => 
                        $record->answered_questions . ' / ' . $record->total_questions
                    )
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('average_response_time')
                    ->label('Ср. время')
                    ->formatStateUsing(fn (string $state): string => $state . ' сек')
                    ->sortable()
                    ->color(fn (float $state): string => match (true) {
                        $state <= 5 => 'success',
                        $state <= 10 => 'warning',
                        default => 'danger',
                    }),
                    
                Tables\Columns\TextColumn::make('payment_amount')
                    ->label('Сумма')
                    ->money('KZT')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Начата')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Завершена')
                    ->dateTime()
                    ->placeholder('—')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус сессии')
                    ->options([
                        'started' => 'Начата',
                        'in_progress' => 'В процессе',
                        'completed' => 'Завершена',
                        'abandoned' => 'Прервана',
                    ]),
                    
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Статус оплаты')
                    ->options([
                        'pending' => 'Ожидает оплаты',
                        'review' => 'Проверка оплаты',
                        'completed' => 'Оплата подтверждена',
                        'failed' => 'Ошибка оплаты',
                    ]),
                    
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name'),
                    
                Tables\Filters\Filter::make('completed_sessions')
                    ->label('Завершенные сессии')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'completed')),
                    
                Tables\Filters\Filter::make('paid_sessions')
                    ->label('Подтвержденные оплаты')
                    ->query(fn (Builder $query): Builder => $query->where('payment_status', 'completed')),
                    
                Tables\Filters\Filter::make('has_payer_name')
                    ->label('С именем плательщика')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('payer_name')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('viewAnswers')
                    ->label('Ответы')
                    ->icon('heroicon-o-eye')
                    ->url(fn (TestSession $record): string => 
                        route('filament.dashboard.resources.user-answers.index') . 
                        '?tableFilters[test_session_id][value]=' . urlencode($record->session_id)
                    )
                    ->openUrlInNewTab(),
                    
                Tables\Actions\Action::make('approvePayment')
                    ->label('Подтвердить оплату')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (TestSession $record) {
                        $record->update([
                            'payment_status' => 'completed',
                            'payment_confirmed_at' => now()
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Подтвердить оплату?')
                    ->modalDescription('Это действие подтвердит оплату и откроет доступ к результатам теста.')
                    ->visible(fn (TestSession $record): bool => 
                        $record->payment_status === 'review' && !empty($record->payer_name)
                    ),
                    
                Tables\Actions\Action::make('rejectPayment')
                    ->label('Отклонить оплату')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (TestSession $record) {
                        $record->update([
                            'payment_status' => 'failed'
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Отклонить оплату?')
                    ->modalDescription('Это действие отклонит оплату и потребует повторной оплаты.')
                    ->visible(fn (TestSession $record): bool => 
                        $record->payment_status === 'review' && !empty($record->payer_name)
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\TestSession\ListTestSessions::route('/'),
            'create' => Pages\TestSession\CreateTestSession::route('/create'),
            'view' => Pages\TestSession\ViewTestSession::route('/{record}'),
            'edit' => Pages\TestSession\EditTestSession::route('/{record}/edit'),
        ];
    }
}
