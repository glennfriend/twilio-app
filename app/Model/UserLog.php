<?php
namespace App\Model;

use App\Model\Users;
use App\Model\UserLogs;

/**
 *  UserLog
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
        return array(
            'id' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getId',
                'field'   => 'id',
            ),
            'userId' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getUserId',
                'field'   => 'user_id',
            ),
            'actions' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getActions',
                'field'   => 'actions',
            ),
            'content' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getContent',
                'field'   => 'content',
            ),
            'ip' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getIp',
                'field'   => 'ip',
            ),
            'ipn' => array(
                'type'    => 'integer',
                'filters' => array(),
                'storage' => 'getIpn',
                'field'   => 'ipn',
            ),
            'createTime' => array(
                'type'    => 'timestamp',
                'filters' => array('dateval'),
                'storage' => 'getCreateTime',
                'field'   => 'create_time',
                'value'   => time(),
            ),
        );
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
