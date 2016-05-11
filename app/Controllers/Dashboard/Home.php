<?php
namespace App\Controllers\Dashboard;

use App\Controllers\AdminPageController;

/**
 *
 */
class Home extends AdminPageController
{

    /**
     *
     */
    protected function defaultPage()
    {
        $this->render('dashboard.home.defaultPage');
    }

}
