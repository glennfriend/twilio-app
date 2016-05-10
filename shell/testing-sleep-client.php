<?php

    // --------------------------------------------------------------------------------
    //  php -q /var/www/facebook-control-campaign-status/shell/run.php
    // --------------------------------------------------------------------------------

    $basePath = dirname(__DIR__);
    require_once $basePath . '/app/bootstrap.php';
    initialize($basePath, 'queue');

    $client = di('queue')->factoryClient();
    $client->push('sleep');

