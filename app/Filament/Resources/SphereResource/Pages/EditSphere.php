<?php

namespace App\Filament\Resources\SphereResource\Pages;

use App\Filament\Resources\SphereResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSphere extends EditRecord
{
    protected static string $resource = SphereResource::class;
    
    protected $talentCoefficients = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing talent coefficients
        $talentCoefficients = [];
        foreach ($this->record->talents as $talent) {
            $talentCoefficients[] = [
                'talent_id' => $talent->id,
                'coefficient' => $talent->pivot->coefficient,
            ];
        }
        
        $data['talent_coefficients'] = $talentCoefficients;
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract talent coefficients from form data
        $talentCoefficients = $data['talent_coefficients'] ?? [];
        unset($data['talent_coefficients']);
        
        // Store talent coefficients for after save
        $this->talentCoefficients = $talentCoefficients;
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Sync talents with coefficients
        $syncData = [];
        
        if (isset($this->talentCoefficients)) {
            foreach ($this->talentCoefficients as $item) {
                if (isset($item['talent_id']) && isset($item['coefficient'])) {
                    $syncData[$item['talent_id']] = [
                        'coefficient' => $item['coefficient']
                    ];
                }
            }
        }
        
        $this->record->talents()->sync($syncData);
    }
}
