<?php
namespace App\Controllers\Me;

use App\Controllers\AdminPageController;
use App\Utility\Output\MenuManager as MenuManager;
use Bridge\Input as Input;

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
        MenuManager::setMain('me');
    }

    /**
     *
     */
    protected function about()
    {
        MenuManager::setSub('me-about');

        $this->render('me.home.about');
    }

    /**
     *
     */
    protected function changePassword()
    {
        MenuManager::setSub('me-change-password');

        $this->render('me.home.changePassword');
    }

    /**
     *
     */
    protected function showLogs()
    {
        MenuManager::setSub('me-logs');
        $actions = Input::get('actions')

        $this->render('me.home.showLogs');
    }



}
