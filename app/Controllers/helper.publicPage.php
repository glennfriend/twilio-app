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
    return di('homeUrl')->CreateUrl($segment, $args);
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

function redirectAdmin($url)
{
    if (isCli()) {
        throw new Exception('Error: Is command-line env');
    }

    $url = di('adminUrl')->createUrl($url);
    return SlimManager::getResponse()->withHeader('Location', $url);
}
