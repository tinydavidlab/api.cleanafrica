<?php

return [
    'defaults' => [
        'guard'     => 'customer',
        'passwords' => 'customers',
    ],

    'guards' => [
        'admin'       => [
            'driver'   => 'passport',
            'provider' => 'admins',
        ],
        'super_admin' => [
            'driver'   => 'passport',
            'provider' => 'admins',
        ],
        'customer'    => [
            'driver'   => 'passport',
            'provider' => 'customers',
        ],
        'collector'   => [
            'driver'   => 'passport',
            'provider' => 'collectors',
        ],
    ],

    'providers' => [
        'customers' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Customer::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class,
        ],

        'collectors' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Agent::class,
        ],
    ],
];
