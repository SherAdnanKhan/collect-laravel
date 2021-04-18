<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */

    'supportsCredentials' => false,
    'allowedOrigins' => [
        'http://veva.local:3000',
        'http://localhost:3000',
        'https://veva-studio-collect.netlify.com',
        'https://veva-studio-collect.netlify.app',
        'https://prod.vevacollect.com',
        'https://app.vevacollect.com',
        'https://staging.vevacollect.com',
        'https://staging.app.vevacollect.com',
    ],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
