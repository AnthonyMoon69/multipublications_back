<?php

$origins = array_values(array_filter(array_map(
    'trim',
    explode(',', env('CORS_ALLOWED_ORIGINS', env('FRONTEND_URL', '')))
)));

$supportsCredentials = env('CORS_SUPPORTS_CREDENTIALS');
$supportsCredentials = filter_var(
    $supportsCredentials,
    FILTER_VALIDATE_BOOLEAN,
    ['flags' => FILTER_NULL_ON_FAILURE]
);

if (empty($origins)) {
    $origins = ['*'];
    $supportsCredentials = $supportsCredentials ?? false;
} else {
    $supportsCredentials = $supportsCredentials ?? true;
}

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => $origins,

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => $supportsCredentials,
];
