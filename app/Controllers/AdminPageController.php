<?php
namespace App\Controllers;

use App\Module\Identity\UserManager as UserManager;

/**
 *
 */
class AdminPageController extends BaseController
{
    /**
     *
     */
    public function init()
    {
        // 必須認證
        $user = UserManager::getUser();
        if (!$user) {
            return redirect('/login');
        }
    }

}
