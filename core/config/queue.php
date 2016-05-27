<?php

return [

    'gearman' => [
        'servers'   => ['127.0.0.1:4730'],  // ['192.168.0.1:1001', '192.168.0.2::4730']
        'services'  => ['sleep'],           // ['sleep,rebuildSearchTable']
    ],

];
