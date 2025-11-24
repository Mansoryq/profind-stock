<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SphereResource\Pages;
use App\Filament\Resources\SphereResource\RelationManagers;
use App\Models\Sphere;
use App\Models\Talent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SphereResource extends Resource
{
    protected static ?string $model = Sphere::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    
    protected static ?string $navigationLabel = 'Сферы профессий';
    
    protected static ?string $modelLabel = 'Сфера';
    
    protected static ?string $pluralModelLabel = 'Сферы';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('name_kz')
                            ->label('Название (Казахский)')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('name_en')
                            ->label('Название (Английский)')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->rows(3),
                        
                        Forms\Components\Textarea::make('description_kz')
                            ->label('Описание (Казахский)')
                            ->rows(3),
                        
                        Forms\Components\Textarea::make('description_en')
                            ->label('Описание (Английский)')
                            ->rows(3),
                    ])->columns(2),
                
                Forms\Components\Section::make('Настройки отображения')
                    ->schema([
                        Forms\Components\ColorPicker::make('color')
                            ->label('Цвет')
                            ->default('#6B7280'),
                        
                        Forms\Components\TextInput::make('icon')
                            ->label('Иконка (CSS класс)')
                            ->placeholder('heroicon-o-briefcase'),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Порядок сортировки')
                            ->numeric()
                            ->default(0),
                        
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
                            ->minValue(0.01)
                            ->maxValue(0.99)
                            ->default(0.50)
                            ->required(),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->collapsible()
                    ->collapsed()
                    ->itemLabel(fn (array $state): ?string => 
                        isset($state['talent_id']) ? Talent::find($state['talent_id'])?->name : null
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
                
                Tables\Columns\ColorColumn::make('color')
                    ->label('Цвет'),
                
                Tables\Columns\TextColumn::make('professions_count')
                    ->label('Профессий')
                    ->counts('professions')
                    ->badge(),

                Tables\Columns\TextColumn::make('talents_count')
                    ->label('Талантов')
                    ->counts('talents')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
                
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Статус')
                    ->boolean()
                    ->trueLabel('Только активные')
                    ->falseLabel('Только неактивные')
                    ->native(false),
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
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProfessionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpheres::route('/'),
            'create' => Pages\CreateSphere::route('/create'),
            'edit' => Pages\EditSphere::route('/{record}/edit'),
        ];
    }
}
