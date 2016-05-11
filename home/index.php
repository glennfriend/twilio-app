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

if (isTraining()) {
    $app->get('/help',      'App\Controllers\Publicly\Help:help');
    $app->get('/help-info', 'App\Controllers\Publicly\Help:info');
}

$app->run();


/*
laravel 在 所有 router 之前的 code

Route::filter('login_check', function()
{
    if (Auth::check()):
        //return View::make('login');
    else:
		return View::make('login');
    endif;
});
*/