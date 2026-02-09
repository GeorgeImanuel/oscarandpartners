<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    | This value is the version of your application.
    | [flow update].[feature update].[bug fix update]
    */
    'version' => '0.2.1',
    /*
    |--------------------------------------------------------------------------
    | Card Status IDs
    |--------------------------------------------------------------------------
    | Card status identifiers used throughout the application
    */
    'card_status' => [
        'active' => 1,
        'gress_period' => 2,
        'lewat_deadline' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Card Type IDs
    |--------------------------------------------------------------------------
    | Card type identifiers used throughout the application
    */
    'card_type' => [
        'new' => 1,
        'renewal' => 2,
        'endors' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Status IDs
    |--------------------------------------------------------------------------
    | Payment status identifiers used throughout the application
    */
    'payment_status' => [
        'pending' => 1,
        'completed' => 2,
        'failed' => 3,
        'refunded' => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Type IDs
    |--------------------------------------------------------------------------
    | Payment type identifiers used throughout the application
    */
    'payment_type' => [
        'penuh' => 1,
        'cicilan' => 2,
    ],
];
