<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetsService;
use App\Services\ImportService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Exception;

class ImportController extends Controller
{
    private GoogleSheetsService $sheetsService;
    private ImportService $importService;

    public function __construct(GoogleSheetsService $sheetsService, ImportService $importService)
    {
        $this->sheetsService = $sheetsService;
        $this->importService = $importService;
    }

    /**
     * Show the import dashboard
     */
    public function index()
    {
        try {
            $connectionStatus = $this->sheetsService->testConnection();
            $spreadsheetInfo = $connectionStatus ? $this->sheetsService->getSpreadsheetInfo() : null;

            return view('admin.import.index', [
                'connectionStatus' => $connectionStatus,
                'spreadsheetInfo' => $spreadsheetInfo,
                'availableModels' => $this->getAvailableModels(),
            ]);
        } catch (Exception $e) {
            return view('admin.import.index', [
                'connectionStatus' => false,
                'spreadsheetInfo' => null,
                'availableModels' => $this->getAvailableModels(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Test Google Sheets connection
     */
    public function testConnection()
    {
        try {
            $status = $this->sheetsService->testConnection();
            $info = $status ? $this->sheetsService->getSpreadsheetInfo() : null;

            return response()->json([
                'success' => $status,
                'info' => $info,
                'message' => $status ? 'Connection successful' : 'Connection failed'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available sheets
     */
    public function getSheets()
    {
        try {
            $sheets = $this->sheetsService->getSheetNames();
            return response()->json([
                'success' => true,
                'sheets' => $sheets
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get sheets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview data from a sheet
     */
    public function previewData(Request $request)
    {
        $request->validate([
            'sheet' => 'required|string',
            'range' => 'nullable|string',
        ]);

        try {
            $sheet = $request->input('sheet');
            $range = $request->input('range');
            
            // Clean and validate the sheet name
            $sheet = trim($sheet);
            
            // If no range specified, use a default range for preview
            if (empty($range)) {
                $range = 'A1:Z10';
            } else {
                $range = trim($range);
            }
            
            // Construct the full range - handle sheet names with spaces or special characters
            if (preg_match('/[^A-Za-z0-9_-]/', $sheet)) {
                // Sheet name has special characters, wrap in single quotes
                $fullRange = "'{$sheet}'!{$range}";
            } else {
                // Simple sheet name, no quotes needed
                $fullRange = "{$sheet}!{$range}";
            }

            Log::info("Attempting to preview data", [
                'sheet' => $sheet,
                'range' => $range,
                'full_range' => $fullRange
            ]);

            // First try to get just the raw data to see if the range works
            $rawData = $this->sheetsService->getSheetData($fullRange);
            
            if (empty($rawData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found in the specified range'
                ]);
            }

            // Convert to associative array
            $data = $this->sheetsService->convertToAssociativeArray($rawData);

            return response()->json([
                'success' => true,
                'data' => array_slice($data, 0, 10), // Limit preview to 10 rows
                'total_rows' => count($data),
                'columns' => !empty($data) ? array_keys($data[0]) : [],
                'raw_rows' => count($rawData)
            ]);
        } catch (Exception $e) {
            Log::error('Preview data failed', [
                'error' => $e->getMessage(),
                'sheet' => $request->input('sheet'),
                'range' => $request->input('range'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to preview data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start import process
     */
    public function startImport(Request $request)
    {
        $request->validate([
            'model' => 'required|string|in:talents,spheres,professions,answers,talent-domains,promo-codes,talent-professions',
            'sheet' => 'required|string',
            'range' => 'nullable|string',
            'dry_run' => 'boolean',
            'truncate' => 'boolean',
            'update_mode' => 'boolean',
            'update_fields' => 'nullable|array',
            'unique_fields' => 'nullable|array',
        ]);

        try {
            $model = $request->input('model');
            $sheet = $request->input('sheet');
            $range = $request->input('range');
            $dryRun = $request->boolean('dry_run', false);
            $truncate = $request->boolean('truncate', false);
            $updateMode = $request->boolean('update_mode', false);
            $updateFields = $request->input('update_fields', []);
            $uniqueFields = $request->input('unique_fields', []);

            // Use the ImportService for better error handling and response
            $result = $this->importService->import(
                $sheet,
                $range,
                $model,
                $dryRun,
                $truncate,
                $updateMode,
                $uniqueFields,
                $updateFields
            );

            Log::info('Import completed via web interface', [
                'model' => $model,
                'sheet' => $sheet,
                'range' => $range,
                'result' => $result
            ]);

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('Import failed', [
                'error' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            // Provide more detailed error message for pattern matching issues
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'pattern') !== false) {
                $errorMessage .= '. Please check that your data matches the required format. For promo codes, they must be 6 digits.';
            }

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $errorMessage
            ], 500);
        }
    }

    /**
     * Get available models for import
     */
    private function getAvailableModels(): array
    {
        return [
            'talents' => [
                'name' => 'Talents',
                'description' => 'Import talent data',
                'required_columns' => ['name', 'description'],
                'optional_columns' => ['short_description', 'advice', 'icon', 'domain'],
                'unique_fields' => ['name'],
                'update_fields' => ['description', 'short_description', 'advice', 'icon', 'talent_domain_id']
            ],
            'spheres' => [
                'name' => 'Spheres',
                'description' => 'Import sphere data',
                'required_columns' => ['name'],
                'optional_columns' => ['name_kz', 'name_en', 'description', 'description_kz', 'description_en', 'color', 'icon', 'sort_order', 'is_active'],
                'unique_fields' => ['name']
            ],
            'professions' => [
                'name' => 'Professions',
                'description' => 'Import profession data',
                'required_columns' => ['name', 'sphere'],
                'optional_columns' => ['description', 'is_active'],
                'relations' => [
                    'sphere' => 'sphere_id (resolved by sphere name)'
                ],
                'unique_fields' => ['name']
            ],
            'talent-professions' => [
                'name' => 'Talent-Profession Relationships',
                'description' => 'Import talent-profession relationships with coefficients',
                'required_columns' => ['talent', 'profession', 'coefficient'],
                'optional_columns' => [],
                'relations' => [
                    'talent' => 'talent_id (resolved by talent name)',
                    'profession' => 'profession_id (resolved by profession name)'
                ],
                'unique_fields' => ['talent_id', 'profession_id'],
                'pivot_table' => 'profession_talent',
                'pivot_fields' => ['coefficient']
            ],
            'answers' => [
                'name' => 'Questions/Answers',
                'description' => 'Import question data',
                'required_columns' => ['question', 'talent'],
                'optional_columns' => ['value', 'order'],
                'relations' => [
                    'talent' => 'talent_id (resolved by talent name)'
                ],
                'unique_fields' => ['question', 'talent_id']
            ],
            'talent-domains' => [
                'name' => 'Talent Domains',
                'description' => 'Import talent domain relationships',
                'required_columns' => ['talent'],
                'optional_columns' => ['domain'],
                'relations' => [
                    'talent' => 'talent_id (resolved by talent name)'
                ],
                'unique_fields' => ['talent_id']
            ],
            'promo-codes' => [
                'name' => 'Promo Codes',
                'description' => 'Import promo code data',
                'required_columns' => ['code'],
                'optional_columns' => ['description', 'is_active', 'is_used'],
                'unique_fields' => ['code']
            ]
        ];
    }
}
