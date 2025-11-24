<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;
use App\Models\Talent;
use App\Models\TalentDomain;
use App\Models\Sphere;
use App\Models\Profession;
use App\Models\Answer;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Log;
use Exception;

class ImportFromGoogleSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:google-sheets
                            {model : The model to import (talents, spheres, professions, answers, promo-codes)}
                            {--sheet= : Sheet name to import from}
                            {--range= : Range to import (e.g., A1:Z100)}
                            {--dry-run : Run without actually importing data}
                            {--truncate : Truncate table before import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from Google Sheets to database tables';

    private GoogleSheetsService $sheetsService;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->sheetsService = new GoogleSheetsService();

        $model = $this->argument('model');
        $sheet = $this->option('sheet');
        $range = $this->option('range');
        $dryRun = $this->option('dry-run');
        $truncate = $this->option('truncate');

        $this->info("Starting import for model: {$model}");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No data will be imported');
        }

        try {
            // Test connection first
            if (!$this->sheetsService->testConnection()) {
                $this->error('Failed to connect to Google Sheets');
                return 1;
            }

            $this->info('✓ Connected to Google Sheets');

            // Get available sheets if no sheet specified
            if (!$sheet) {
                $sheets = $this->sheetsService->getSheetNames();
                $this->info('Available sheets: ' . implode(', ', $sheets));
                $sheet = $this->choice('Select sheet to import from:', $sheets);
            }

            // Set default range if not specified
            if (!$range) {
                $range = $sheet . '!A:Z';
            } else {
                $range = $sheet . '!' . $range;
            }

            $this->info("Importing from range: {$range}");

            // Get data from sheet
            $data = $this->sheetsService->getSheetDataAsAssociativeArray($range);

            if (empty($data)) {
                $this->warn('No data found in the specified range');
                return 0;
            }

            $this->info('Found ' . count($data) . ' rows to import');

            // Import based on model type
            $result = match($model) {
                'talents' => $this->importTalents($data, $dryRun, $truncate),
                'spheres' => $this->importSpheres($data, $dryRun, $truncate),
                'professions' => $this->importProfessions($data, $dryRun, $truncate),
                'answers' => $this->importAnswers($data, $dryRun, $truncate),
                'promo-codes' => $this->importPromoCodes($data, $dryRun, $truncate),
                default => throw new Exception("Unknown model: {$model}")
            };

            if ($result) {
                $this->info('✓ Import completed successfully');
                return 0;
            } else {
                $this->error('✗ Import failed');
                return 1;
            }

        } catch (Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            Log::error('Google Sheets import failed', [
                'model' => $model,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Import talents from Google Sheets
     */
    private function importTalents(array $data, bool $dryRun, bool $truncate): bool
    {
        $requiredColumns = ['name', 'description'];
        $this->sheetsService->validateColumns($data, $requiredColumns);

        if ($truncate && !$dryRun) {
            $this->warn('Truncating talents table...');
            Talent::truncate();
        }

        $imported = 0;
        $errors = 0;

        foreach ($data as $row) {
            try {
                if (empty(trim($row['name']))) {
                    continue; // Skip empty rows
                }

                $talentData = [
                    'name' => trim($row['name']),
                    'description' => trim($row['description'] ?? ''),
                    'short_description' => trim($row['short_description'] ?? ''),
                    'advice' => trim($row['advice'] ?? ''),
                    'icon' => trim($row['icon'] ?? ''),
                ];

                // Handle talent domain
                if (!empty($row['domain'])) {
                    $domain = TalentDomain::firstOrCreate(['name' => trim($row['domain'])]);
                    $talentData['talent_domain_id'] = $domain->id;
                }

                if (!$dryRun) {
                    Talent::updateOrCreate(
                        ['name' => $talentData['name']],
                        $talentData
                    );
                }

                $imported++;
                $this->info("✓ Processed talent: {$talentData['name']}");

            } catch (Exception $e) {
                $errors++;
                $this->error("✗ Failed to import talent: {$row['name']} - {$e->getMessage()}");
            }
        }

        $this->info("Talents processed: {$imported}, Errors: {$errors}");
        return $errors === 0;
    }

    /**
     * Import spheres from Google Sheets
     */
    private function importSpheres(array $data, bool $dryRun, bool $truncate): bool
    {
        $requiredColumns = ['name'];
        $this->sheetsService->validateColumns($data, $requiredColumns);

        if ($truncate && !$dryRun) {
            $this->warn('Truncating spheres table...');
            Sphere::truncate();
        }

        $imported = 0;
        $errors = 0;

        foreach ($data as $row) {
            try {
                if (empty(trim($row['name']))) {
                    continue;
                }

                $sphereData = [
                    'name' => trim($row['name']),
                    'name_kz' => trim($row['name_kz'] ?? ''),
                    'name_en' => trim($row['name_en'] ?? ''),
                    'description' => trim($row['description'] ?? ''),
                    'description_kz' => trim($row['description_kz'] ?? ''),
                    'description_en' => trim($row['description_en'] ?? ''),
                    'color' => trim($row['color'] ?? ''),
                    'icon' => trim($row['icon'] ?? ''),
                    'sort_order' => is_numeric($row['sort_order'] ?? '') ? (int)$row['sort_order'] : 0,
                    'is_active' => !isset($row['is_active']) || strtolower(trim($row['is_active'])) !== 'false',
                ];

                if (!$dryRun) {
                    Sphere::updateOrCreate(
                        ['name' => $sphereData['name']],
                        $sphereData
                    );
                }

                $imported++;
                $this->info("✓ Processed sphere: {$sphereData['name']}");

            } catch (Exception $e) {
                $errors++;
                $this->error("✗ Failed to import sphere: {$row['name']} - {$e->getMessage()}");
            }
        }

        $this->info("Spheres processed: {$imported}, Errors: {$errors}");
        return $errors === 0;
    }

    /**
     * Import professions from Google Sheets
     */
    private function importProfessions(array $data, bool $dryRun, bool $truncate): bool
    {
        $requiredColumns = ['name', 'sphere'];
        $this->sheetsService->validateColumns($data, $requiredColumns);

        if ($truncate && !$dryRun) {
            $this->warn('Truncating professions table...');
            Profession::truncate();
        }

        $imported = 0;
        $errors = 0;

        foreach ($data as $row) {
            try {
                if (empty(trim($row['name'])) || empty(trim($row['sphere']))) {
                    continue;
                }

                // Find sphere
                $sphere = Sphere::where('name', trim($row['sphere']))->first();
                if (!$sphere) {
                    $this->error("✗ Sphere not found: {$row['sphere']}");
                    $errors++;
                    continue;
                }

                $professionData = [
                    'name' => trim($row['name']),
                    'description' => trim($row['description'] ?? ''),
                    'sphere_id' => $sphere->id,
                    'is_active' => !isset($row['is_active']) || strtolower(trim($row['is_active'])) !== 'false',
                ];

                if (!$dryRun) {
                    Profession::updateOrCreate(
                        ['name' => $professionData['name'], 'sphere_id' => $sphere->id],
                        $professionData
                    );
                }

                $imported++;
                $this->info("✓ Processed profession: {$professionData['name']}");

            } catch (Exception $e) {
                $errors++;
                $this->error("✗ Failed to import profession: {$row['name']} - {$e->getMessage()}");
            }
        }

        $this->info("Professions processed: {$imported}, Errors: {$errors}");
        return $errors === 0;
    }

    /**
     * Import answers (questions) from Google Sheets
     */
    private function importAnswers(array $data, bool $dryRun, bool $truncate): bool
    {
        $requiredColumns = ['question', 'talent'];
        $this->sheetsService->validateColumns($data, $requiredColumns);

        if ($truncate && !$dryRun) {
            $this->warn('Truncating answers table...');
            Answer::truncate();
        }

        $imported = 0;
        $errors = 0;

        foreach ($data as $row) {
            try {
                if (empty(trim($row['question'])) || empty(trim($row['talent']))) {
                    continue;
                }

                // Find talent
                $talent = Talent::where('name', trim($row['talent']))->first();
                if (!$talent) {
                    $this->error("✗ Talent not found: {$row['talent']}");
                    $errors++;
                    continue;
                }

                $answerData = [
                    'question' => trim($row['question']),
                    'talent_id' => $talent->id,
                    'value' => is_numeric($row['value'] ?? '') ? (int)$row['value'] : 1,
                    'order' => is_numeric($row['order'] ?? '') ? (int)$row['order'] : 0,
                ];

                if (!$dryRun) {
                    Answer::updateOrCreate(
                        ['question' => $answerData['question'], 'talent_id' => $talent->id],
                        $answerData
                    );
                }

                $imported++;
                $this->info("✓ Processed question: " . substr($answerData['question'], 0, 50) . "...");

            } catch (Exception $e) {
                $errors++;
                $this->error("✗ Failed to import question: " . substr($row['question'], 0, 30) . "... - {$e->getMessage()}");
            }
        }

        $this->info("Questions processed: {$imported}, Errors: {$errors}");
        return $errors === 0;
    }

    /**
     * Import promo codes from Google Sheets
     */
    private function importPromoCodes(array $data, bool $dryRun, bool $truncate): bool
    {
        $requiredColumns = ['code'];
        $this->sheetsService->validateColumns($data, $requiredColumns);

        if ($truncate && !$dryRun) {
            $this->warn('Truncating promo_codes table...');
            PromoCode::truncate();
        }

        $imported = 0;
        $errors = 0;

        foreach ($data as $row) {
            try {
                $code = trim($row['code']);
                if (empty($code)) {
                    continue;
                }

                // Validate code format (6 digits)
                if (!preg_match('/^\d{6}$/', $code)) {
                    $this->error("✗ Invalid code format: {$code} (must be 6 digits)");
                    $errors++;
                    continue;
                }

                $promoData = [
                    'code' => $code,
                    'description' => trim($row['description'] ?? ''),
                    'is_active' => !isset($row['is_active']) || strtolower(trim($row['is_active'])) !== 'false',
                    'is_used' => isset($row['is_used']) && strtolower(trim($row['is_used'])) === 'true',
                ];

                if (!$dryRun) {
                    PromoCode::updateOrCreate(
                        ['code' => $promoData['code']],
                        $promoData
                    );
                }

                $imported++;
                $this->info("✓ Processed promo code: {$promoData['code']}");

            } catch (Exception $e) {
                $errors++;
                $this->error("✗ Failed to import promo code: {$row['code']} - {$e->getMessage()}");
            }
        }

        $this->info("Promo codes processed: {$imported}, Errors: {$errors}");
        return $errors === 0;
    }
}
