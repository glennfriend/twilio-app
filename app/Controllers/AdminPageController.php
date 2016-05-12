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

        include 'adminPage.helper.php';
        di('view')->setLayout(
            ViewHelper::get('_global.layout.admin')
        );

        $this->diLoader();
        $this->menuManagerLoader();
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

    /**
     *
     */
    private function menuManagerLoader()
    {

        // setting plugin manager
        // 注意該程式執行的位置
        // 越在前面, 執行的順序就越早, 但是當下時間 得到的資訊、使用的資源越少
        // 越在後面, 執行的順序就越晚, 但是當下時間 得到的資訊、使用的資源越多

//TODO: 未處理 user, 權限
        MenuManager::init(UserManager::getUser());
        // $this->plugin = $this->plugins->getPluginByKey( $this->module->getId() );
    }

}
