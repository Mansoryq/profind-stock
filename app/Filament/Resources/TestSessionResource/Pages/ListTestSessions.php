<?php

namespace App\Filament\Resources\TestSessionResource\Pages;

use App\Filament\Resources\TestSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestSessions extends ListRecords
{
    protected static string $resource = TestSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
