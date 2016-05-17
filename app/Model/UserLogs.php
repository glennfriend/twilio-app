<?php
namespace App\Model;

use App\Model\UserLog;

/**
 *
 */
class UserLogs extends \ZendModel
{
    const CACHE_USER_LOG = 'cache_user_log';

    /**
     *  table name
     */
    protected $tableName = 'user_logs';

    /**
     *  get method
     */
    protected $getMethod = 'getUserLog';

    /**
     *  get db object by record
     *  @param  row
     *  @return TahScan object
     */
    public function mapRow( $row )
    {
        $object = new UserLog();
        $object->setId         ( $row['id']                      );
        $object->setUserId     ( $row['user_id']                 );
        $object->setActions    ( $row['actions']                 );
        $object->setContent    ( $row['content']                 );
        $object->setIp         ( $row['ip']                      );
        $object->setIpn        ( $row['ipn']                     );
        $object->setCreateTime ( strtotime($row['create_time'])  );
        return $object;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     *  add UserLog
     *  @param UserLog object
     *  @return insert id or false
     */
    public function addUserLog($object)
    {
        $insertId = $this->addObject($object, true);
        if (!$insertId) {
            return false;
        }

        $object = $this->getUserLog($insertId);
        if (!$object) {
            return false;
        }

        $this->preChangeHook($object);
        return $insertId;
    }

    /**
     *  pre change hook, first remove cache, second do something more
     *  about add, update, delete
     *  @param object
     */
    public function preChangeHook($object)
    {
        // first, remove cache
        $this->removeCache($object);
    }

    /**
     *  remove cache
     *  @param object
     */
    protected function removeCache($object)
    {
        if ( $object->getId() <= 0 ) {
            return;
        }

        $cacheKey = $this->getFullCacheKey( $object->getId(), UserLogs::CACHE_USER_LOG );
        self::getCache()->remove($cacheKey);
    }


    /* ================================================================================
        read access database
    ================================================================================ */

    /**
     *  get UserLog by id
     *  @param  int id
     *  @return object or false
     */
    public function getUserLog($id)
    {
        $object = $this->getObject( 'id', $id, UserLogs::CACHE_USER_LOG );
        if ( !$object ) {
            return false;
        }
        return $object;
    }

    /* ================================================================================
        find UserLogs and get count
        多欄、針對性的搜尋, 主要在後台方便使用, 使用 and 搜尋方式
    ================================================================================ */

    /**
     *  find many UserLog
     *  @param  option array
     *  @return objects or empty array
     */
    public function findUserLogs($opt=[])
    {
        $opt += [
            '_order'        => 'id,DESC',
            '_page'         => 1,
            '_itemsPerPage' => conf('db.per_page')
        ];
        return $this->findUserLogsReal( $opt );
    }

    /**
     *  get count by "findUserLogs" method
     *  @return int
     */
    public function numFindUserLogs($opt=[])
    {
        // $opt += [];
        return $this->findUserLogsReal($opt, true);
    }

    /**
     *  findUserLogs option
     *
     *      - actions , EX. login,logout
     *
     *  @return objects or record total
     */
    protected function findUserLogsReal($opt=[], $isGetCount=false)
    {
        // validate 欄位 白名單
        $list = [
            'fields' => [
                'id'        => 'id',
                'userId'    => 'user_id',
                'actions'   => 'actions',
                'content'   => 'content',
                'ip'        => 'ip',
                'ipn'       => 'ipn',
            ],
            'option' => [
                '_order',
                '_page',
                '_itemsPerPage',
                '_serverType',
            ]
        ];

        \ZendModelWhiteListHelper::validateFields($opt, $list);
        \ZendModelWhiteListHelper::filterOrder($opt, $list);
        \ZendModelWhiteListHelper::fieldValueNullToEmpty($opt);

        $select = $this->getDbSelect();

        //
        $field = $list['fields'];

        if ( isset($opt['id']) ) {
            $select->where->and->equalTo( $field['id'], $opt['id'] );
        }
        if ( isset($opt['userId']) ) {
            $select->where->and->equalTo( $field['userId'], $opt['userId'] );
        }
        if ( isset($opt['actions']) ) {
            $items = explode(',', $opt['actions']);
            // 包裏 Zend Db nest()
            \ZendModelWhiteListHelper::nestLikeOr( $select, $field['actions'], $items );
        }
        if ( isset($opt['content']) ) {
            $select->where->and->like( $field['content'], '%'.$opt['content'].'%' );
        }
        if ( isset($opt['ip']) ) {
            $select->where->and->like( $field['ip'], '%'.$opt['ip'].'%' );
        }
        if ( isset($opt['ipn']) ) {
            $select->where->and->equalTo( $field['ipn'], $opt['ipn'] );
        }

        if ( !$isGetCount ) {
            return $this->findObjects( $select, $opt );
        }
        return $this->numFindObjects( $select );
    }

    /* ================================================================================
        extends
    ================================================================================ */

}
