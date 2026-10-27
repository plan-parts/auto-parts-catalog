<?php

return [

    /*
    |--------------------------------------------------------------------------
    | plan.parts API Token
    |--------------------------------------------------------------------------
    |
    | Your personal API token from https://plan.parts
    | Register at https://plan.parts/register to get your token.
    |
    */

    'token' => env('PLAN_PARTS_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    */

    'base_url' => env('PLAN_PARTS_BASE_URL', 'https://cdn.plan.parts/api'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout (seconds)
    |--------------------------------------------------------------------------
    */

    'timeout' => env('PLAN_PARTS_TIMEOUT', 15),

    /*
    |--------------------------------------------------------------------------
    | Route Prefix & Middleware
    |--------------------------------------------------------------------------
    |
    | The catalog web UI will be available at /{prefix}
    | Default: /auto-parts
    |
    */

    'route_prefix' => env('PLAN_PARTS_CATALOG_PREFIX', 'auto-parts'),

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Response Cache
    |--------------------------------------------------------------------------
    |
    | Cache API responses to reduce token consumption on repeated browsing.
    | Set 'enabled' to false to disable caching entirely.
    |
    */

    'cache' => [
        'enabled' => env('PLAN_PARTS_CACHE', true),
        'ttl'     => env('PLAN_PARTS_CACHE_TTL', 3600), // seconds
        'store'   => env('PLAN_PARTS_CACHE_STORE', null), // null = default cache store
    ],

    /*
    |--------------------------------------------------------------------------
    | Display Language
    |--------------------------------------------------------------------------
    */

    'lang' => env('PLAN_PARTS_LANG', 'en'),

];
