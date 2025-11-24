<?php

namespace App\Filament\Resources\TestSessionResource\Pages;

use App\Filament\Resources\TestSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestSession extends EditRecord
{
    protected static string $resource = TestSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
