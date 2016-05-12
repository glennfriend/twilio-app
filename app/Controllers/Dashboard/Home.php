<?php
namespace App\Controllers\Dashboard;

use App\Controllers\AdminPageController;
use App\Utility\Output\MenuManager as MenuManager;

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
        MenuManager::setMain('dashboard');
        MenuManager::setSub('dashboard-2');
    }

    /**
     *
     */
    protected function defaultPage()
    {
        $this->render('dashboard.home.defaultPage');
    }

}
