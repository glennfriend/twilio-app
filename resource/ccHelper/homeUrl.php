<?php

use App\Utility\Url\HomeUrlManager;

/**
 *  create home url
 *
 */
function ccHelper_homeUrl($url, $args=[])
{
    HomeUrlManager::init([
        'basePath'  =>  conf('app.path'),
        'baseUrl'   =>  conf('home.base.url'),
        'host'      =>  isCli() ? '' :  $_SERVER['HTTP_HOST'],
    ]);
    return HomeUrlManager::createUrl($url, $args);
}
