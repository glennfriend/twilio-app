<?php

    $managerRole = ['manager', 'developer'];

    return [
        'main_order' => 200,
        'main' => [
            'key'       => 'test-only',
            'label'     => 'test only',
            'link'      => url('/dashboard'),
            'role'      => '',
        ],
        'sub' => [
            [
                'key'       => 'test-only',
                'label'     => 'test only',
                'link'      => url('/dashboard'),
                'role'      => '',
            ],
        ],
    ];
