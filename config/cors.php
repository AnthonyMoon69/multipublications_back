<?php

$origins = array_map(
    'trim',
    explode(',', env('CORS_ALLOWED_ORIGINS', env('FRONTEND_URL', '')))
);
$origins = array_values(array_filter($origins, fn ($origin) => $origin !== ''));

$explicitWildcard = in_array('*', $origins, true);
if ($explicitWildcard) {
    $origins = ['*'];
}

$supportsCredentials = env('CORS_SUPPORTS_CREDENTIALS');
$supportsCredentials = filter_var(
    $supportsCredentials,
    FILTER_VALIDATE_BOOLEAN,
    ['flags' => FILTER_NULL_ON_FAILURE]
);

if (empty($origins)) {
    $origins = [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ];
    $supportsCredentials = $supportsCredentials ?? true;
} elseif ($origins === ['*']) {
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
