<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class ImportValidator
{
    private array $errors = [];
    private array $warnings = [];
    private int $validRows = 0;
    private int $invalidRows = 0;

    /**
     * Validate import data
     *
     * @param array $data
     * @param array $config
     * @return array
     */
    public function validate(array $data, array $config): array
    {
        $this->resetCounters();
        $validatedData = [];
        $rules = $config['validation_rules'] ?? [];
        $requiredColumns = $config['required_columns'] ?? [];

        // First, validate that required columns exist
        if (!$this->validateRequiredColumns($data, $requiredColumns)) {
            return [
                'valid' => false,
                'data' => [],
                'errors' => $this->errors,
                'warnings' => $this->warnings,
                'stats' => $this->getStats()
            ];
        }

        // Validate each row
        foreach ($data as $index => $row) {
            $rowNumber = $index + 2; // Account for header row
            
            try {
                $validationResult = $this->validateRow($row, $rules, $rowNumber);
                
                if ($validationResult['valid']) {
                    $validatedData[] = $validationResult['data'];
                    $this->validRows++;
                } else {
                    $this->invalidRows++;
                    $this->errors = array_merge($this->errors, $validationResult['errors']);
                }
                
                if (!empty($validationResult['warnings'])) {
                    $this->warnings = array_merge($this->warnings, $validationResult['warnings']);
                }
                
            } catch (Exception $e) {
                $this->invalidRows++;
                $this->errors[] = "Row {$rowNumber}: Validation failed - " . $e->getMessage();
                Log::error("Row validation failed", [
                    'row' => $rowNumber,
                    'data' => $row,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'valid' => empty($this->errors) || $this->validRows > 0,
            'data' => $validatedData,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'stats' => $this->getStats()
        ];
    }

    /**
     * Validate that required columns exist in the data
     *
     * @param array $data
     * @param array $requiredColumns
     * @return bool
     */
    private function validateRequiredColumns(array $data, array $requiredColumns): bool
    {
        if (empty($data)) {
            $this->errors[] = "No data provided for validation";
            return false;
        }

        $firstRow = $data[0];
        $availableColumns = array_keys($firstRow);
        $missingColumns = array_diff($requiredColumns, $availableColumns);

        if (!empty($missingColumns)) {
            $this->errors[] = "Missing required columns: " . implode(', ', $missingColumns);
            return false;
        }

        return true;
    }

    /**
     * Validate a single row of data
     *
     * @param array $row
     * @param array $rules
     * @param int $rowNumber
     * @return array
     */
    private function validateRow(array $row, array $rules, int $rowNumber): array
    {
        $errors = [];
        $warnings = [];
        $isValid = true;

        // Skip empty rows
        if ($this->isEmptyRow($row)) {
            $warnings[] = "Row {$rowNumber}: Empty row skipped";
            return [
                'valid' => false,
                'data' => null,
                'errors' => [],
                'warnings' => $warnings
            ];
        }

        // Use Laravel's validator
        $validator = Validator::make($row, $rules);

        if ($validator->fails()) {
            $isValid = false;
            foreach ($validator->errors()->all() as $error) {
                $errors[] = "Row {$rowNumber}: {$error}";
            }
        }

        // Additional custom validations
        $customValidationResult = $this->performCustomValidations($row, $rowNumber);
        if (!$customValidationResult['valid']) {
            $isValid = false;
            $errors = array_merge($errors, $customValidationResult['errors']);
        }
        $warnings = array_merge($warnings, $customValidationResult['warnings']);

        return [
            'valid' => $isValid,
            'data' => $isValid ? $row : null,
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }

    /**
     * Check if a row is empty
     *
     * @param array $row
     * @return bool
     */
    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if (!empty(trim((string)$value))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Perform custom validations specific to the data
     *
     * @param array $row
     * @param int $rowNumber
     * @return array
     */
    private function performCustomValidations(array $row, int $rowNumber): array
    {
        $errors = [];
        $warnings = [];
        $isValid = true;

        // Validate promo code format if present
        if (isset($row['code'])) {
            if (!preg_match('/^\d{6}$/', $row['code'])) {
                $errors[] = "Row {$rowNumber}: Invalid promo code format (must be 6 digits)";
                $isValid = false;
            }
        }

        // Validate email format if present
        if (isset($row['email']) && !empty($row['email'])) {
            if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$rowNumber}: Invalid email format";
                $isValid = false;
            }
        }

        // Validate URL format if present
        if (isset($row['url']) && !empty($row['url'])) {
            if (!filter_var($row['url'], FILTER_VALIDATE_URL)) {
                $warnings[] = "Row {$rowNumber}: Invalid URL format";
            }
        }

        // Validate color format if present
        if (isset($row['color']) && !empty($row['color'])) {
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $row['color'])) {
                $warnings[] = "Row {$rowNumber}: Invalid color format (should be hex color like #FF0000)";
            }
        }

        // Check for potential duplicates within the same import
        // This would require additional context about the full dataset

        return [
            'valid' => $isValid,
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }

    /**
     * Reset validation counters
     */
    private function resetCounters(): void
    {
        $this->errors = [];
        $this->warnings = [];
        $this->validRows = 0;
        $this->invalidRows = 0;
    }

    /**
     * Get validation statistics
     *
     * @return array
     */
    private function getStats(): array
    {
        return [
            'total_rows' => $this->validRows + $this->invalidRows,
            'valid_rows' => $this->validRows,
            'invalid_rows' => $this->invalidRows,
            'error_count' => count($this->errors),
            'warning_count' => count($this->warnings),
            'success_rate' => $this->validRows + $this->invalidRows > 0 
                ? round(($this->validRows / ($this->validRows + $this->invalidRows)) * 100, 2) 
                : 0
        ];
    }

    /**
     * Get all errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get all warnings
     *
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * Check if validation passed
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors) && $this->validRows > 0;
    }

    /**
     * Get validation summary
     *
     * @return string
     */
    public function getSummary(): string
    {
        $stats = $this->getStats();
        
        return sprintf(
            "Validation completed: %d total rows, %d valid (%.1f%%), %d invalid, %d errors, %d warnings",
            $stats['total_rows'],
            $stats['valid_rows'],
            $stats['success_rate'],
            $stats['invalid_rows'],
            $stats['error_count'],
            $stats['warning_count']
        );
    }
}
