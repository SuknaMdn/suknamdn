<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'moyasar' => [
        'api_key' => env('MOYASAR_API_KEY'),
        'secret_key' => env('MOYASAR_SECRET_KEY'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'nafath' => [
        'base_url' => env('NAFATH_BASE_URL', 'https://nafath.api.elm.sa/stg'),
        'api_key' => env('NAFATH_API_KEY'),
        'api_id' => env('NAFATH_APP_ID'),
        'client_secret' => env('NAFATH_CLIENT_SECRET'),
        'max_retries' => env('NAFATH_MAX_RETRIES', 3), // number of retries
        'timeout' => env('NAFATH_TIMEOUT', 10000), // seconds (10 seconds)
        'retry_delay' => env('NAFATH_RETRY_DELAY', 1000), // milliseconds
        'api_stg_url' => env('NAFATH_API_STG_URL', 'https://nafath.api.elm.sa/stg'),

    ],
    'msegat' => [
        'username' => env('MSEGAT_USERNAME'),
        'api_key'  => env('MSEGAT_API_KEY'),
        'sender'   => env('MSEGAT_DEFAULT_SENDER'),
    ],

];
