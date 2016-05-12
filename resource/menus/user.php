<?php

    $managerRole = ['manager', 'developer'];

    return [
        'main_order' => 9000,
        'main' => [
            'key'       => 'user-logout',
            'label'     => 'Logout',
            'link'      => cc('homeUrl' ,'/logout'),
            'role'      => '',
        ],
        'sub' => [],
    ];
