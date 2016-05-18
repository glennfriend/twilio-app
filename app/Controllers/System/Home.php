<?php
namespace App\Controllers\System;

use App\Controllers\AdminPageController;
use App\Utility\Output\MenuManager;

/**
 *
 */
class Home extends AdminPageController
{

    /**
     *
     */
    protected function init()
    {
        MenuManager::setMain('system');
    }

    /**
     *
     */
    protected function environment()
    {
        MenuManager::setSub('system-environment');

        $this->render('system.home.environmen');
    }

}
