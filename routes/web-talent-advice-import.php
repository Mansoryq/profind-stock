<?php

use App\Http\Controllers\TalentAdviceImportController;
use Illuminate\Support\Facades\Route;

// Routes for talent advice import
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/talents/import-advice', [TalentAdviceImportController::class, 'showImportForm'])
        ->name('admin.talents.import-advice.form');
    Route::post('/admin/talents/import-advice', [TalentAdviceImportController::class, 'import'])
        ->name('admin.talents.import-advice');
});