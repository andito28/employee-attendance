<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Version & Service
     |--------------------------------------------------------------------------
     |
     | 1. Default version is v1. You can set up versioning in directories
     |    Algorithms, Controllers, Requests, Models and routes
     | 2. Set your service name for routing, using kebab case. Ex: customer-supports, work-orders
     |
     */

    'version' => env('VERSION', 'v1'),

    'service' => env('SERVICE_NAME', ''),


    /*
     |--------------------------------------------------------------------------
     | Base Prefix & Namespace
     |--------------------------------------------------------------------------
     |
     | Set up base endpoint for web, mobile and B2B (default)
     | Set up base namespace controller for web, mobile and B2B (default)
     |
     */

    'prefix' => [

        'web' => env('BASE_WEB_PREFIX', 'api/web'),

        'mobile' => env('BASE_MOBILE_PREFIX', 'api/mobile'),

        'mygx' => env('BASE_MYGX_PREFIX', 'api/mygx'),

    ],

    'namespace' => [

        'web' => env('BASE_WEB_NAMESPACE', 'Web'),

        'mobile' => env('BASE_MOBILE_NAMESPACE', 'Mobile'),

        'mygx' => env('BASE_MYGX_NAMESPACE', 'MyGX'),

    ],

    'dev-emails' => [
        'yuswa98@gmail.com'
    ],

    'storage-link' => env('API_GATEWAY_LINK_URL', 'http://localhost/'),

];
