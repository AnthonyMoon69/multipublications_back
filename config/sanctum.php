<?php

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        env('APP_URL', 'localhost'),
        env('SANCTUM_STATEFUL_DOMAINS_SUFFIX', '')
    ))),

    'guard' => ['web'],

    'expiration' => null,

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),
];
