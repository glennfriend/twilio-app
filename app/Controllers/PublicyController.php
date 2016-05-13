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
        $this->diLoader();
        include 'helper.public.php';

        di('view')->setLayout(
            ViewHelper::get('_global.layout.public')
        );
    }

    /**
     *
     */
    private function diLoader()
    {
    }

}
