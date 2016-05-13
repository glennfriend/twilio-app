<?php
namespace App\Controllers;

use App\Utility\Identity\UserManager as UserManager;
use App\Utility\Output\MenuManager as MenuManager;

/**
 *
 */
class AdminPageController extends BaseController
{
    /**
     *  initBefore() 僅供 extend controller rewrite
     *  最終端 Controller 請使用 init()
     */
    protected function initBefore()
    {
        $this->diLoader();
        include 'helper.adminPage.php';

        di('view')->setLayout('_global.layout.admin');

        // 必須認證
        $user = UserManager::getUser();
        if (!$user) {
            return redirectHome('/login');
        }

        MenuManager::init($user);
    }

    /**
     *
     */
    private function diLoader()
    {
    }

}
