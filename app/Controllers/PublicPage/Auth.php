<?php
namespace App\Controllers\PublicPage;

use App\Controllers\PublicPageController;
use App\Utility\Identity\UserIdentity as UserIdentity;
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

        $account  = trim(strip_tags( Input::get('account') ));
        $password = Input::get('password');

        if( Input::isPost() ) {

            if( $userIdentity->authenticate( $account, $password ) ) {
                // 登入成功
                return redirectAdmin('/dashboard');
            }
            else {
                // 帳號或密碼錯誤
                // TODO: 未加入 FormMessageManager
                echo 'The password you entered is invalid. Check the field highlighted below and try again.';
                exit;
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
        UserIdentity::logout();
        return redirect('/');
    }

}
