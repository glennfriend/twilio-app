<?php
$basePath = dirname(dirname(__DIR__));
require_once $basePath . '/core/bootstrap.php';
initialize($basePath);
$app = ProjectHelper::buildApp();

// admin system
$app->get ('/dashboard',            'App\Controllers\Dashboard\Home:defaultPage');

$app->get ('/twilio/test',          'App\Controllers\Other\Twilio:test');

//
$app->get ('/system-environment',   'App\Controllers\System\Home:environment');
$app->get ('/me',                   'App\Controllers\Me\Home:about');
$app->get ('/me-change-password',   'App\Controllers\Me\Home:changePassword');
$app->post('/me-change-password',   'App\Controllers\Me\Home:changePassword');
$app->get ('/me-logs',              'App\Controllers\Me\Home:showLogs');



// developer tool
$app->get('/help',          'App\Controllers\Developer\Help:help');
$app->get('/help-info',     'App\Controllers\Developer\Help:info');

$app->run();
