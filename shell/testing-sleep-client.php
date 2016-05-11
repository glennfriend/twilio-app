<?php

    // --------------------------------------------------------------------------------
    //  php -q /var/www/facebook-control-campaign-status/shell/run.php
    // --------------------------------------------------------------------------------

    $basePath = dirname(__DIR__);

    require_once $basePath . '/core/bootstrap.php';
    initialize($basePath);

    $client = di('queue')->factoryClient();
    $client->push('sleep');

