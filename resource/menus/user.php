<?php

    $managerRole = ['login'];

    return [
        'main_order' => 9000,
        'main' => [
            'key'       => 'me',
            'label'     => 'User',
            'link'      => url('/me-logs', ['actions' => 'login-success,login-fail']),
            'role'      => '',
        ],
        'sub' => [
            [
                'key'       => 'me-about',
                'label'     => 'About Me',
                'link'      => url('/me'),
                'role'      => '',
            ],
            [
                'key'       => 'me-change-password',
                'label'     => 'Change Password',
                'link'      => url('/me-change-password'),
                'role'      => '',
            ],
            [
                'key'       => 'me-logs',
                'label'     => 'Show Logs',
                'link'      => url('/me-logs', ['actions' => 'login-success,login-fail']),
                'role'      => '',
            ],
        ],
    ];
