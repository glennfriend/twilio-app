<?php
namespace App\Controllers\Dashboard;

use App\Controllers\AdminPageController;
use App\Utility\Output\MenuManager as MenuManager;
use App\Utility\Identity\UserManager as UserManager;
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
        MenuManager::setMain('dashboard');
        MenuManager::setSub('dashboard-user');
    }

    /**
     *
     */
    protected function defaultPage()
    {
        switch (Input::get('by')) {
            case 'manager':
                MenuManager::setSub('dashboard-manager');
                if( !UserManager::isAdmin() ) {
                    return redirect('/dashboard');
                }
                break;
            case 'developer':
                MenuManager::setSub('dashboard-developer');
                if( !UserManager::isDeveloper() ) {
                    return redirect('/dashboard');
                }
                break;
            default:
                MenuManager::setSub('dashboard-user');
        }

        $this->render('dashboard.home.defaultPage');
    }

}
