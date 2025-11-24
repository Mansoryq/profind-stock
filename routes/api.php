<?php

use App\Http\Controllers\Import\Profession\TalentController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::group(['prefix' => 'import'], function () {
    Route::group(['prefix' => 'profession'], function () {
        Route::post('talents', [TalentController::class, 'import']);
    });
});
