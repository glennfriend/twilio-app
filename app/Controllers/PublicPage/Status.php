<?php
namespace App\Controllers\PublicPage;

use App\Controllers\PublicPageController;
use App\Utility\Project\SlimManager;
use Bridge\Input;

/**
 *
 */
class Status extends PublicPageController
{

    /**
     *
     */
    protected function show404()
    {
        if (Input::isAjax()) {
            echo json_encode([
                'error' => [
                    'code'    => '1001',
                    'message' => 'Is error-404',
                ]
            ]);
        }
        else {
            echo '404, Page not found';
        }
        return SlimManager::getResponse()->withStatus(404);
    }

}
