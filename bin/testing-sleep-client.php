<?php
// --------------------------------------------------------------------------------
//  php -q /var/www/__project__/bin/testing-sleep-client.php
// --------------------------------------------------------------------------------
$basePath = dirname(__DIR__);
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);

//
$client = di('queue')->factoryClient();
$client->push('sleep');
