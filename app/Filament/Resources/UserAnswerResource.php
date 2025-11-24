<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserAnswerResource\Pages;
use App\Models\UserAnswer;
use App\Models\User;
use App\Models\Answer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserAnswerResource extends Resource
{
    protected static ?string $model = UserAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Ответы пользователей';

    protected static ?string $modelLabel = 'Ответ пользователя';

    protected static ?string $pluralModelLabel = 'Ответы пользователей';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name')
                    ->required(),
                    
                Forms\Components\Select::make('question_id')
                    ->label('Вопрос')
                    ->relationship('question', 'question')
                    ->required(),
                    
                Forms\Components\TextInput::make('answer_value')
                    ->label('Значение ответа')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required(),
                    
                Forms\Components\TextInput::make('response_time_seconds')
                    ->label('Время ответа (сек)')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                    
                Forms\Components\TextInput::make('test_session_id')
                    ->label('ID сессии теста')
                    ->maxLength(255),
                    
                Forms\Components\DateTimePicker::make('answered_at')
                    ->label('Время ответа')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('question.question')
                    ->label('Вопрос')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('question.talent.name')
                    ->label('Талант')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('answer_value')
                    ->label('Ответ')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'danger',
                        '2' => 'warning',
                        '3' => 'gray',
                        '4' => 'success',
                        '5' => 'primary',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('response_time_seconds')
                    ->label('Время ответа')
                    ->formatStateUsing(fn (string $state): string => $state . ' сек')
                    ->sortable()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 5 => 'success',
                        $state <= 10 => 'warning',
                        default => 'danger',
                    }),
                    
                Tables\Columns\TextColumn::make('test_session_id')
                    ->label('Сессия')
                    ->limit(8)
                    ->tooltip(fn (UserAnswer $record): string => $record->test_session_id ?? ''),
                    
                Tables\Columns\TextColumn::make('answered_at')
                    ->label('Дата ответа')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name'),
                    
                Tables\Filters\Filter::make('test_session_id')
                    ->label('ID сессии теста')
                    ->form([
                        Forms\Components\TextInput::make('test_session_id')
                            ->label('ID сессии')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['test_session_id'],
                                fn (Builder $query, $sessionId): Builder => $query->where('test_session_id', 'like', "%{$sessionId}%"),
                            );
                    }),
                    
                Tables\Filters\SelectFilter::make('answer_value')
                    ->label('Значение ответа')
                    ->options([
                        '1' => '1 - Совершенно не согласен',
                        '2' => '2 - Не согласен',
                        '3' => '3 - Нейтрально',
                        '4' => '4 - Согласен',
                        '5' => '5 - Полностью согласен',
                    ]),
                    
                Tables\Filters\Filter::make('response_time')
                    ->label('Быстрые ответы (≤ 5 сек)')
                    ->query(fn (Builder $query): Builder => $query->where('response_time_seconds', '<=', 5)),
                    
                Tables\Filters\Filter::make('slow_response_time')
                    ->label('Медленные ответы (≥ 15 сек)')
                    ->query(fn (Builder $query): Builder => $query->where('response_time_seconds', '>=', 15)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('answered_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserAnswers::route('/'),
            'create' => Pages\CreateUserAnswer::route('/create'),
            'edit' => Pages\EditUserAnswer::route('/{record}/edit'),
        ];
    }
}
