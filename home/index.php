<?php
$basePath = dirname(__DIR__);
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);
$app = ProjectHelper::buildApp();

// public
$app->get ('/',         'App\Controllers\PublicPage\Home:defaultPage');
$app->get ('/login',    'App\Controllers\PublicPage\Auth:login');
$app->post('/login',    'App\Controllers\PublicPage\Auth:login');
$app->get ('/logout',   'App\Controllers\PublicPage\Auth:logout');

$app->get ('/404',      'App\Controllers\PublicPage\Status:show404');
$app->run();
