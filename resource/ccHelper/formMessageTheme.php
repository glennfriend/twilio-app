<?php

use App\Utility\Output\FormMessageManager as FormMessageManager;

/**
 *  FormMessageManager 產出對應的 theme
 *  NOTE: theme 的設定不在這裡
 */
function ccHelper_formMessageTheme($field)
{
    return FormMessageManager::factoryTheme($field);
}

