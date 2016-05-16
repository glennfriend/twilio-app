<?php
return [
    'dbname'    => conf('db.mysql.db'),
    'user'      => conf('db.mysql.user'),
    'password'  => conf('db.mysql.pass'),
    'host'      => conf('db.mysql.host'),
    'driver'    => 'pdo_mysql',
    'charset'   => 'utf8',
    'defaultTableOptions'   => [
        'charset'   => 'utf8',
        'collate'   => 'utf8_general_ci',
    ],
];