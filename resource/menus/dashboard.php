<?php

    $managerRole = ['manager', 'developer'];

    return [
        'main_order' => 100,
        'main' => [
            'key'       => 'dashboard',
            'label'     => 'Dashboard',
            'link'      => url('/dashboard'),
            'role'      => '',
        ],
        'sub' => [
            [
                'key'   => 'dashboard-1',
                'label' => 'Dashboard 1',
                'link'  => url('/dashboard/1'),
                'role'  => '',
            ],
            [
                'key'   => 'dashboard-2',
                'label' => 'Dashboard 2',
                'link'  => url('/dashboard/2'),
                'role'  => '',
            ],
            [
                'key'   => 'dashboard-3',
                'label' => 'Dashboard 3',
                'link'  => url('/dashboard/3'),
                'role'  => $managerRole,
            ],
        ],
    ];
