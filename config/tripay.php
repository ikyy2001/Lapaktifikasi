<?php

$mode = strtolower(env('TRIPAY_MODE', 'sandbox'));
$isProduction = in_array($mode, ['production', 'live', 'prod'], true);
$defaultBaseUrl = $isProduction
    ? 'https://tripay.co.id/api/'
    : 'https://tripay.co.id/api-sandbox/';

return [
    'mode' => $mode,
    'is_production' => $isProduction,
    'api_key' => env('TRIPAY_API_KEY', ''),
    'private_key' => env('TRIPAY_PRIVATE_KEY', ''),
    'merchant_code' => env('TRIPAY_MERCHANT_CODE', ''),
    'base_url' => rtrim(env('TRIPAY_BASE_URL', $defaultBaseUrl), '/') . '/',
    'channel_cache_ttl' => (int) env('TRIPAY_CHANNEL_CACHE_TTL', 3600 * 6), // 6 jam
];
