<?php

return [
    'project_slug' => env('PAKASIR_PROJECT_SLUG', ''),
    'api_key'      => env('PAKASIR_API_KEY', ''),
    'base_url'     => env('PAKASIR_BASE_URL', 'https://app.pakasir.com'),
    'sandbox_mode' => (bool) env('PAKASIR_SANDBOX_MODE', false),
];
