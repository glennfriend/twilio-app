<?php
namespace App\Model;

use App\Model\Users;
use App\Model\UserLogs;

/**
 *  UserLog
 *
 *  這裡要記錄的, 是跟該 user 相關的行為
 *  只要跟該 user 有關的, 都可以記錄
 *  不是該 user 操作的行為; 但是確跟該 user 有關, 也可以記錄
 *  當然, 也可以選擇不記錄, 視情況而定
 *
 *  以 user ken 為例
 *      - 我 (ken) 自己改了自己的密碼
 *      - manager vivian 改了我 (ken) 的密碼
 *      - 我 (ken) 忘記密碼, 系統幫我重新設定了一組密碼
 *      - 我 (ken) 寄了信件給 vivian
 *
 */
class UserLog extends \BaseObject
{

    /**
     *  請依照 table 正確填寫該 field 內容
     *  @return array()
     */
    public static function getTableDefinition()
    {
        return [
            'id' => [
                'type'    => 'integer',
                'filters' => array('intval'),
            ],
            'user_id' => [
                'type'    => 'integer',
                'filters' => array('intval'),
            ],
            'actions' => [
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
            ],
            'content' => [
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
            ],
            'ip' => [
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
            ],
            'ipn' => [
                'type'    => 'integer',
                'filters' => array(),
            ],
            'create_time' => [
                'type'    => 'timestamp',
                'filters' => array('dateval'),
                'value'   => time(),
            ],
        ];
    }

    /**
     *  validate
     *  @return messages array()
     */
    public function validate()
    {
        return array();
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        basic method rewrite or extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  Disabled methods
     *  @return array()
     */
    public static function getDisabledMethods()
    {
        return array();
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        extends
    ------------------------------------------------------------------------------------------------------------------------ */



    /* ------------------------------------------------------------------------------------------------------------------------
        lazy loading methods
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  get user object by user id
     *
     *  @param isCacheBuffer , is store object
     *  @return object or null
     */
    public function getUser($isCacheBuffer=true)
    {
        if ( !$isCacheBuffer ) {
            $this->_user = null;
        }
        if ( isset($this->_user) ) {
            return $this->_user;
        }

        $userId = $this->getUserId();
        if (!$userId) {
            return null;
        }
        $users = new Users();
        $user = $users->getUser($userId);

        if ($isCacheBuffer) {
            $this->_user = $user;
        }
        return $user;
    }

}
