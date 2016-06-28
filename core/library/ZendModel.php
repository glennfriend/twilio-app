<?php

use App\Utility\Identity\UserManager;
// use Zend\Db\Sql\Select;
// use Zend\Db\Sql\Insert;
// use Zend\Db\Sql\Update;
// use Zend\Db\Sql\Delete;


/*
    Zend Db sample:
        https://gist.github.com/ralphschindler/3949548
        http://www.maltblue.com/tutorial/zend-db-sql-the-basics
        http://www.maltblue.com/tutorial/zend-db-sql-select-easy-where-clauses
        http://framework.zend.com/manual/2.0/en/modules/zend.db.sql.html
*/
class ZendModel
{
    const SERVER_TYPE_MASTER = 'master';
    const SERVER_TYPE_SLAVE  = 'slave';

    /**
     *  任何錯誤的情況, 要將狀態儲存在此
     *  object or null
     */
    protected $error = null;

    /**
     *  table name
     */
    protected $tableName = null;

    /**
     *  get method
     */
    protected $getMethod = null;

    /**
     *  table master field key
     */
    protected $pk = 'id';

    /**
     *  master adapter
     */
    static protected $adapter = null;

    /**
     *  slave adapter
     */
    static protected $adapterSlave = null;

    protected function getCache()
    {
        return di('cache');
    }

    protected function getLog()
    {
        return di('log');
    }

    /**
     * you can rewrite it
     * @return string|null
     */
    public function getFullCacheKey( $value, $key )
    {
        if (!$key) {
            return null;
        }
        return "CACHE_MODELS.". trim($key) .".". trim($value);
    }

    /**
     *  get model error message
     *      - 如果有 自定義錯誤訊息, 就回傳該訊息
     *      - 如果有 exception 訊息, 就回傳該訊息
     *      - 如果有 update fail 的 update_message 訊息, 就回傳該訊息
     *      - 沒有就傳回預設內置的錯誤訊息
     *
     *  @return error-message|null
     */
    public function getModelError()
    {
        if (!$this->error) {
            return null;
        }
        if (isset($this->error['message'])) {
            return $this->error['message'];
        }
        if (isset($this->error['exception'])) {
            return $this->error['exception']->getMessage();
        }
        if (isset($this->error['update_message'])) {
            return $this->error['update_message'];
        }
        return 'Unknown model error';
    }

    /**
     *  set model error message
     */
    protected function setModelErrorMessage($message)
    {
        if (!$this->error) {
            $this->error = [];
        }
        $this->error['message'] = $message;
    }

    // --------------------------------------------------------------------------------
    //  transaction
    // --------------------------------------------------------------------------------

    /**
     *
     */
    public function beginTransaction()
    {
        $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
    }

    /**
     *
     */
    public function commit()
    {
        $this->getAdapter()->getDriver()->getConnection()->commit();
    }

    /**
     *
     */
    public function rollback()
    {
        $this->getAdapter()->getDriver()->getConnection()->rollBack();
    }

    // --------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------

    /**
     *  get master adapter
     *  cache the $adapter
     *
     *  @return adapter
     */
    public function getAdapter()
    {
        if ( self::$adapter ) {
            return self::$adapter;
        }
        self::$adapter = $this->getAdapterByParam(
            conf('db.mysql.host'),
            conf('db.mysql.db'),
            conf('db.mysql.user'),
            conf('db.mysql.pass')
        );
        return self::$adapter;
    }

    /**
     *  在 主從式資料庫 (master/slave) 之下, 取得的 slave adapter
     *  cache the $adapterSlave
     *
     *  @return adapter
     *
     *  注意!
     *      Slave 會有時間上的延遲
     *      所以 "不應該" 一切都以 "寫入用 master, 讀取用 slave" 的概念來決定使用方式
     *      只適合取得 "不準確" 的資料
     *
     */
    protected function getSlaveAdapter()
    {
        if ( self::$adapterSlave ) {
            return self::$adapterSlave;
        }
        self::$adapterSlave = $this->getAdapterByParam(
            conf('db.mysql_slave.host'),
            conf('db.mysql_slave.db'),
            conf('db.mysql_slave.user'),
            conf('db.mysql_slave.pass')
        );
        return self::$adapterSlave;
    }

    /**
     *  build Zend Db Adapter
     */
    protected function getAdapterByParam($host, $db, $user, $pass)
    {
        return new Zend\Db\Adapter\Adapter(array(
            'driver'    => 'Pdo_Mysql',
            'dsn'       => 'mysql:host='. $host .';dbname='. $db,
            'username'  => $user,
            'password'  => $pass,
            'driver_options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            )
        ));
    }

    /**
     *  Zend db query, access
     *
     *  @param Zend\Db\Sql\Select
     *  @return statement result object
     */
    public function query($select)
    {
        return $this->_query($select, ZendModel::SERVER_TYPE_MASTER);
    }

    /**
     *  query by slave server
     */
    public function querySlave($select)
    {
        return $this->_query($select, ZendModel::SERVER_TYPE_SLAVE);
    }

    /**
     *  query by Adapter Type "master" or "slave"
     */
    protected function _query($select, $serverType)
    {
        if (ZendModel::SERVER_TYPE_MASTER === $serverType) {
            $adapter = $this->getAdapter();
        }
        elseif (ZendModel::SERVER_TYPE_SLAVE === $serverType) {
            $adapter = $this->getSlaveAdapter();
        }
        else {
            throw new Exception('Model Adapter Type Error!');
            exit;
        }

        $zendSql = new Zend\Db\Sql\Sql($adapter);

        if (UserManager::isDebugMode() || 'training' === conf('app.env'))
        {
            // log
            self::getLog()->sql(
                $select->getSqlString( $adapter->getPlatform() ),
                $serverType
            );

            // developer tool
            // MonitorManager::sqlQuery( $select->getSqlString( $adapter->getPlatform() ) );
        }

        $this->error = null;
        try {
            $statement = $zendSql->prepareStatementForSqlObject($select);
            $results = $statement->execute();
        }
        catch (Exception $e) {
            $this->error = [
                'exception' => $e
            ];
            return false;
        }
        return $results;
    }

    /**
     *  Zend db execute, write
     *
     *  @param zend sql object
     *      Zend\Db\Sql\Insert
     *      Zend\Db\Sql\Update
     *      Zend\Db\Sql\Delete
     *  @return statement result object
     */
    public function execute( $write )
    {
        $adapter = $this->getAdapter();
        $sql = $write->getSqlString( $adapter->getPlatform() );

        if (UserManager::isDebugMode() || 'training' === conf('app.env'))
        {
            // log
            self::getLog()->sql($sql, ZendModel::SERVER_TYPE_MASTER);

            // developer tool
            // MonitorManager::executeQuery( $write->getSqlString( $adapter->getPlatform() ) );
        }

        $this->error = null;
        try {
            $statement = $adapter->query($sql);
            $result = $statement->execute();
        }
        catch( Exception $e ) {
            // insert/update/delete error
            // 例如: 重覆的鍵值 引發了 衝突
            $this->error = [
                'exception' => $e
            ];
            return false;
        }
        return $result;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     * add object to database
     * @param object  - dbobject
     * @param boolean - false is default boolean, true is return insert id
     * @return  boolean or int
     */
    protected function addObject($object, $isReturnInsertId=false)
    {
        $row = $this->objectToArray( $object );

        $insert = new Zend\Db\Sql\Insert( $this->tableName );
        $insert->values($row);
        $result = $this->execute($insert);
        if( !$result ) {
            return false;
        }

        if( $isReturnInsertId ) {
            return (int) $result->getGeneratedValue();
        }
        return true;
    }

    /**
     *  update object to database
     *  更新時, 若資料完全相同, 不會有更新的動作, 所以傳回值會是 0
     *
     * @param object
     * @return int, affected row count
     */
    protected function updateObject( $object )
    {
        $row = $this->objectToArray( $object );
        $pk = $this->pk;
        $pkValue = $row[$pk];
        unset($row[$pk]);

        $update = new Zend\Db\Sql\Update( $this->tableName );
        $update->where(array( $pk => $pkValue ));
        $update->set($row);

        $result = $this->execute($update);
        if (!$result) {
            if (!$this->error) {
                $this->error = [];
            }
            $this->error['update_message'] = 'Update fail';
            return false;
        }
        return $result->count();
    }

    /**
     * delete object to database
     * @param key
     * @return int, affected row count
     */
    protected function deleteObject($key)
    {
        $delete = new Zend\Db\Sql\Delete( $this->tableName );
        $delete->where(array( $this->pk => $key));

        $result = $this->execute($delete);
        if( !$result ) {
            return false;
        }
        return $result->count();
    }

    /**
     *  資料從 object 寫入到 database 之前要做資料轉換的動作
     */
    protected function objectToArray($object)
    {
        $data = array();
        foreach ($object->getTableDefinition() as $field => $item) {
            $type       = $item['type'];
            $varName    = DaoHelper::convertUnderlineToVarName($field);
            $method     = 'get' . strtoupper($varName[0]) . substr($varName, 1);
            $value      = $object->$method();

            if (is_object($value) || is_array($value)) {
                $value = serialize($value);
            }

            switch ($type) {
                case 'datetime':
                case 'timestamp':
                    $value = date('Y-m-d H:i:s', (int) $value);
                    break;
                case 'date':
                    $value = date('Y-m-d', (int) $value);
                    break;
            }

            $data[$field] = $value;
        }
        return $data;
    }

    /* ================================================================================
        access database
    ================================================================================ */

    /**
     *  get ZF2 Zend Db Select
     *  @return Zend\Db\Sql\Select
     */
    protected function getDbSelect( $isSetDefaultValue=true )
    {
        $select = new Zend\Db\Sql\Select();
        if ( $isSetDefaultValue ) {
            $select->columns(array($this->pk));
            $select->from( $this->tableName );
        }
        return $select;
    }

    /**
     * get object and cache
     * @param string - field name
     * @param string - field value
     * @param string - cache key
     * @return object or false
     */
    protected function getObject( $field, $value, $cacheKey=null )
    {
        if ( $cacheKey ) {
            $fullCacheKey = self::getFullCacheKey( $value, $cacheKey );
            $object = self::getCache()->get( $fullCacheKey );
            if( $object ) {
                return $object;
            }
        }

        $select = $this->getDbSelect();
        $select->columns(array('*'));
        $select->where(array( $field => $value ));

        $result = $this->query($select);
        if( !$result ) {
            return false;
        }

        $row = $result->current();
        if( !$row ) {
            return false;
        }

        $object = $this->mapRow( $row );
        if ( $cacheKey ) {
            self::getCache()->set( $fullCacheKey, $object );
        }
        return $object;
    }

    /**
     *  find objects
     *  這裡可以選擇 adapter 使用 "master" or "slave"
     *
     *  @param $select   - Zend\Db\Sql\Select
     *  @param $opt      - option array
     *  @return objects or empty array
     */
    protected function findObjects(Zend\Db\Sql\Select $select, $opt=[])
    {
        $orderBy      = isset($opt['orderString']) ?       $opt['orderString'] : '' ;
        $page         = isset($opt['page'])        ? (int) $opt['page']        : 1  ;
        $itemsPerPage = isset($opt['perPage'])     ? (int) $opt['perPage']     : conf('db.per_page');

        $serverType = ZendModel::SERVER_TYPE_MASTER;
        if (isset($opt['serverType']) && ZendModel::SERVER_TYPE_SLAVE===$opt['serverType']) {
            $serverType = ZendModel::SERVER_TYPE_SLAVE;
        }

        if ($orderBy) {
            $select->order( trim($orderBy) );
        }
        if(-1 !== $page) {
            $page = (int) $page;
            if( $page == 0 ) {
                $page = 1;
            }
            $select->limit( $itemsPerPage );
            $select->offset( ($page-1)*$itemsPerPage );
        }
        $result = $this->_query($select, $serverType);
        if ( !$result ) {
            return array();
        }

        $objects = array();
        $getMethod = $this->getMethod;
        while( $row = $result->next() ) {
            $objects[] = $this->$getMethod( $row[$this->pk] );
        };
        return $objects;
    }

    /**
     *  get row count
     *
     *  @param $condition - sql condition
     *  @param $opt       - option array
     *  @return int
     */
    protected function numFindObjects(Zend\Db\Sql\Select $select, $opt=[])
    {
        $serverType = ZendModel::SERVER_TYPE_MASTER;
        if (isset($opt['serverType']) && $opt['serverType']===ZendModel::SERVER_TYPE_SLAVE) {
            $serverType = ZendModel::SERVER_TYPE_SLAVE;
        }

        $param = 'count(*)';
        $expression = array('total' => new \Zend\Db\Sql\Expression($param));
        $select->columns( $expression );

        $result = $this->_query($select, $serverType);
        if( !$result ) {
            return 0;
        }

        $row = $result->current();
        return $row['total'];
    }
    
}
