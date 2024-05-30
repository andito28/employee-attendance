<?php

return [

    'credentials' => [

        'testing' => [
            'id' => env('OPEN_API_TESTING_ID', ''),
            'key' => env('OPEN_API_TESTING_KEY', '')
        ],

    ],

    'clients' => [

        'example' => [
            'host' => env('EXAMPLE_HOST', ''),
            'client-id' => env('EXAMPLE_CLIENT_ID', ''),
            'client-name' => env('EXAMPLE_CLIENT_NAME', ''),
            'client-secret' => env('EXAMPLE_CLIENT_SECRET', ''),
        ],

    ]

];
