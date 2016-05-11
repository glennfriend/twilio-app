<?php
namespace App\Package\Identity;

use Bridge\Session as Session;
use App\Package\Identity\UserManager as UserManager;
use App\Model\User as User;
use App\Model\Users as Users;

/**
 * Admin UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity
{
    /**
     *  error code
     *  -1  not validate
     *   1  account empty
     *   2  password empty
     *   0  success
     */
    protected $_errorCode = -1;

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate( $account, $password )
    {
        if( !isset($account) ) {
            $this->_errorCode = 1;
            return false;
        }

        if( !isset($password) ) {
            $this->_errorCode = 2;
            return false;
        }

        $users = new Users();
        $user = $users->getUserByAuthenticate( $account, $password );
        if( !$user ) {
            return false;
        }

        $this->_errorCode = 0;

        // setting basic config
        Session::set('account_id', $user->getId() );
        Session::set(
            'login_user_info_string',
            $user->getId() .'_'. $_SERVER['REMOTE_ADDR'] .'_'. $_SERVER['HTTP_USER_AGENT']
        );

        // custom setting
        // 注意, 以下的程式要放在此 method 最後面
        // 依照情況設定
        if( UserManager::isDeveloper() ) {
            UserManager::setDebugMode(true);
        }
        return true;
    }

    /**
     * get error code
     * @return int
     */
    public function getErrorCode()
    {
        exit; // 從未使用
        return $this->_errorCode;
    }

    /**
     * check is login
     * @return boolean
     */
    public static function isLogin()
    {
        $accountId = Session::get('account_id');
        if( !$accountId ) {
            return false;
        }
        return true;
    }

    /**
     * destory session
     */
    static public function logout()
    {
        Session::destroy();
    }

}

