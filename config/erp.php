<?php

return [
    'enabled' => env('ERP_SYNC_ENABLED', false),
    'base_url' => env('ERP_BASE_URL', ''),
    'api_key' => env('ERP_API_KEY', ''),
    'timeout' => env('ERP_TIMEOUT', 30),
    'webhook_secret' => env('ERP_WEBHOOK_SECRET', ''),
];
