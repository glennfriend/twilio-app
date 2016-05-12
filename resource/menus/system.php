<?php

    $managerRole   = ['manager', 'developer'];
    // $developerRole = ['developer'];

    return [
        'main_order' => 800,
        'main' => [
            'key'       => 'system',
            'label'     => 'System',
            'link'      => url('/dashboard'),
            'role'      => $managerRole,
        ],
        'sub' => [
            [
                'key'   => 'system-environment',
                'label' => 'Environment',
                'link'  => url('/system-environment'),
                'role'  => $managerRole,
            ],
            [
                'key'   => 'system-config',
                'label' => 'Config',
                'link'  => url('/system-config'),
                'role'  => $managerRole,
            ],
        ],
    ];
