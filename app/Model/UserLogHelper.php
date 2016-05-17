<?php
namespace App\Model;

use App\Utility\Identity\UserManager;
use Ydin;

/**
 *
 */
class UserLogHelper
{

    /**
     *  login success
     */
    public static function addLogin($userId, $content=null)
    {
        return self::save('[login-success]', $userId, $content);
    }

    /**
     *  login fail
     */
    public static function addLoginFail($userAccount)
    {
        $users = new Users();
        $virtualUser = $users->getUserByAccount($userAccount);
        if (!$virtualUser) {
            // 登入錯誤時, 如果帳號本身不存在, 則不記錄該 log
            return;
        }

        $content = $_SERVER['HTTP_USER_AGENT'];
        return self::save('[login-fail]', $virtualUser->getId(), $content);
    }

    /**
     *  logout
     */
    public static function addLogout($userId, $content=null)
    {
        return self::save('[logout-success]', $userId, $content);
    }

    /**
     *  change password
     */
    public static function addChangePassword($userId, $content=null)
    {
        return self::save('[password-update]', $userId, $content);
    }

    // --------------------------------------------------------------------------------
    // private
    // --------------------------------------------------------------------------------

    private static function save($actions, $userId, $content=null)
    {
        if (!$userId) {
            return;
        }

        $users = new Users();
        $user = $users->getUser($userId);
        if (!$user) {
            return;
        }

        $ip  = Ydin\Client\User::getIp();
        $ipn = Ydin\Net\Ip::ip2long($ip);

        $userLog = new UserLog();
        $userLog->setUserId  ( $userId  );
        $userLog->setActions ( $actions );
        $userLog->setContent ( $content );
        $userLog->setIp      ( $ip      );
        $userLog->setIpn     ( $ipn     );

        $userLogs = new UserLogs();
        return $userLogs->addUserLog($userLog);
    }

}
