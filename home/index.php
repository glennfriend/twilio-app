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

// admin system
$app->get('/dashboard',     'App\Controllers\Dashboard\Home:defaultPage');

// developer tool
$app->get('/help',          'App\Controllers\Developer\Help:help');
$app->get('/help-info',     'App\Controllers\Developer\Help:info');

$app->run();
