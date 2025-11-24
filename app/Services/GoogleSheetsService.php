<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleSheetsService
{
    private $client;
    private $service;
    private $spreadsheetId;

    public function __construct()
    {
        $this->initializeClient();
        $this->spreadsheetId = config('services.google.sheet_id', env('GOOGLE_SHEET_ID'));
    }

    /**
     * Initialize Google Client
     */
    private function initializeClient()
    {
        try {
            $this->client = new Client();
            
            // Set the path to the service account key file
            $credentialsPath = public_path('assets/credentials.json');
            
            if (!file_exists($credentialsPath)) {
                throw new Exception("Google credentials file not found at: {$credentialsPath}");
            }

            $this->client->setAuthConfig($credentialsPath);
            $this->client->addScope(Sheets::SPREADSHEETS_READONLY);
            $this->client->setApplicationName('Profgid Import System');

            $this->service = new Sheets($this->client);
            
            Log::info('Google Sheets service initialized successfully');
        } catch (Exception $e) {
            Log::error('Failed to initialize Google Sheets service: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get data from a specific sheet range
     *
     * @param string $range The range to read (e.g., 'Sheet1!A1:Z100')
     * @param string|null $spreadsheetId Optional spreadsheet ID, uses default if not provided
     * @return array
     */
    public function getSheetData(string $range, ?string $spreadsheetId = null): array
    {
        try {
            $sheetId = $spreadsheetId ?: $this->spreadsheetId;
            
            if (!$sheetId) {
                throw new Exception('No spreadsheet ID provided');
            }

            // Validate range format
            if (empty(trim($range))) {
                throw new Exception('Range cannot be empty');
            }

            // Log the exact range being requested
            Log::info("Requesting data from range: {$range}", [
                'spreadsheet_id' => $sheetId
            ]);

            $response = $this->service->spreadsheets_values->get($sheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                Log::warning("No data found in range: {$range}");
                return [];
            }

            Log::info("Retrieved " . count($values) . " rows from range: {$range}");
            return $values;
        } catch (\Google\Service\Exception $e) {
            Log::error("Google Sheets API error for range {$range}: " . $e->getMessage(), [
                'error_details' => $e->getErrors(),
                'status_code' => $e->getCode()
            ]);
            throw new Exception("Google Sheets API error: " . $e->getMessage());
        } catch (Exception $e) {
            Log::error("Failed to get sheet data from range {$range}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all sheets in the spreadsheet
     *
     * @param string|null $spreadsheetId Optional spreadsheet ID
     * @return array
     */
    public function getSheetNames(?string $spreadsheetId = null): array
    {
        try {
            $sheetId = $spreadsheetId ?: $this->spreadsheetId;
            
            if (!$sheetId) {
                throw new Exception('No spreadsheet ID provided');
            }

            $spreadsheet = $this->service->spreadsheets->get($sheetId);
            $sheets = $spreadsheet->getSheets();
            
            $sheetNames = [];
            foreach ($sheets as $sheet) {
                $sheetNames[] = $sheet->getProperties()->getTitle();
            }

            Log::info("Found " . count($sheetNames) . " sheets: " . implode(', ', $sheetNames));
            return $sheetNames;
        } catch (Exception $e) {
            Log::error("Failed to get sheet names: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Convert sheet data to associative array using first row as headers
     *
     * @param array $data Raw sheet data
     * @param bool $skipFirstRow Whether to skip the first row (headers)
     * @return array
     */
    public function convertToAssociativeArray(array $data): array
    {
        if (empty($data)) {
            return [];
        }

        $headers = array_shift($data); // Get first row as headers
        $result = [];

        foreach ($data as $row) {
            $rowData = [];
            foreach ($headers as $colIndex => $header) {
                $rowData[trim($header)] = isset($row[$colIndex]) ? trim($row[$colIndex]) : '';
            }
            $result[] = $rowData;
        }

        return $result;
    }

    /**
     * Get sheet data as associative array
     *
     * @param string $range
     * @param string|null $spreadsheetId
     * @return array
     */
    public function getSheetDataAsAssociativeArray(string $range, ?string $spreadsheetId = null): array
    {
        $data = $this->getSheetData($range, $spreadsheetId);
        return $this->convertToAssociativeArray($data);
    }

    /**
     * Validate that required columns exist in the data
     *
     * @param array $data
     * @param array $requiredColumns
     * @return bool
     * @throws Exception
     */
    public function validateColumns(array $data, array $requiredColumns): bool
    {
        if (empty($data)) {
            throw new Exception('No data provided for validation');
        }

        $firstRow = $data[0];
        $availableColumns = array_keys($firstRow);
        $missingColumns = array_diff($requiredColumns, $availableColumns);

        if (!empty($missingColumns)) {
            throw new Exception('Missing required columns: ' . implode(', ', $missingColumns));
        }

        return true;
    }

    /**
     * Get spreadsheet metadata
     *
     * @param string|null $spreadsheetId
     * @return array
     */
    public function getSpreadsheetInfo(?string $spreadsheetId = null): array
    {
        try {
            $sheetId = $spreadsheetId ?: $this->spreadsheetId;
            
            if (!$sheetId) {
                throw new Exception('No spreadsheet ID provided');
            }

            $spreadsheet = $this->service->spreadsheets->get($sheetId);
            
            return [
                'title' => $spreadsheet->getProperties()->getTitle(),
                'sheets' => $this->getSheetNames($sheetId),
                'url' => "https://docs.google.com/spreadsheets/d/{$sheetId}",
            ];
        } catch (Exception $e) {
            Log::error("Failed to get spreadsheet info: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Test connection to Google Sheets
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $this->getSpreadsheetInfo();
            return true;
        } catch (Exception $e) {
            Log::error("Google Sheets connection test failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Convert related field names to model IDs
     *
     * @param array $data
     * @param array $relationMappings
     * @return array
     */
    public function resolveRelations(array $data, array $relationMappings): array
    {
        if (empty($data) || empty($relationMappings)) {
            return $data;
        }

        foreach ($data as &$row) {
            foreach ($relationMappings as $field => $config) {
                if (!isset($row[$field]) || empty($row[$field])) {
                    continue;
                }

                $value = trim($row[$field]);
                $modelClass = $config['model'];
                $searchField = $config['search_field'] ?? 'name';
                $targetField = $config['target_field'] ?? $field;

                try {
                    // Find model by specified field
                    $model = $modelClass::where($searchField, $value)->first();
                    
                    if ($model) {
                        // Replace text value with ID
                        $row[$targetField] = $model->id;
                        
                        // Remove original field if different from target
                        if ($field !== $targetField) {
                            unset($row[$field]);
                        }
                    } else {
                        Log::warning("Related model not found", [
                            'model' => $modelClass,
                            'field' => $searchField,
                            'value' => $value
                        ]);
                        
                        // Either skip row or set null
                        $row[$targetField] = null;
                        if ($field !== $targetField) {
                            unset($row[$field]);
                        }
                    }
                } catch (Exception $e) {
                    Log::error("Error resolving relation", [
                        'model' => $modelClass,
                        'field' => $field,
                        'value' => $value,
                        'error' => $e->getMessage()
                    ]);
                    
                    $row[$targetField] = null;
                    if ($field !== $targetField) {
                        unset($row[$field]);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Validate and resolve foreign key relationships
     *
     * @param array $data
     * @param string $model
     * @return array
     */
    public function resolveModelRelations(array $data, string $model): array
    {
        $relationMappings = $this->getModelRelationMappings($model);
        
        if (empty($relationMappings)) {
            return $data;
        }

        return $this->resolveRelations($data, $relationMappings);
    }

    /**
     * Get relation mappings for a specific model
     *
     * @param string $model
     * @return array
     */
    private function getModelRelationMappings(string $model): array
    {
        $mappings = [
            'answers' => [
                'talent' => [
                    'model' => \App\Models\Talent::class,
                    'search_field' => 'name',
                    'target_field' => 'talent_id'
                ]
            ],
            'professions' => [
                'sphere' => [
                    'model' => \App\Models\Sphere::class,
                    'search_field' => 'name',
                    'target_field' => 'sphere_id'
                ]
            ],
            'talent-domains' => [
                'talent' => [
                    'model' => \App\Models\Talent::class,
                    'search_field' => 'name',
                    'target_field' => 'talent_id'
                ]
            ],
            // Add other models and their relations here
        ];

        return $mappings[$model] ?? [];
    }
    
    /**
     * Update existing records or create new ones based on unique fields
     *
     * @param string $modelClass Full class name of the model
     * @param array $data Array of records to import
     * @param array $uniqueFields Fields to use for finding existing records
     * @param array $updateFields Fields that should be updated (empty means all)
     * @return array Results with counts of created, updated, and failed records
     */
    public function updateOrCreateRecords(string $modelClass, array $data, array $uniqueFields = [], array $updateFields = []): array
    {
        $created = 0;
        $updated = 0;
        $failed = 0;
        $results = [];

        if (empty($data)) {
            return [
                'created' => $created,
                'updated' => $updated,
                'failed' => $failed,
                'results' => $results
            ];
        }

        foreach ($data as $record) {
            try {
                // Build the query to find existing record
                $query = $modelClass::query();
                $recordExists = false;
                
                // If unique fields provided, use them to find existing record
                if (!empty($uniqueFields)) {
                    foreach ($uniqueFields as $field) {
                        if (isset($record[$field])) {
                            $query->where($field, $record[$field]);
                        }
                    }
                    
                    $existingRecord = $query->first();
                    $recordExists = !is_null($existingRecord);
                }

                // If record exists and update fields provided, update only those fields
                if ($recordExists) {
                    $updateData = $record;
                    
                    // If specific update fields provided, only include those
                    if (!empty($updateFields)) {
                        $updateData = array_intersect_key($record, array_flip($updateFields));
                    }
                    
                    // Don't update primary key
                    if (isset($updateData['id'])) {
                        unset($updateData['id']);
                    }
                    
                    $existingRecord->update($updateData);
                    $updated++;
                    $results[] = [
                        'status' => 'updated',
                        'model' => class_basename($modelClass),
                        'id' => $existingRecord->id
                    ];
                } else {
                    // Create new record
                    $newRecord = $modelClass::create($record);
                    $created++;
                    $results[] = [
                        'status' => 'created',
                        'model' => class_basename($modelClass),
                        'id' => $newRecord->id
                    ];
                }
            } catch (Exception $e) {
                Log::error("Failed to update or create record", [
                    'model' => class_basename($modelClass),
                    'data' => $record,
                    'error' => $e->getMessage()
                ]);
                
                $failed++;
                $results[] = [
                    'status' => 'failed',
                    'model' => class_basename($modelClass),
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'created' => $created,
            'updated' => $updated,
            'failed' => $failed,
            'results' => $results
        ];
    }
}
