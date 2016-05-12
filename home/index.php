<?php
$basePath = dirname(__DIR__);
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);
$app = ProjectHelper::buildApp();

// public
$app->get('/',              'App\Controllers\Publicly\Home:defaultPage');
$app->get('/status/{type}', 'App\Controllers\Publicly\Home:status');
$app->get('/login',         'App\Controllers\Publicly\Auth:login');
$app->post('/login',        'App\Controllers\Publicly\Auth:login');

$app->run();
