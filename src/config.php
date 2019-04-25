<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled?
    |--------------------------------------------------------------------------
    |
    | Enable or disable Klaviyo integration.
    |
    | When disabled, events will not queue nor fire to Klaviyo.
    |
    */

    'enabled' => env('KLAVIYO_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Your Klaviyo API key.
    |
    */

    'api_key' => env('KLAVIYO_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | The queue on which jobs will be processed.
    |
    */

    'queue' => env('KLAVIYO_QUEUE', 'klaviyo'),
];
