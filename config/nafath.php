<?php

return [
    'api_url' => env('NAFATH_API_URL', 'https://nafath.api.elm.sa'),
    'app_id' => env('NAFATH_APP_ID', ''),
    'app_key' => env('NAFATH_APP_KEY', ''),
    'callback_url' => env('NAFATH_CALLBACK_URL', ''),
    'timeout' => env('NAFATH_TIMEOUT', 30),
];
// Compare this snippet from app/Services/NafathAuthService.php:
