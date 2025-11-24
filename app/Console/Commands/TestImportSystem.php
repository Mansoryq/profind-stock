<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;
use App\Services\ImportService;
use App\Services\ImportValidator;
use App\Services\ImportTransformer;
use Illuminate\Support\Facades\Config;
use Exception;

class TestImportSystem extends Command
{
    protected $signature = 'test:import-system';
    protected $description = 'Test the Google Sheets import system components';

    public function handle()
    {
        $this->info('Testing Google Sheets Import System...');
        $this->newLine();

        // Test 1: Configuration
        $this->info('1. Testing Configuration...');
        $this->testConfiguration();
        $this->newLine();

        // Test 2: Google Sheets Service
        $this->info('2. Testing Google Sheets Service...');
        $this->testGoogleSheetsService();
        $this->newLine();

        // Test 3: Import Transformer
        $this->info('3. Testing Import Transformer...');
        $this->testImportTransformer();
        $this->newLine();

        // Test 4: Import Validator
        $this->info('4. Testing Import Validator...');
        $this->testImportValidator();
        $this->newLine();

        // Test 5: Import Service
        $this->info('5. Testing Import Service...');
        $this->testImportService();
        $this->newLine();

        $this->info('✅ All tests completed!');
    }

    private function testConfiguration()
    {
        try {
            $models = Config::get('import.models');
            
            if (empty($models)) {
                $this->error('❌ Import configuration not found');
                return;
            }

            $this->info("✅ Found {count($models)} model configurations:");
            foreach ($models as $key => $model) {
                $this->line("   - {$key}: {$model['table']}");
            }

            // Test Google Sheets configuration
            $sheetId = Config::get('services.google.sheet_id');
            $credentialsPath = Config::get('services.google.credentials_path');

            if (!$sheetId) {
                $this->warn('⚠️  GOOGLE_SHEET_ID not configured in .env');
            } else {
                $this->info("✅ Google Sheet ID configured: {$sheetId}");
            }

            if (!$credentialsPath) {
                $this->warn('⚠️  GOOGLE_APPLICATION_CREDENTIALS not configured in .env');
            } else {
                $this->info("✅ Google credentials path configured: {$credentialsPath}");
                
                if (!file_exists($credentialsPath)) {
                    $this->error("❌ Credentials file not found: {$credentialsPath}");
                } else {
                    $this->info("✅ Credentials file exists");
                }
            }

        } catch (Exception $e) {
            $this->error("❌ Configuration test failed: " . $e->getMessage());
        }
    }

    private function testGoogleSheetsService()
    {
        try {
            $service = app(GoogleSheetsService::class);
            
            // Test connection
            $connectionResult = $service->testConnection();
            
            if ($connectionResult['success']) {
                $this->info('✅ Google Sheets connection successful');
                
                // Test getting sheet names
                $sheets = $service->getSheetNames();
                if (!empty($sheets)) {
                    $this->info("✅ Found " . count($sheets) . " sheets:");
                    foreach ($sheets as $sheet) {
                        $this->line("   - {$sheet}");
                    }
                } else {
                    $this->warn('⚠️  No sheets found in the spreadsheet');
                }
                
            } else {
                $this->error('❌ Google Sheets connection failed: ' . $connectionResult['message']);
            }

        } catch (Exception $e) {
            $this->error("❌ Google Sheets service test failed: " . $e->getMessage());
        }
    }

    private function testImportTransformer()
    {
        try {
            // Test boolean transformation
            $result = ImportTransformer::transform('true', 'to_boolean');
            if ($result === true) {
                $this->info('✅ Boolean transformation works');
            } else {
                $this->error('❌ Boolean transformation failed');
            }

            // Test integer transformation
            $result = ImportTransformer::transform('123', 'to_integer');
            if ($result === 123) {
                $this->info('✅ Integer transformation works');
            } else {
                $this->error('❌ Integer transformation failed');
            }

            // Test row transformation
            $testRow = [
                'name' => 'Test Name',
                'is_active' => 'true',
                'sort_order' => '5'
            ];

            $config = [
                'column_mapping' => [
                    'name' => 'name',
                    'is_active' => 'is_active',
                    'sort_order' => 'sort_order'
                ],
                'transformations' => [
                    'is_active' => 'to_boolean',
                    'sort_order' => 'to_integer'
                ]
            ];

            $transformedRow = ImportTransformer::transformRow($testRow, $config);
            
            if ($transformedRow['is_active'] === true && $transformedRow['sort_order'] === 5) {
                $this->info('✅ Row transformation works');
            } else {
                $this->error('❌ Row transformation failed');
            }

        } catch (Exception $e) {
            $this->error("❌ Import transformer test failed: " . $e->getMessage());
        }
    }

    private function testImportValidator()
    {
        try {
            $validator = app(ImportValidator::class);

            // Test data
            $testData = [
                ['name' => 'Test 1', 'description' => 'Description 1'],
                ['name' => 'Test 2', 'description' => 'Description 2'],
                ['name' => '', 'description' => 'Invalid - no name'], // Should fail
            ];

            $config = [
                'required_columns' => ['name', 'description'],
                'validation_rules' => [
                    'name' => 'required|string|max:255',
                    'description' => 'required|string'
                ]
            ];

            $result = $validator->validate($testData, $config);

            if ($result['stats']['valid_rows'] === 2 && $result['stats']['invalid_rows'] === 1) {
                $this->info('✅ Import validator works correctly');
                $this->line("   Valid rows: {$result['stats']['valid_rows']}");
                $this->line("   Invalid rows: {$result['stats']['invalid_rows']}");
            } else {
                $this->error('❌ Import validator failed');
            }

        } catch (Exception $e) {
            $this->error("❌ Import validator test failed: " . $e->getMessage());
        }
    }

    private function testImportService()
    {
        try {
            // Check if ImportService can be instantiated
            $service = app(ImportService::class);
            $this->info('✅ Import service can be instantiated');

            // Test model configuration retrieval
            $reflection = new \ReflectionClass($service);
            $method = $reflection->getMethod('getModelConfig');
            $method->setAccessible(true);
            
            $config = $method->invoke($service, 'talents');
            
            if ($config && isset($config['model'])) {
                $this->info('✅ Model configuration retrieval works');
            } else {
                $this->error('❌ Model configuration retrieval failed');
            }

        } catch (Exception $e) {
            $this->error("❌ Import service test failed: " . $e->getMessage());
        }
    }
}
