<?php
namespace App\Controllers;

use App\Utility\View\ViewHelper as ViewHelper;

/**
 *
 */
class PublicyController extends BaseController
{
    /**
     *  這裡僅供 extend controller rewrite
     *  最終端 Controller 請使用 init()
     */
    public function initBefore()
    {
        include 'public.helper.php';
        di('view')->setLayout(
            ViewHelper::get('_global.layout.public')
        );
        $this->diLoader();
    }

    /**
     *
     */
    private function diLoader()
    {
        $di = di();

        $di->register('url', 'App\Utility\Url\HomeUrlManager');
        $di->get('url')->init([
            'basePath'  =>  conf('app.path'),
            'baseUrl'   =>  conf('home.base.url'),
            'host'      =>  isCli() ? '' :  $_SERVER['HTTP_HOST'],
        ]);
    }

}
