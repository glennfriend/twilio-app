<?php
$basePath = dirname(dirname(__DIR__));
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);
$app = ProjectHelper::buildApp();

// admin system
$app->get('/dashboard',     'App\Controllers\Dashboard\Home:defaultPage');

// developer tool
$app->get('/help',          'App\Controllers\Developer\Help:help');
$app->get('/help-info',     'App\Controllers\Developer\Help:info');

$app->run();
