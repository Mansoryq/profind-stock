<?php

namespace App\Filament\Resources\PromoCodeResource\Pages;

use App\Filament\Resources\PromoCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePromoCode extends CreateRecord
{
    protected static string $resource = PromoCodeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Промокод успешно создан';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure the code is exactly 6 digits
        if (isset($data['code'])) {
            $data['code'] = str_pad($data['code'], 6, '0', STR_PAD_LEFT);
        }

        return $data;
    }
}
