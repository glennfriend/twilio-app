<?php
namespace App\Model;

use App\Model\User as User;

class Users extends \ZendModel
{
    const CACHE_USER = 'cache_user';

    /**
     *  table name
     */
    protected $tableName = 'users';

    /**
     *  get method
     */
    protected $getMethod = 'getUser';

    /**
     *  covert db row to object
     *  return object
     */
    public function mapRow( $row )
    {
        $object = new User();
        $object->setId           ( $row['id']                       );
        $object->setAccount      ( $row['account']                  );
        $object->setPurePassword ( $row['password']                 );
        $object->setEmail        ( $row['email']                    );
        $object->setStatus       ( $row['status']                   );
        $object->setRoleNames    ( $row['role_names']               );
        $object->setCreateTime   ( strtotime($row['create_time'])   );
        $object->setUpdateTime   ( strtotime($row['update_time'])   );
        $object->setProperties   ( unserialize($row['properties'])  );

        // user extended info
        $roleInfo = $this->getRoleInfo($object);
        $object->setProperty('roleInfo',$roleInfo);
        return $object;
    }

    /**
     *  add user
     */
    public function addUser( $object )
    {
        $insertId = $this->addObject( $object, true );
        if ( !$insertId ) {
            return false;
        }

        $object = $this->getUser( $insertId );
        if ( !$object ) {
            return false;
        }

        $this->preChangeHook( $object );
        return $insertId;
    }

    /**
     *  update user
     */
    public function updateUser( $object )
    {
        $result = $this->updateObject( $object );
        if ( !$result ) {
            return false;
        }

        $this->preChangeHook( $object );
        return $result;
    }

    /**
     *  disable user
     */
    public function disableUser( $object )
    {
        $object->setStatus( User::STATUS_DELETE );
        return $this->updateObject( $object );
    }

    /**
     *  pre change hook, first remove cache, second do something more
     *  about add, update, delete
     *  @param object
     */
    public function preChangeHook( $object )
    {
        // first, remove cache
        $this->removeCache( $object );
    }


    /**
     *  remove cache
     *  @param object
     */
    protected function removeCache( $object )
    {
        if ( $object->getId() <= 0 ) {
            return;
        }
        $cacheKey = $this->getFullCacheKey($object->getId(), Users::CACHE_USER);
        self::getCache()->remove($cacheKey);
    }

    /* ================================================================================
        access database
    ================================================================================ */

    /**
     *  get by Authenticate
     *  認證的部份必須包含狀態的檢查
     *  @return object or empty array
     */
    public function getUserByAuthenticate( $account, $password )
    {
        $select = $this->getDbSelect();
        $select->where(array( 'account'  => $account  ));

        $objects = $this->findObjects( $select, array(
            '_page' => 1,
            '_itemsPerPage' => 1,
        ));
        if ( !$objects || count($objects)<1 ) {
            return array();
        }
        $hasCacheObject = $objects[0];

        // 注意!
        // 這裡是認證的部份, 所以必須清除 cache 重新再取得資料
        $this->removeCache($hasCacheObject);
        $object = $this->getUser( $hasCacheObject->getId() );

        if( !$object->validatePassword($password)) {
            return array();
        }
        if( User::STATUS_OPEN !== $object->getStatus() ) {
            return array();
        }
        return $object;
    }

    /**
     *  get by id
     *  @return object or false
     */
    public function getUser( $id )
    {
        $object = $this->getObject( 'id', $id, Users::CACHE_USER );
        if ( !$object ) {
            return false;
        }
        return $object;
    }

    /**
     *  get by account
     *  @return object or empty array
     */
    public function getUserByAccount( $account )
    {
        $select = $this->getDbSelect();
        $select->where(array( 'account' => $account ));

        $objects = $this->findObjects( $select, array(
            '_page' => 1,
            '_itemsPerPage' => 1,
        ));
        if ( !$objects || count($objects)<1 ) {
            return array();
        }
        $object = $objects[0];
        return $object;
    }

    /**
     *  get user role info
     *      雖然 user_roles 沒有 cache
     *      但是其實資料會儲存在 user object 之中
     *      所以實際上是有 cache
     *
     *  @return rows array or empty array
     */
    public function getRoleInfo(User $user)
    {
        $roleNameString = $user->getRoleNames();
        if ( !$roleNameString ) {
            return [];
        }

        $roles = array();
        foreach ( explode(',',$roleNameString) as $roleName ) {
            $roles[] = trim(strip_tags($roleName));
        }
        $roles = array_unique($roles);

        $select = $this->getDbSelect(false);
        $select->columns(array('*'));
        $select->from('user_roles');
        $select->where->in('name', $roles);

        $results = $this->query($select);
        if( !$results ) {
            return array();
        }

        $rows = array();
        while( $row = $results->next() ) {
            $rows[] = $row;
        }
        return $rows;
    }

    /* ================================================================================
        find Users and get count
        多欄、針對性的搜尋, 主要在後台方便使用, 使用 and 搜尋方式
    ================================================================================ */

    /**
     *  find many User
     *  @param  option array
     *  @return objects or empty array
     */
    public function findUsers( array $opt )
    {
        $opt += array(
            '_order'        => 'id,DESC',
            '_page'         => 1,
            '_itemsPerPage' => conf('db.per_page')
        );
        return $this->findUsersReal( $opt );
    }

    /**
     *  get count by "findUsers" method
     *  @return int
     */
    public function numFindUsers( array $opt )
    {
        // $opt += array();
        return $this->findUsersReal( $opt, true );
    }


    /**
     *  findUsers option
     *  @return objects or record total
     */
    protected function findUsersReal( $opt=array(), $isGetCount=false )
    {
        // validate 欄位 白名單
        $list = array(
            'fields' => [
                'id'        => 'id',
                'account'   => 'account',
                'roleIds'   => 'role_ids',
                'email'     => 'email',
                'status'    => 'status',
            ],
            'option' => [
                '_order',
                '_page',
                '_itemsPerPage',
                '_serverType',
            ]
        );

        \ZendModelWhiteListHelper::validateFields($opt, $list);
        \ZendModelWhiteListHelper::filterOrder($opt, $list);
        \ZendModelWhiteListHelper::fieldValueNullToEmpty($opt);

        $select = $this->getDbSelect();

        //
        $field = $list['fields'];

        if ( isset($opt['account']) ) {
            $select->where->and->equalTo( $field['account'], $opt['account'] );
        }
        if ( isset($opt['email']) ) {
            $select->where->and->equalTo( $field['email'], $opt['email'] );
        }
        if ( isset($opt['status']) ) {
            $select->where->and->equalTo( $field['status'], $opt['status'] );
        }

        if ( !$isGetCount ) {
            return $this->findObjects( $select, $opt );
        }
        return $this->numFindObjects( $select, $opt );
    }

    /**
     *  NOTE: 建議停用該程式, 改用 getUsersByRuleName()
     *
     *  Get user objects by rule id
     *  @return array
     */
    public static function getUsersByRule($ruleId)
    {
        $options = array_filter(array(
            'status' => USER::STATUS_OPEN,
            '_order' => 'id,asc',
            '_page'  => -1,
        ));
        $users = new Users();
        $myUsers  = $users->findUsers( $options );
        foreach ($myUsers as $key => $user) {
            $roleInfos = $user->getProperty('roleInfo');
            $isTarget = false;
            foreach ($roleInfos as $roleInfo) {
                if ($roleInfo['id'] == $ruleId) {
                    $isTarget = true;
                }
            }
            if (!$isTarget) {
                unset($myUsers[$key]);
            }
        }
        return $myUsers;
    }

    /**
     *  get users by rule name
     *
     *  @param string - rule name string
     *  @return users array
     */
    public static function getUsersByRuleName($ruleName)
    {
        $options = [
            'status' => User::STATUS_OPEN,
            '_order' => 'id,asc',
            '_page'  => -1,
        ];

        $allowUsers = [];
        $users      = new Users();
        $myUsers    = $users->findUsers( $options );
        foreach ($myUsers as $key => $user) {
            $roleInfos = $user->getProperty('roleInfo');
            foreach ($roleInfos as $roleInfo) {
                if ($roleInfo['name'] === $ruleName) {
                    $allowUsers[] = $user;
                    break;
                }
            }
        }
        return $allowUsers;
    }

}

