<?php
namespace App\Controllers;

use App\Utility\Identity\UserManager as UserManager;

/**
 *
 */
class AdminPageController extends BaseController
{
    /**
     *
     */
    public function init()
    {
        // 必須認證
        $user = UserManager::getUser();
        if (!$user) {
            return redirect('/login');
        }

        include 'adminPage.helper.php';
        $this->diLoader();
    }

    /**
     *
     */
    private function diLoader()
    {
        $di = di();

        $di->register('url', 'App\Utility\Url\AdminUrlManager');
        $di->get('url')->init([
            'basePath'  =>  conf('app.path'),
            'baseUrl'   =>  conf('admin.base.url'),
            'host'      =>  isCli() ? '' :  $_SERVER['HTTP_HOST'],
        ]);
    }

}
