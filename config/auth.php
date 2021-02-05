<?php

return [
    'defaults' => [
        'guard' => 'customer',
        'passwords' => 'customers',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'jwt',
            'provider' => 'admins',
        ],
        'super_admin' => [
            'driver' => 'jwt',
            'provider' => 'admins',
        ],
        'customer' => [
            'driver' => 'jwt',
            'provider' => 'customers',
        ],
        'collector' => [
            'driver' => 'jwt',
            'provider' => 'collectors',
        ],
    ],

    'providers' => [
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'collectors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Agent::class,
        ]
    ],
];
