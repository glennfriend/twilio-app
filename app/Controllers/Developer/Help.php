<?php
namespace App\Controllers\Developer;

use App\Controllers\AdminPageController;
use App\Utility\Project\SlimManager;

/**
 *
 */
class Help extends AdminPageController
{

    // --------------------------------------------------------------------------------
    //  help
    // --------------------------------------------------------------------------------
    protected function help()
    {
        $routes = SlimManager::getRouter()->getRoutes();
        $urlPrefix = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
        $show = [];
        $index = 0;
        foreach ($routes as $route) {

            if (!$this->isAllowPattern($route->getPattern())) {
                continue;
            }

            $show[$index] = [
                'pattern' => $route->getPattern(),
                'methods' => join(',' , $route->getMethods()),
                'url'     => $urlPrefix . url($route->getPattern()),
            ];

            $description = $this->getArgumentsTip($route->getPattern());
            if ($description) {
                $show[$index]['arguments_tip'] = $description;
            }

            $index++;
        }

        toJson($show);
    }

    /**
     *  對特定 pattern 做說明
     */
    private function getArgumentsTip($pattern)
    {
        switch ($pattern) {
            case '/status/{type}':
                return [1 => 'active', 0 => 'pause'];
                break;
        }
        return null;
    }

    /**
     *  不需要顯示的 pattern 可以隱藏
     */
    private function isAllowPattern($pattern)
    {
        switch ($pattern) {
            case '/help':
            return false;
        }
        return true;
    }

    // --------------------------------------------------------------------------------
    //  info
    // --------------------------------------------------------------------------------
    protected function info()
    {
        echo 'Session: ';
        pr( di('session')->getAll() );

        table([
            ['Current:'        , date('Y-m-d H:i:s')],
            ['Session_Expire:' , date('Y-m-d H:i:s', di('session')->get('session_expire'))],
        ]);
    }

}
