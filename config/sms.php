<?php

return [
    'default' => env('SMS_PROVIDER', 'kavenegar'),

    'kavenegar' => [
        'api_key' => env('KAVEHNEGAR_API_KEY', '12345'),
        'sender'  => env('KAVEHNEGAR_SENDER'),
    ],

    'ghasedak' => [
        'api_key' => env('GHASEDAK_API_KEY', 'fake-key'),
        'line_number' => env('GHASEDAK_LINE', '1000'),
    ],
];
