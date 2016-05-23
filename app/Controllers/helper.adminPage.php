<?php

use App\Utility\Project\SlimManager;

/**
 *  取得 route 處理之後獲得的參數
 */
function getParam($key, $defaultValue=null)
{
    if (isCli()) {
        throw new Exception('Error: Is command-line env');
    }
    return Bridge\Input::getParam($key, $defaultValue);
}

function url($segment, $args=[])
{
    return di('adminUrl')->createUrl($segment, $args);
}

function homeUrl($segment, $args=[])
{
    return di('homeUrl')->createUrl($segment, $args);
}

function redirect($url, $isFullUrl=false)
{
    if (isCli()) {
        throw new Exception('Error: Is command-line env');
    }

    if (!$isFullUrl) {
        $url = url($url);
    }
    return SlimManager::getResponse()->withHeader('Location', $url);
}

function redirectHome($url, $isFullUrl=false)
{
    if (isCli()) {
        throw new Exception('Error: Is command-line env');
    }

    if (!$isFullUrl) {
        $url = homeUrl($url);
    }
    return SlimManager::getResponse()->withHeader('Location', $url);
}
