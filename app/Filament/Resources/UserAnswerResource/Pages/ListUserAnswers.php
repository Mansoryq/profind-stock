<?php

namespace App\Filament\Resources\UserAnswerResource\Pages;

use App\Filament\Resources\UserAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserAnswers extends ListRecords
{
    protected static string $resource = UserAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
