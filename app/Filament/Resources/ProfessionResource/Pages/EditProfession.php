<?php

namespace App\Filament\Resources\ProfessionResource\Pages;

use App\Filament\Resources\ProfessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfession extends EditRecord
{
    protected static string $resource = ProfessionResource::class;

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

        $intellectCoefficients = [];
        foreach ($this->record->intellects as $intellect) {
            $intellectCoefficients[] = [
                'intellect_id' => $intellect->id,
            ];
        }

        $data['intellect_coefficients'] = $intellectCoefficients;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract talent coefficients from form data
        $talentCoefficients = $data['talent_coefficients'] ?? [];
        unset($data['talent_coefficients']);

        // Store talent coefficients for after save
        $this->talentCoefficients = $talentCoefficients;

        $intellectCoefficients = $data['intellect_coefficients'] ?? [];
        unset($data['intellect_coefficients']);

        // Store talent coefficients for after save
        $this->intellectCoefficients = $intellectCoefficients;

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

        $syncIntellect = [];
        if (isset($this->intellectCoefficients)) {
            foreach ($this->intellectCoefficients as $item) {
                if (isset($item['intellect_id'])) {
                    $syncIntellect[$item['intellect_id']] = [
                        'coefficient' => 0
                    ];
                }
            }
        }

        $this->record->talents()->sync($syncData);
        $this->record->intellects()->sync($syncIntellect);
    }
}
