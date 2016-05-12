<?php

    $managerRole    = ['manager', 'developer'];
    $developerRole  = ['developer'];

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
                'key'   => 'dashboard-user',
                'label' => 'User Dashboard',
                'link'  => url('/dashboard'),
                'role'  => '',
            ],
            [
                'key'   => 'dashboard-manager',
                'label' => 'Manager Dashboard',
                'link'  => url('/dashboard', ['by'=>'manager']),
                'role'  => $managerRole,
            ],
            [
                'key'   => 'dashboard-developer',
                'label' => 'Developer Dashboard',
                'link'  => url('/dashboard', ['by'=>'developer']),
                'role'  => $developerRole,
            ],
        ],
    ];
