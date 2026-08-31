<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shipping Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for shipping providers and default settings
    |
    */

    'origin_city' => env('SHIPPING_ORIGIN_CITY', 'Kendal'),
    'origin_district' => env('SHIPPING_ORIGIN_DISTRICT', 'Kendal'),
    'origin_postal_code' => env('SHIPPING_ORIGIN_POSTAL_CODE', '51311'),

    /*
    |--------------------------------------------------------------------------
    | Default Courier Settings
    |--------------------------------------------------------------------------
    */
    'default_courier' => env('SHIPPING_DEFAULT_COURIER', 'jne'),
    'default_service' => env('SHIPPING_DEFAULT_SERVICE', 'JNE REG'),

    /*
    |--------------------------------------------------------------------------
    | Provider API Credentials
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'jne' => [
            'api_key' => env('JNE_API_KEY'),
            'is_active' => env('JNE_IS_ACTIVE', true),
        ],
        'jnt' => [
            'api_key' => env('JNT_API_KEY'),
            'is_active' => env('JNT_IS_ACTIVE', true),
        ],
        'sicepat' => [
            'api_key' => env('SICEPAT_API_KEY'),
            'is_active' => env('SICEPAT_IS_ACTIVE', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache_duration' => env('SHIPPING_CACHE_DURATION', 3600), // 1 hour

];