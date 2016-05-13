<?php
namespace App\Controllers\PublicPage;

use App\Controllers\PublicPageController;
use Bridge\Input;

/**
 *
 */
class Home extends PublicPageController
{

    /**
     *
     */
    protected function defaultPage()
    {
        /*
        pr([
            'get_or_post_data'  => trim(strip_tags(Input::get('data'))),
            'route_or_cli_data' => trim(strip_tags(getParam('data'))),
        ]);
        */

        $this->render('publicPage.home.defaultPage', [
            'message' => 'welcome',
        ]);
    }

}
