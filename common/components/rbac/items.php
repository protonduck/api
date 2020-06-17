<?php

return [
    'guest' => [
        'type' => 1,
        'description' => 'Guest',
    ],
    'user' => [
        'type' => 1,
        'description' => 'User',
        'children' => [
            'guest',
        ],
    ],
    'manager' => [
        'type' => 1,
        'description' => 'Manager',
        'children' => [
            'user',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Administrator',
        'children' => [
            'manager',
        ],
    ],
];