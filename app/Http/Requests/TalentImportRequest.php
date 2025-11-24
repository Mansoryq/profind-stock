<?php

namespace App\Http\Requests;

use App\Models\Profession;
use App\Models\Talent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

final class TalentImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'profession' => ['required', 'string', new Exists(Profession::class, 'name')],
            'talents' => ['required', 'array'],
            'talents.*.talent' => ['required', 'string', new Exists(Talent::class, 'name')],
            'talents.*.coefficient' => ['required', 'numeric'],
        ];
    }

    public function getProfession(): string
    {
        return $this->validated('profession');
    }

    public function getTalents(): array
    {
        return $this->validated('talents');
    }
}
