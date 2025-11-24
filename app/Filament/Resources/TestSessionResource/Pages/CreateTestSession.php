<?php

namespace App\Filament\Resources\TestSessionResource\Pages;

use App\Filament\Resources\TestSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestSession extends CreateRecord
{
    protected static string $resource = TestSessionResource::class;
}
