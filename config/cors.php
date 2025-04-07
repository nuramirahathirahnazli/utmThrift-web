<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],  // Flutter app's origin
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
    'hosts' => [],
];



