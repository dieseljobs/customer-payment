<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Customer Payment Processor
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment processor that will be
    | implemented when using this library.
    |
    */

    'default' => env('PAYMENT_PROCESSOR', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Payment Processors
    |--------------------------------------------------------------------------
    |
    | Available payment processors
    |
    */

    'drivers' => [

        'stripe' => [
            'api_key' => env('STRIPE_KEY', ''),
            'api_secret' => env('STRIPE_SECRET', ''),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Customer Model
    |--------------------------------------------------------------------------
    |
    | Specify the model class used for customer and payment profile data
    |
    */

    'model' => App\Models\User::class,

];
