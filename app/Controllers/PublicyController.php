<?php
namespace App\Controllers;

/**
 *
 */
class PublicyController extends BaseController
{
    /**
     *
     */
    public function init()
    {
        include 'public.helper.php';
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
