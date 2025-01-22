<?php

return [
    '1C' => [
        'uri' => env('SPARK_1C_URL'),
        'buh_uri' => env('SPARK_1C_BUH_URI'),

        'token' => [
            'mobile_app' => env('SPARK_1C_MOBILE_APP_TOKEN'),
            'main'       => env('SPARK_1C_MAIN_TOKEN'),
            'buh_main'   => env('SPARK_1C_BUH_TOKEN')
        ],

        'basic_auth' => [
            'login'    => env('SPARK_1C_LOGIN'),
            'password' => env('SPARK_1C_PASSWORD')
        ],
    ],
    'cabinet' => env('SPARK_CABINET_URL', 'https://go.spark.kz'),
    'bpms'    => env('SPARK_BPMS_URL', 'https://bpms.spark.kz'),
    'mindsale' => [
        'url' => env('MINDSALE_URL')
    ]
];
