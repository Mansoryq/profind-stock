<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfessionResource\Pages;
use App\Models\Intellect;
use App\Models\Profession;
use App\Models\Sphere;
use App\Models\Talent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProfessionResource extends Resource
{
    protected static ?string $model = Profession::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Профессии';

    protected static ?string $modelLabel = 'Профессия';

    protected static ?string $pluralModelLabel = 'Профессии';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название профессии')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('sphere_id')
                            ->label('Сфера')
                            ->options(\App\Models\Sphere::pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('rating')
                            ->label('Рейтинг')
                            ->numeric(),

                        Forms\Components\TextInput::make('man')
                            ->label('Мужчина')
                            ->numeric(),

                        Forms\Components\TextInput::make('woman')
                            ->label('Женщина')
                            ->numeric(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Активна')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Repeater::make('talent_coefficients')
                    ->label('Таланты и коэффициенты')
                    ->schema([
                        Forms\Components\Select::make('talent_id')
                            ->label('Талант')
                            ->options(Talent::pluck('name', 'id'))
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                        Forms\Components\TextInput::make('coefficient')
                            ->label('Коэффициент')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(9.99)
                            ->default(1.00)
                            ->required(),
                    ])
                    ->columns(2)
                    ->minItems(1)
                    ->maxItems(8)
                    ->defaultItems(0)
                    ->columnSpanFull()
                    ->addActionLabel('Добавить талант')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string =>
                        $state['talent_id'] ? Talent::find($state['talent_id'])?->name . ' (коэф.: ' . ($state['coefficient'] ?? '1.00') . ')' : null
                    ),

                Forms\Components\Repeater::make('intellect_coefficients')
                    ->label('Интелект и коэффициенты')
                    ->schema([
                        Forms\Components\Select::make('intellect_id')
                            ->label('Интелект')
                            ->options(Intellect::pluck('name', 'id'))
                            ->required()
                            ->distinct(),
                    ])
                    ->minItems(1)
                    ->maxItems(2)
                    ->defaultItems(0)
                    ->columnSpanFull()
                    ->addActionLabel('Добавить интелект')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string =>
                        $state['intellect_id'] ? Intellect::find($state['intellect_id'])?->name : null
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sphere.name')
                    ->label('Сфера')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Описание')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('talents_count')
                    ->label('Количество талантов')
                    ->counts('talents'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sphere_id')
                    ->options(Sphere::query()->pluck('name', 'id'))
                    ->label('Сфера')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfessions::route('/'),
            'create' => Pages\CreateProfession::route('/create'),
            'edit' => Pages\EditProfession::route('/{record}/edit'),
        ];
    }
}
