<?php

    $managerRole = ['login'];

    return [
        'main_order' => 20000,
        'main' => [
            'key'       => 'other',
            'label'     => 'Other',
            'link'      => url('/twilio/test'),
            'role'      => '',
        ],
        'sub' => [
            [
                'key'       => 'twilio-test',
                'label'     => 'Twilio test',
                'link'      => url('/twilio/test'),
                'role'      => '',
            ],
        ],
    ];
