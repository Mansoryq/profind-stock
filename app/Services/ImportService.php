<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Exception;

class ImportService
{
    private GoogleSheetsService $sheetsService;
    private ImportValidator $validator;
    private ImportTransformer $transformer;

    public function __construct(
        GoogleSheetsService $sheetsService,
        ImportValidator $validator,
        ImportTransformer $transformer
    ) {
        $this->sheetsService = $sheetsService;
        $this->validator = $validator;
        $this->transformer = $transformer;
    }

    /**
     * Import data from Google Sheets
     *
     * @param string $sheetUrl
     * @param string $range
     * @param string $modelName
     * @param bool $isDryRun
     * @param bool $isTruncate
     * @param bool $updateMode Whether to update existing records or only create new ones
     * @param array $uniqueFields Fields to use for finding existing records
     * @param array $updateFields Fields that should be updated (empty means all)
     * @return array
     */
    public function import(
        string $sheetName,
        string $range,
        string $modelName,
        bool $isDryRun = false,
        bool $isTruncate = false,
        bool $updateMode = false,
        array $uniqueFields = [],
        array $updateFields = []
    ): array {
        $config = $this->getModelConfig($modelName);
        
        if (!$config || !isset($config['model'])) {
            return [
                'success' => false,
                'message' => "Model {$modelName} not found"
            ];
        }
        
        $modelClass = $config['model'];
        
        try {
            $fullRange = $sheetName . '!' . $range;
            $data = $this->sheetsService->getSheetData($fullRange);

            if (empty($data)) {
                return [
                    'success' => false,
                    'message' => 'No data found'
                ];
            }

            // Transform data (headers to associative array)
            $transformedData = $this->sheetsService->convertToAssociativeArray($data);
            
            // Resolve model relations (foreign keys)
            $transformedData = $this->sheetsService->resolveModelRelations($transformedData, $modelName);

            if ($isDryRun) {
                return [
                    'success' => true,
                    'message' => 'Dry run completed successfully',
                    'data' => $transformedData,
                    'count' => count($transformedData)
                ];
            }

            if ($isTruncate) {
                $modelClass::truncate();
            }

            // Import the data based on mode (update or create only)
            if ($updateMode) {
                $result = $this->sheetsService->updateOrCreateRecords($modelClass, $transformedData, $uniqueFields, $updateFields);
                return [
                    'success' => true,
                    'message' => "Import completed: {$result['created']} records created, {$result['updated']} records updated, {$result['failed']} failed",
                    'created' => $result['created'],
                    'updated' => $result['updated'],
                    'failed' => $result['failed'],
                    'results' => $result['results']
                ];
            } else {
                // Regular create-only import
                $result = $modelClass::insert($transformedData);
                return [
                    'success' => $result,
                    'message' => $result ? 'Import completed successfully' : 'Import failed',
                    'count' => count($transformedData)
                ];
            }
        } catch (Exception $e) {
            Log::error('Import error: ' . $e->getMessage(), [
                'model' => $modelName,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Import error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Import data to database
     *
     * @param array $data
     * @param array $config
     * @param array $options
     * @return array
     */
    private function importToDatabase(array $data, array $config, array $options): array
    {
        $modelClass = $config['model'];
        $uniqueFields = $config['unique_fields'] ?? [];
        $batchSize = $options['batch_size'] ?? Config::get('import.defaults.batch_size', 100);
        $truncate = $options['truncate'] ?? false;
        $dryRun = $options['dry_run'] ?? false;
        $updateMode = $options['update_mode'] ?? false;
        $updateFields = $options['update_fields'] ?? [];

        $imported = 0;
        $updated = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            // Truncate table if requested
            if ($truncate && !$dryRun) {
                $modelClass::truncate();
                Log::info("Truncated table for model: {$modelClass}");
            }

            // Process data in batches
            $batches = array_chunk($data, $batchSize);
            
            foreach ($batches as $batchIndex => $batch) {
                foreach ($batch as $rowIndex => $row) {
                    try {
                        if ($dryRun) {
                            $imported++;
                            continue;
                        }

                        // If update mode is enabled, use custom updateOrCreate logic
                        if ($updateMode && !empty($uniqueFields)) {
                            // Use the custom method from GoogleSheetsService for more control
                            $result = $this->sheetsService->updateOrCreateRecords($modelClass, [$row], $uniqueFields, $updateFields);
                            $imported += $result['created'];
                            $updated += $result['updated'];
                        }
                        // Use updateOrCreate if unique fields are defined
                        else if (!empty($uniqueFields)) {
                            $uniqueData = [];
                            foreach ($uniqueFields as $field) {
                                if (isset($row[$field])) {
                                    $uniqueData[$field] = $row[$field];
                                }
                            }

                            if (!empty($uniqueData)) {
                                $model = $modelClass::updateOrCreate($uniqueData, $row);
                                if ($model->wasRecentlyCreated) {
                                    $imported++;
                                } else {
                                    $updated++;
                                }
                            } else {
                                $modelClass::create($row);
                                $imported++;
                            }
                        } else {
                            $modelClass::create($row);
                            $imported++;
                        }

                    } catch (Exception $e) {
                        $errors[] = "Batch {$batchIndex}, Row {$rowIndex}: " . $e->getMessage();
                        Log::error("Failed to import row", [
                            'batch' => $batchIndex,
                            'row' => $rowIndex,
                            'data' => $row,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            if (!$dryRun) {
                DB::commit();
            }

            $message = $dryRun 
                ? "Dry run completed successfully" 
                : "Import completed successfully";

            return [
                'success' => true,
                'message' => $message,
                'stats' => [
                    'imported_rows' => $imported,
                    'updated_rows' => $updated,
                    'error_count' => count($errors)
                ],
                'errors' => $errors
            ];

        } catch (Exception $e) {
            if (!$dryRun) {
                DB::rollBack();
            }

            return [
                'success' => false,
                'message' => 'Database import failed: ' . $e->getMessage(),
                'stats' => [
                    'imported_rows' => $imported,
                    'updated_rows' => $updated,
                    'error_count' => count($errors) + 1
                ],
                'errors' => array_merge($errors, [$e->getMessage()])
            ];
        }
    }

    /**
     * Get model configuration
     *
     * @param string $model
     * @return array|null
     */
    private function getModelConfig(string $model): ?array
    {
        $models = Config::get('import.models', []);
        
        // Handle different model name formats
        $modelKey = match($model) {
            'promo-codes' => 'promo_codes',
            'talent-professions' => 'talent_professions',
            default => $model
        };

        return $models[$modelKey] ?? null;
    }

    /**
     * Preview import data
     *
     * @param string $model
     * @param string $sheet
     * @param string|null $range
     * @param int $limit
     * @return array
     */
    public function preview(string $model, string $sheet, ?string $range = null, int $limit = 10): array
    {
        try {
            $config = $this->getModelConfig($model);
            if (!$config) {
                throw new Exception("Unknown model: {$model}");
            }

            $fullRange = $sheet . '!' . ($range ?: 'A1:Z' . ($limit + 1));
            $rawData = $this->sheetsService->getSheetDataAsAssociativeArray($fullRange);

            $previewData = array_slice($rawData, 0, $limit);
            
            return [
                'success' => true,
                'data' => $previewData,
                'columns' => !empty($previewData) ? array_keys($previewData[0]) : [],
                'total_rows' => count($rawData),
                'required_columns' => $config['required_columns'] ?? [],
                'optional_columns' => array_keys($config['column_mapping'] ?? [])
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Preview failed: ' . $e->getMessage(),
                'data' => [],
                'columns' => []
            ];
        }
    }
}
