<?php

return [
    'paths' => ['api/*', '/sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],  
    'allowed_origins_patterns' => ['/^http:\/\/(localhost|127\.0\.0\.1):\d+$/'],
    'allowed_headers' => ['*', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
    'hosts' => [],
];



