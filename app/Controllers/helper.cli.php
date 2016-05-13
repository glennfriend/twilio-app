<?php
use App\Utility\Console\CliManager as CliManager;

// 目前未使用
echo '123412341234213412341234213423';
exit;

/**
 *  取得 command line 處理之後獲得的參數
 */
function getParam($key, $defaultValue=null)
{
    if (!isCli()) {
        throw new Exception('Error: Is not command-line env');
    }
    return CliManager::get($key);
}
