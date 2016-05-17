<?php
namespace App\Controllers\PublicPage;

use App\Controllers\PublicPageController;
use App\Utility\Identity\UserManager;
use App\Utility\Identity\UserIdentity;
use App\Utility\Output\FormMessageManager;
use App\Model\UserLogHelper;
use Bridge\Input;

/**
 *
 */
class Auth extends PublicPageController
{

    /**
     *
     */
    protected function login()
    {
        $userIdentity = new UserIdentity();
        if ($userIdentity->isLogin()) {
            return redirectAdmin('/dashboard');
        }

        $account  = trim(strip_tags(Input::get('account')));
        $password = Input::get('password');

        if (Input::isPost()) {
            if ($userIdentity->authenticate($account, $password)) {
                // 登入成功
                $user = UserManager::getUser();
                UserLogHelper::addLogin($user->getId());
                return redirectAdmin('/dashboard');
            }
            else {
                // 帳號或密碼錯誤
                UserLogHelper::addLoginFail($account);
                FormMessageManager::addErrorResultMessage('The password you entered is invalid. Check the field highlighted below and try again.');
            }
        }

        $this->render('publicPage.auth.login', Array(
            'account' => $account,
        ));
    }

    /**
     *
     */
    protected function logout()
    {
        $user = UserManager::getUser();
        if (!$user) {
            return redirect('/login');
        }
        UserLogHelper::addLogout($user->getId());

        UserIdentity::logout();
        return redirect('/login');
    }

}
