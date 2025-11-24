<?php

namespace App\Services;

use App\Models\Talent;
use App\Models\TalentDomain;
use App\Models\Sphere;
use Illuminate\Support\Facades\Log;
use Exception;

class ImportTransformer
{
    /**
     * Transform a value based on the transformation type
     *
     * @param mixed $value
     * @param string $transformation
     * @param array $options
     * @return mixed
     */
    public static function transform($value, string $transformation, array $options = [])
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        return match($transformation) {
            'to_boolean' => self::toBoolean($value),
            'to_integer' => self::toInteger($value),
            'format_promo_code' => self::formatPromoCode($value),
            'resolve_talent_domain' => self::resolveTalentDomain($value),
            'resolve_sphere_id' => self::resolveSphereId($value),
            'resolve_talent_id' => self::resolveTalentId($value),
            default => $value
        };
    }

    /**
     * Convert value to boolean
     *
     * @param mixed $value
     * @return bool
     */
    private static function toBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $value = strtolower(trim((string)$value));
        $trueValues = ['true', '1', 'yes', 'да', 'y', 'on'];
        
        return in_array($value, $trueValues);
    }

    /**
     * Convert value to integer
     *
     * @param mixed $value
     * @return int|null
     */
    private static function toInteger($value): ?int
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        return 0;
    }
    
    /**
     * Format promo code to ensure it matches the required pattern
     *
     * @param string $value
     * @return string
     */
    private static function formatPromoCode($value): string
    {
        // Remove any non-digit characters
        $digits = preg_replace('/\D/', '', (string)$value);
        
        // Pad with zeros if less than 6 digits
        if (strlen($digits) < 6) {
            $digits = str_pad($digits, 6, '0', STR_PAD_LEFT);
        }
        
        // Truncate if more than 6 digits
        if (strlen($digits) > 6) {
            $digits = substr($digits, 0, 6);
        }
        
        return $digits;
    }

    /**
     * Resolve talent domain name to ID
     *
     * @param string $domainName
     * @return int|null
     */
    private static function resolveTalentDomain(string $domainName): ?int
    {
        if (empty(trim($domainName))) {
            return null;
        }

        try {
            $domain = TalentDomain::firstOrCreate(['name' => trim($domainName)]);
            return $domain->id;
        } catch (Exception $e) {
            Log::error("Failed to resolve talent domain: {$domainName}", [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Resolve sphere name to ID
     *
     * @param string $sphereName
     * @return int|null
     */
    private static function resolveSphereId(string $sphereName): ?int
    {
        if (empty(trim($sphereName))) {
            return null;
        }

        try {
            $sphere = Sphere::where('name', trim($sphereName))->first();
            
            if (!$sphere) {
                Log::warning("Sphere not found: {$sphereName}");
                return null;
            }

            return $sphere->id;
        } catch (Exception $e) {
            Log::error("Failed to resolve sphere: {$sphereName}", [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Resolve talent name to ID
     *
     * @param string $talentName
     * @return int|null
     */
    private static function resolveTalentId(string $talentName): ?int
    {
        if (empty(trim($talentName))) {
            return null;
        }

        try {
            $talent = Talent::where('name', trim($talentName))->first();
            
            if (!$talent) {
                Log::warning("Talent not found: {$talentName}");
                return null;
            }

            return $talent->id;
        } catch (Exception $e) {
            Log::error("Failed to resolve talent: {$talentName}", [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Apply all transformations to a row of data
     *
     * @param array $row
     * @param array $config
     * @return array
     */
    public static function transformRow(array $row, array $config): array
    {
        $transformedRow = [];
        $transformations = $config['transformations'] ?? [];
        $columnMapping = $config['column_mapping'] ?? [];

        foreach ($row as $column => $value) {
            // Get the database field name
            $dbField = $columnMapping[$column] ?? $column;
            
            // Apply transformation if defined
            if (isset($transformations[$column])) {
                $value = self::transform($value, $transformations[$column]);
            }

            $transformedRow[$dbField] = $value;
        }

        return $transformedRow;
    }

    /**
     * Validate transformed data against rules
     *
     * @param array $data
     * @param array $rules
     * @return array
     */
    public static function validateData(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            // Check required fields
            if (str_contains($rule, 'required') && (is_null($value) || $value === '')) {
                $errors[] = "Field '{$field}' is required";
                continue;
            }

            // Check string max length
            if (preg_match('/max:(\d+)/', $rule, $matches)) {
                $maxLength = (int)$matches[1];
                if (is_string($value) && strlen($value) > $maxLength) {
                    $errors[] = "Field '{$field}' exceeds maximum length of {$maxLength}";
                }
            }

            // Check integer constraints
            if (str_contains($rule, 'integer') && !is_null($value) && !is_int($value)) {
                $errors[] = "Field '{$field}' must be an integer";
            }

            // Check boolean constraints
            if (str_contains($rule, 'boolean') && !is_null($value) && !is_bool($value)) {
                $errors[] = "Field '{$field}' must be a boolean";
            }
        }

        return $errors;
    }

    /**
     * Clean and prepare data for database insertion
     *
     * @param array $data
     * @return array
     */
    public static function cleanData(array $data): array
    {
        $cleaned = [];

        foreach ($data as $key => $value) {
            // Skip null values for optional fields
            if (is_null($value)) {
                continue;
            }

            // Trim string values
            if (is_string($value)) {
                $value = trim($value);
                // Skip empty strings
                if ($value === '') {
                    continue;
                }
            }

            $cleaned[$key] = $value;
        }

        return $cleaned;
    }
}
