<?php
namespace App\Model;

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
    public function mapRow($row)
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
    public function findUserLogs(Array $values, $options=[])
    {
        $options += [
            'order' => [
                'id' => 'DESC',
            ],
        ];
        return $this->findUserLogsReal($values, $options);
    }

    /**
     *  get count by "findUserLogs" method
     *  @return int
     */
    public function numFindUserLogs($values, $options=[])
    {
        // $options += [];
        return $this->findUserLogsReal($values, $options, true);
    }

    /**
     *  findUserLogs option
     *
     *      - actions , EX. login,logout
     *
     *  @return objects or record total
     */
    protected function findUserLogsReal(Array $values, $opt=[], $isGetCount=false)
    {
        // validate 欄位 白名單
        $map = [
            'id'        => 'id',
            'userId'    => 'user_id',
            'actions'   => 'actions',
            'content'   => 'content',
            'ip'        => 'ip',
            'ipn'       => 'ipn',
        ];
        \ZendModelWhiteListHelper::perform($values, $map, $opt);
        $select = $this->getDbSelect();

        //
        if ( isset($values['id']) ) {
            $select->where->and->equalTo( $map['id'], $values['id'] );
        }
        if ( isset($values['userId']) ) {
            $select->where->and->equalTo( $map['userId'], $values['userId'] );
        }
        if ( isset($values['actions']) ) {
            $items = explode(',', $values['actions']);
            // 包裏 Zend Db nest()
            \ZendModelWhiteListHelper::nestLikeOr( $select, $map['actions'], $items );
        }
        if ( isset($values['content']) ) {
            $select->where->and->like( $map['content'], '%'.$values['content'].'%' );
        }
        if ( isset($values['ip']) ) {
            $select->where->and->like( $map['ip'], '%'.$values['ip'].'%' );
        }
        if ( isset($values['ipn']) ) {
            $select->where->and->equalTo( $map['ipn'], $values['ipn'] );
        }

        if ( !$isGetCount ) {
            return $this->findObjects($select, $opt);
        }
        return $this->numFindObjects($select, $opt);
    }

    /* ================================================================================
        extends
    ================================================================================ */

}
