<?php
/**
 *  這裡的設定值, 將會覆蓋 core/config/ 下相同名稱的值
 */
return [
    'app' => [
        /**
         *  不論在任何情況下, 正式的環境不予許改變該值
         */
        'env' => 'production',
    ],
    'db' => [
        'mysql' => [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db'   => 'twilio_tool',
        ],
    ],
    'twilio' => [
        'sid'   => 'please setting',
        'token' => 'please setting',
    ],
];
