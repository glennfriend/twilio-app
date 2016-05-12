<?php
namespace App\Model;
use App\Model\Users as Users;
use App\Library\Encrypt\Pbkdf2 as Pbkdf2;

/**
 *  User
 *
 */
class User extends \BaseObject
{

    const STATUS_ALL    = -1;
    const STATUS_CLOSE  = 0;
    const STATUS_OPEN   = 1;
    const STATUS_DELETE = 9;

    /**
     *  請依照 table 正確填寫該 field 內容
     *  @return array()
     */
    public static function getTableDefinition()
    {
        /*
            變數名稱 =>
                type    格式 => 文字/數字/浮點數字/日期
                filters 過濾 => _filter_ + method
                field   欄位 => 資料庫欄位
                value   值   => 預設值
        */
        return array(
            'id' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getId',
                'field'   => 'id',
            ),
            'account' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getAccount',
                'field'   => 'account',
            ),
            'password' => array(
                'type'    => 'string',
                'filters' => array(),
                'storage' => 'getPassword',
                'field'   => 'password',
            ),
            'roleNames' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getRoleNames',
                'field'   => 'role_names',
            ),
            'email' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getEmail',
                'field'   => 'email',
            ),
            'status' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getStatus',
                'field'   => 'status',
                'value'   => self::STATUS_CLOSE,
            ),
            'createTime' => array(
                'type'    => 'timestamp',
                'filters' => array('intval'),
                'storage' => 'getCreateTime',
                'field'   => 'create_time',
                'value'   => time(),
            ),
            'updateTime' => array(
                'type'    => 'timestamp',
                'filters' => array('intval'),
                'storage' => 'getUpdateTime',
                'field'   => 'update_time',
                'value'   => time(),
            ),
            'properties' => array(
                'type'    => 'string',
                'filters' => array('arrayval'), // 特殊的 object filter, 通常會在這裡加新的 method
                'storage' => 'getProperties',
                'field'   => 'properties',
            ),
        );
    }

    /**
     *  Disabled methods
     *  @return array()
     */
    public static function getDisabledMethods()
    {
        // parent::getDisabledMethods();
        return array();
    }

    /**
     *  validate
     *  @return messages Array()
     */
    public function validate()
    {
        $messages = array();

        if (!$this->getAccount()) {
            $messages['account'] = '該欄位必填';
        }

        // role
        if (!$this->getRoleNames()) {
            $messages['role_names'] = 'Role info error';
        }

        // email
        $email = filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $messages['email'] = 'Email 格式不正確';
        }

        // choose value
        $result = false;
        foreach (cc('attribList', $this, 'status') as $name => $value) {
            if ($this->getStatus()==$value) {
                $result = true;
                break;
            }
        }
        if (!$result) {
            $messages['status'] = 'status incorrect';
        }

        return $messages;
    }

    /**
     *  filter object data
     */
    public function filter()
    {
    }


    /* ------------------------------------------------------------------------------------------------------------------------
        basic method rewrite or extends
    ------------------------------------------------------------------------------------------------------------------------ */

    public function setPassword($string)
    {
        $this->store['password'] = $this->passwordEncode($string);
    }
    public function setPurePassword($string)
    {
        $this->store['password'] = $string;
    }
    /**
     *  驗証輸入的密碼是否跟物件中的密碼相同
     *  @return boolean
     */
    public function validatePassword($password)
    {
        if (!$password || !$this->store['password']) {
            return false;
        }
        if (!Pbkdf2::validatePassword($password, $this->store['password'])) {
            return false;
        }
        return true;
    }
    /**
     *  密碼 的 加密方式
     *  @return string
     */
    protected function passwordEncode($password)
    {
        return Pbkdf2::createHash($password);
    }

    /**
     *  get createTime by format
     *  @param  format , date format string
     *  @return string
     */
    public function getCreateTimeByFormat($format="Y-m-d")
    {
        return date($format, $this->getCreateTime());
    }

    /**
     *  get updateTime by format
     *  @param  format , date format string
     *  @return string
     */
    public function getUpdateTimeByFormat($format="Y-m-d")
    {
        return date($format, $this->getUpdateTime());
    }

    /* --------------------------------------------------------------------------------
        extends
    -------------------------------------------------------------------------------- */

    /**
     *  get user name
     */
    public function getUsername()
    {
        $username = $this->getProperty('username');
        if (!$username) {
            $username = preg_replace('/[^a-zA-Z0-9\.]+/', '', $this->getAccount() );
            $username = str_replace('.', ' ', $username);
            $username = ucwords($username);
        }
        return $username;
    }

    /**
     *  set user name
     */
    public function setUsername($username)
    {
        $username = cc('escape', $username);
        $this->setProperty('username', $username);
    }

    /**
     *  get role descriptions
     *  @return array
     */
    public function getRoleDescriptions()
    {
        $roleInfo = $this->getProperty('roleInfo');
        $result = array();
        foreach ($roleInfo as $role) {
            $result[] = $role['description'];
        }
        return $result;
    }

    /**
     *  檢查是否有對應到任何其中一種的權限, 只要有一種符合就會回傳 true
     *  代入值若為 null 或是 '' 空值 則回傳 true
     *
     *  @param string array, $permissions
     *  @return boolean
     */
    public function hasPermission($askPermissions)
    {
        if (!is_array($askPermissions)) {
            $askPermissions = array($askPermissions);
        }

        $roleNames = $this->getRoleNames();
        foreach ($askPermissions as $ask) {
            if ($ask===null || $ask==='' || in_array($ask, $roleNames)) {
                return true;
            }
        }
        return false;
    }
}
