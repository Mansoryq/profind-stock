<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Sheets Import Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file defines the mapping between Google Sheets columns
    | and database fields for different models. It also includes validation
    | rules and transformation options.
    |
    */

    'models' => [
        'talents' => [
            'table' => 'talents',
            'model' => \App\Models\Talent::class,
            'required_columns' => ['name', 'description'],
            'column_mapping' => [
                'name' => 'name',
                'description' => 'description',
                'short_description' => 'short_description',
                'advice' => 'advice',
                'icon' => 'icon',
                'domain' => 'talent_domain_id', // Special handling required
            ],
            'validation_rules' => [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'short_description' => 'nullable|string|max:500',
                'advice' => 'nullable|string',
                'icon' => 'nullable|string|max:255',
                'domain' => 'nullable|string|max:255',
            ],
            'transformations' => [
                'domain' => 'resolve_talent_domain', // Custom transformation
            ],
            'unique_fields' => ['name'],
        ],

        'spheres' => [
            'table' => 'spheres',
            'model' => \App\Models\Sphere::class,
            'required_columns' => ['name'],
            'column_mapping' => [
                'name' => 'name',
                'name_kz' => 'name_kz',
                'name_en' => 'name_en',
                'description' => 'description',
                'description_kz' => 'description_kz',
                'description_en' => 'description_en',
                'color' => 'color',
                'icon' => 'icon',
                'sort_order' => 'sort_order',
                'is_active' => 'is_active',
            ],
            'validation_rules' => [
                'name' => 'required|string|max:255',
                'name_kz' => 'nullable|string|max:255',
                'name_en' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'description_kz' => 'nullable|string',
                'description_en' => 'nullable|string',
                'color' => 'nullable|string|max:7',
                'icon' => 'nullable|string|max:255',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
            ],
            'transformations' => [
                'sort_order' => 'to_integer',
                'is_active' => 'to_boolean',
            ],
            'unique_fields' => ['name'],
        ],

        'professions' => [
            'table' => 'professions',
            'model' => \App\Models\Profession::class,
            'required_columns' => ['name', 'sphere'],
            'column_mapping' => [
                'name' => 'name',
                'description' => 'description',
                'sphere' => 'sphere_id', // Special handling required
                'is_active' => 'is_active',
            ],
            'validation_rules' => [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'sphere' => 'required|string|max:255',
                'is_active' => 'nullable|boolean',
            ],
            'transformations' => [
                'sphere' => 'resolve_sphere_id', // Custom transformation
                'is_active' => 'to_boolean',
            ],
            'unique_fields' => ['name', 'sphere_id'],
        ],

        'answers' => [
            'table' => 'answers',
            'model' => \App\Models\Answer::class,
            'required_columns' => ['question', 'talent'],
            'column_mapping' => [
                'question' => 'question',
                'talent' => 'talent_id', // Special handling required
                'value' => 'value',
                'order' => 'order',
            ],
            'validation_rules' => [
                'question' => 'required|string',
                'talent' => 'required|string|max:255',
                'value' => 'nullable|integer|min:1|max:5',
                'order' => 'nullable|integer|min:0',
            ],
            'transformations' => [
                'talent' => 'resolve_talent_id', // Custom transformation
                'value' => 'to_integer',
                'order' => 'to_integer',
            ],
            'unique_fields' => ['question', 'talent_id'],
        ],

        'promo_codes' => [
            'table' => 'promo_codes',
            'model' => \App\Models\PromoCode::class,
            'required_columns' => ['code'],
            'column_mapping' => [
                'code' => 'code',
                'description' => 'description',
                'is_active' => 'is_active',
                'is_used' => 'is_used',
            ],
            'validation_rules' => [
                'code' => 'required|string|max:20', // Modified to be more flexible
                'description' => 'nullable|string|max:255',
                'is_active' => 'nullable|boolean',
                'is_used' => 'nullable|boolean',
            ],
            'transformations' => [
                'code' => 'format_promo_code',
                'is_active' => 'to_boolean',
                'is_used' => 'to_boolean',
            ],
            'unique_fields' => ['code'],
        ],

        'talent_professions' => [
            'table' => 'profession_talent',
            'is_pivot' => true,
            'required_columns' => ['talent', 'profession', 'coefficient'],
            'column_mapping' => [
                'talent' => 'talent_id',
                'profession' => 'profession_id',
                'coefficient' => 'coefficient',
            ],
            'validation_rules' => [
                'talent' => 'required|string|max:255',
                'profession' => 'required|string|max:255',
                'coefficient' => 'required|numeric|min:0|max:1',
            ],
            'transformations' => [
                'talent' => 'resolve_talent_id',
                'profession' => 'resolve_profession_id',
                'coefficient' => 'to_float',
            ],
            'unique_fields' => ['talent_id', 'profession_id'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Import Settings
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'batch_size' => 100,
        'max_rows' => 10000,
        'timeout' => 300, // 5 minutes
        'memory_limit' => '512M',
    ],

    /*
    |--------------------------------------------------------------------------
    | Transformation Functions
    |--------------------------------------------------------------------------
    |
    | Define custom transformation functions that can be applied to column values
    | during import. These functions should be defined in the ImportTransformer class.
    |
    */
    'transformations' => [
        'to_boolean' => [
            'true' => true,
            'false' => false,
            '1' => true,
            '0' => false,
            'yes' => true,
            'no' => false,
            'да' => true,
            'нет' => false,
        ],
        'to_integer' => [
            'default' => 0,
            'nullable' => true,
        ],
        'to_float' => [
            'default' => 0.0,
            'nullable' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Import Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'skip_invalid_rows' => true,
        'log_errors' => true,
        'max_errors' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Sheets Settings
    |--------------------------------------------------------------------------
    */
    'google_sheets' => [
        'default_range' => 'A:Z',
        'header_row' => 1,
        'data_start_row' => 2,
        'max_columns' => 26, // A-Z
    ],
];
