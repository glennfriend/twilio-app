<?php
namespace App\Controllers;

/**
 *
 */
class PublicPageController extends BaseController
{
    /**
     *  這裡僅供 extend controller rewrite
     *  最終端 Controller 請使用 init()
     */
    protected function initBefore()
    {
        $this->diLoader();
        include 'helper.publicPage.php';

        di('view')->setLayout('_global.layout.public');
    }

    /**
     *
     */
    private function diLoader()
    {
    }

}
