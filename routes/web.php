<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\Pages\About;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\PrivacyPolicy;
use App\Livewire\Pages\TermsOfService;
use App\Livewire\Pages\PaymentTerms;
use App\Livewire\Pages\Login;
use App\Livewire\Pages\ForgotPassword;
use App\Livewire\Pages\ResetPassword;
use App\Livewire\Pages\Profile;
use App\Livewire\Pages\Register;
use App\Livewire\Pages\TalentTest;
use App\Livewire\Pages\TalentTestResults;
use App\Livewire\Pages\ProfessionRecommendations;
use App\Livewire\Pages\MyProfessions;
use App\Livewire\Pages\MySpheres;
use App\Livewire\Pages\ProfessionMap;
use App\Livewire\Pages\TestHistory;
use App\Livewire\PaymentPage;
use App\Livewire\PaymentStatus;
use App\Livewire\GrantAnalysis;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TalentPdfController;
use App\Livewire\PaymentStatusDemo;
use App\Livewire\Pages\TestPreparation;
use App\Http\Controllers\TalentTestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfessionController;

// Language Switcher Route
Route::get("/locale/{locale}", [LocaleController::class, "setLocale"])->name("locale.set");

Route::middleware([SetLocale::class])->group(function () {
    Route::get("/", Home::class)->name("home");
    Route::get("/about", About::class)->name("about");
    Route::get("/grant-analysis", GrantAnalysis::class)->name("grant-analysis");
    Route::get("/privacy-policy", PrivacyPolicy::class)->name("privacy-policy");
    Route::get("/terms-of-service", TermsOfService::class)->name("terms-of-service");
    Route::get("/profession-map", ProfessionMap::class)->name("profession-map");
    Route::get("/payment-terms", PaymentTerms::class)->name("payment-terms");

    Route::get("/export-livewire-page/{session_id}", function () {
        $html = view("export.talents", [
            "userResults" => $this->userResults,
        ])->render();
    })->name("export-livewire");

    Route::get("/login", Login::class)->name("login");
    Route::get("/register", Register::class)->name("register");
    Route::get("/forgot-password", ForgotPassword::class)->name("forgot-password");
    Route::get("/reset-password/{token}", ResetPassword::class)->name("reset-password");

    Route::get("/auth/google", [GoogleController::class, "redirectToGoogle",])->name("auth.google");
    Route::get("/auth/google/callback", [GoogleController::class, "handleGoogleCallback",]);

    Route::get('forte/payment', PaymentStatusDemo::class)->name('payment-status-demo');

    Route::post("/logout", function () {
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route("home");
    })->name("logout");

    Route::middleware(["auth"])->group(function () {
        Route::get("/profile", Profile::class)->name("profile");
    //    Route::get("/settings", Settings::class)->name("settings");
        Route::get("/test-preparation", TestPreparation::class)->name("test-preparation");
        Route::get("/test", TalentTest::class)->name("test");
        Route::get("/talent-test", TalentTest::class)->name("talent-test");
        Route::get("/test/results", TalentTestResults::class)->name("test.results");
        Route::get("/talent-test-results/{sessionId?}", TalentTestResults::class)->name("talent-test-results");
        Route::get("/profession-recommendations/{sessionId?}", ProfessionRecommendations::class)->name("profession-recommendations");
        Route::get("/my-professions", MyProfessions::class)->name("my-professions");
        Route::get("/my-spheres", MySpheres::class)->name("my-spheres");
        Route::get("/test/history", TestHistory::class)->name("test.history");
        Route::get("/payment/{sessionId?}", PaymentPage::class)->name("payment");
        Route::get("/payment-status/{sessionId?}/{plan?}", PaymentStatus::class)->name("payment-status");

        // Talent Test API routes
        Route::post("/api/talent-test/submit", [TalentTestController::class, "submitTestResults"])->name("api.talent-test.submit");

        // Profession management routes
        Route::post("/profession/add-to-favorites", [ProfessionController::class, "addToFavorites"])->name("profession.add-to-favorites");
        Route::post("/profession/remove-from-favorites", [ProfessionController::class, "removeFromFavorites"])->name("profession.remove-from-favorites");

        // PDF download route - принимает тарифный план
        Route::get("/download-talent-pdf", [TalentPdfController::class, "download"])->name("talent.pdf.download");
    });

    // Admin Import Routes (protected by auth middleware)
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/import', [ImportController::class, 'index'])->name('import.index');
        Route::post('/import/test-connection', [ImportController::class, 'testConnection'])->name('import.test-connection');
        Route::get('/import/sheets', [ImportController::class, 'getSheets'])->name('import.sheets');
        Route::post('/import/preview', [ImportController::class, 'previewData'])->name('import.preview');
        Route::post('/import/import-data', [ImportController::class, 'startImport'])->name('import.import-data');
    });
});
