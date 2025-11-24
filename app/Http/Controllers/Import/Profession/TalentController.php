<?php

declare(strict_types=1);

namespace App\Http\Controllers\Import\Profession;

use App\Http\Controllers\Controller;
use App\Http\Requests\TalentImportRequest;
use App\Models\Profession;
use App\Models\ProfessionTalent;
use App\Models\Talent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final class TalentController extends Controller
{
    public function import(TalentImportRequest $request): JsonResponse
    {
        $profession = Profession::query()->where('name', $request->getProfession())->firstOrFail();
        $talents = $request->getTalents();

        DB::transaction(function () use ($profession, $talents) {
            foreach ($talents as $talent) {
                $talentModel = Talent::query()->where('name', $talent['talent'])->firstOrFail();

                ProfessionTalent::query()
                    ->create([
                        'profession_id' => $profession->id,
                        'talent_id' => $talentModel->id,
                        'coefficient' => $talent['coefficient'],
                    ]);
            }
        });

        return response()->json([
           'message' => 'Talents imported successfully.',
        ]);
    }
}
