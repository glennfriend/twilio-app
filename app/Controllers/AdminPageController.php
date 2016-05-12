<?php
namespace App\Controllers;

use App\Utility\View\ViewHelper as ViewHelper;
use App\Utility\Identity\UserManager as UserManager;
use App\Utility\Output\Menu as Menu;
use App\Utility\Output\MenuManager as MenuManager;

/**
 *
 */
class AdminPageController extends BaseController
{
    /**
     *  這裡僅供 extend controller rewrite
     *  最終端 Controller 請使用 init()
     */
    public function initBefore()
    {
        // 必須認證
        $user = UserManager::getUser();
        if (!$user) {
            return redirect('/login');
        }

        // setting layout
        include 'adminPage.helper.php';
        di('view')->setLayout(
            ViewHelper::get('_global.layout.admin')
        );

        $this->diLoader();

        //
        MenuManager::init(UserManager::getUser());
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
