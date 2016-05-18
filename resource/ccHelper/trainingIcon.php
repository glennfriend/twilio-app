<?php

use App\Utility\Url\HomeUrlManager;

/**
 *  建立預設的日期格式, 年月日時分秒
 */
function ccHelper_trainingIcon()
{
    if ('training'===conf('app.env')) {
        $image = HomeUrlManager::getUrl('/dist/framework/training.png');
        return '<img src="'. $image .'">';
    }
}
