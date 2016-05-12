<?php
$basePath = dirname(__DIR__);
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);
$app = ProjectHelper::buildApp();

// public
$app->get ('/',         'App\Controllers\Publicly\Home:defaultPage');
$app->get ('/login',    'App\Controllers\Publicly\Auth:login');
$app->post('/login',    'App\Controllers\Publicly\Auth:login');
$app->get ('/logout',   'App\Controllers\Publicly\Auth:logout');

$app->run();
