<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalentResource\Pages;
use App\Filament\Resources\TalentResource\RelationManagers;
use App\Models\Talent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TalentResource extends Resource
{
    protected static ?string $model = Talent::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Таланты';

    protected static ?string $modelLabel = 'Талант';

    protected static ?string $pluralModelLabel = 'Таланты';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('talent_domain_id')
                    ->relationship('domain', 'name')
                    ->label('Домен таланта')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->label('Иконка')
                    ->helperText('Название иконки или URL')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Описание')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('short_description')
                    ->label('Краткое описание')
                    ->helperText('Краткое описание таланта (показывается всем пользователям)')
                    ->maxLength(500)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('advice')
                    ->label('Советы')
                    ->helperText('Советы для человека с этим талантом')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название совета')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_kz')
                            ->label('Название совета на казахском')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Описание совета')
                            ->required()
                            ->maxLength(65535),
                        Forms\Components\Textarea::make('description_kz')
                            ->label('Описание совета на казахском')
                            ->required()
                            ->maxLength(65535),
                    ])
                    ->defaultItems(0)
                    ->maxItems(5)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain.name')
                    ->label('Домен')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('advice')
                    ->label('Кол-во советов')
                    ->formatStateUsing(fn($state) => is_array($state) ? count($state) : 0)
                    ->badge()
                    ->color(fn($state) => is_array($state) && count($state) > 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('answers_count')
                    ->label('Кол-во вопросов')
                    ->counts('answers')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('domain')
                    ->label('Домен')
                    ->relationship('domain', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalent::route('/'),
            'create' => Pages\CreateTalent::route('/create'),
            'edit' => Pages\EditTalent::route('/{record}/edit'),
        ];
    }
}
