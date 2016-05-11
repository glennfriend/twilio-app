<?php
namespace App\Controllers\Publicly;

use App\Controllers\PublicyController;
use Bridge\Input;

/**
 *
 */
class Home extends PublicyController
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

        $this->render('publicly.home.defaultPage', [
            'message' => 'welcome',
        ]);
    }

}
