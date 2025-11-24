<?php

namespace App\Filament\Resources\TalentDomainResource\Pages;

use App\Filament\Resources\TalentDomainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalentDomain extends EditRecord
{
    protected static string $resource = TalentDomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
