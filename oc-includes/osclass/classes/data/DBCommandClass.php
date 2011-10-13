<?php

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

     /**
      * 
      */
	class DBCommandClass
	{
        /**
         *
         * @var type 
         */
        var $connId ;
        /**
         *
         * @var type 
         */
        var $resultId ;
        
        /**
         *
         * @var type 
         */
        var $queries ;
        /**
         *
         * @var type 
         */
        var $queryTimes ;
        /**
         *
         * @var type 
         */
        var $queryCount ;

        /**
         *
         * @var type 
         */
        var $errorLevel ;
        /**
         *
         * @var type 
         */
        var $errorDesc ;

        /**
         *
         * @var type 
         */
        var $aSelect ;
        /* var $aDistinct ; */
        /**
         *
         * @var type 
         */
        var $aFrom ;
        /**
         *
         * @var type 
         */
        var $aJoin ;
        /**
         *
         * @var type 
         */
        var $aWhere ;
        /**
         *
         * @var type 
         */
        var $aLike ;
        /**
         *
         * @var type 
         */
        var $aGroupby ;
        /**
         *
         * @var type 
         */
        var $aHaving ;
        /* var $aKeys ; */
        /**
         *
         * @var type 
         */
        var $aLimit ;
        /**
         *
         * @var type 
         */
        var $aOffset ;
        /**
         *
         * @var type 
         */
        var $aOrder ;
        /**
         *
         * @var type 
         */
        var $aOrderby ;
        /**
         *
         * @var type 
         */
        var $aSet ;
        /**
         *
         * @var type 
         */
        var $aWherein ;
        /* var $aAliasedTables ; */
        /* var $aStoreArray ; */

        /**
         *
         * @var type 
         */
        var $log ;

        /**
         *
         * @param type $conn_id 
         */
        function __construct(&$connId)
        {
            $this->connId          = &$connId ;
            $this->resultId        = 0 ;

            $this->queries          = array() ;
            $this->queryTimes      = array() ;
            $this->queryCount      = 0 ;

            $this->errorLevel      = 0 ;
            $this->errorDesc       = "" ;

            $this->aSelect         = array() ;
            $this->aFrom           = array() ;
            $this->aJoin           = array() ;
            $this->aWhere          = array() ;
            $this->aLike           = array() ;
            $this->aGroupby        = array() ;
            $this->aHaving         = array() ;
            $this->aLimit          = false ;
            $this->aOffset         = false ;
            $this->aOrder          = false ;
            $this->aOrderby        = array() ;
            $this->aWherein        = array() ;

            $this->log             = LogDatabase::newInstance() ;
        }

        /**
         * 
         */
        function __destruct()
        {
            unset($this->connId) ;
            unset($this->resultId) ;
        }

        /**
         *
         * @return type 
         */
        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Set SELECT parameter
         * 
         * @param mixed $select It can be a string or array
         * @return DBCommandClass 
         */
        function select($select = '*')
        {
            if( is_string($select) ) {
                $select = explode(',', $select) ;
            }

            foreach($select as $s) {
                $s = trim($s) ;

                if($s != '') {
                    $this->aSelect[] = $s ;
                }
            }

            return $this ;
        }

        /**
         * Set FROM parameter
         * 
         * @param mixed $from It can be a string or array
         * @return DBCommandClass 
         */
        function from($from)
        {
            if( !is_array($from) ) {
                if( strpos($from, ',') !== false) {
                    $from = explode(',', $from) ;
                } else {
                    $from = array($from) ;
                }
            }

            foreach($from as $f) {
                $this->aFrom[] = $f ;
            }

            return $this ;
        }

        /**
         *
         * @param type $table
         * @param type $cond
         * @param type $type
         * @return DBCommandClass 
         */
        function join($table, $cond, $type = '')
        {
            if($type != '') {
                $type = strtoupper(trim($type)) ;

                if( !in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER')) ) {
                    $type = '' ;
                } else {
                    $type .= ' ' ;
                }
            }

            $join = $type . ' JOIN ' . $table . ' ON ' . $cond ;
            $this->aJoin[] = $join ;

            return $this ;
        }

        /**
         *
         * @param type $key
         * @param type $value 
         */
        function where($key, $value = null)
        {
            $this->_where($key, $value, 'AND ') ;
        }

        /**
         *
         * @param type $key
         * @param type $value 
         */
        function orWhere($key, $value = null)
        {
            $this->_where($key, $value, 'OR ') ;
        }

        /**
         *
         * @param type $key
         * @param type $value
         * @param type $type 
         */
        function _where($key, $value = null, $type = 'AND ')
        {
            if( !is_array($key) ) {
                $key = array($key => $value) ;
            }

            foreach($key as $k => $v) {
                $prefix = (count($this->aWhere) > 0) ? $type : '' ;
                
                if( !$this->_hasOperator($k) ) {
                    $k .= ' =' ;
                }
                
                if(!is_null($v)) {
                    $v = ' ' . $this->escape($v) ;
                }

                $prefix . $k . $v ;
                $this->aWhere[] = $prefix . $k . $v ;
            }

            return $this ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @return type 
         */
        function whereIn($key = null, $values = null)
        {
            return $this->_whereIn($key, $values, false, 'AND ') ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @return type 
         */
        function orWhereIn($key = null, $values = null)
        {
            return $this->_whereIn($key, $values, false, 'OR ') ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @return type 
         */
        function whereNotIn($key = null, $values = null)
        {
            return $this->_whereIn($key, $values, true, 'AND ') ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @return type 
         */
        function orWhereNotIn($key = null, $values = null)
        {
            return $this->_whereIn($key, $values, true, 'OR ') ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @param type $not
         * @param type $type
         * @return DBCommandClass 
         */
        function _whereIn($key = null, $values = null, $not = false, $type = 'AND ')
        {
            if( !is_array($values) ) {
                $values = array($values) ;
            }

            $not = ($not) ? ' NOT' : '' ;

            foreach($values as $value) {
                $this->aWherein[] = $this->escape($value) ;
            }

            $prefix  = (count($this->aWhere) > 0) ? $type : '' ;

            $whereIn = $key . $not . ' IN (' . implode(', ', $this->aWherein) . ') ' ;

            $this->aWhere[] = $whereIn ;
            $this->aWherein = array() ;
            return $this ;
        }

        /**
         *
         * @param type $field
         * @param type $match
         * @param type $side
         * @return type 
         */
        function like($field, $match = '', $side = 'both')
        {
            return $this->_like($field, $match, 'AND ', $side);
        }

        /**
         *
         * @param type $field
         * @param type $match
         * @param type $side 
         */
        function notLike($field, $match = '', $side = 'both')
        {
            return $this->_like($field, $match, 'AND ', $side, 'NOT');
        }

        /**
         *
         * @param type $field
         * @param type $match
         * @param type $side
         * @return type 
         */
        function orLike($field, $match = '', $side = 'both')
        {
            return $this->_like($field, $match, 'OR ', $side);
        }

        /**
         *
         * @param type $field
         * @param type $match
         * @param type $side
         * @return type 
         */
        function orNotLike($field, $match = '', $side = 'both')
        {
            return $this->_like($field, $match, 'OR ', $side, 'NOT');
        }

        /**
         *
         * @param type $field
         * @param type $match
         * @param type $type
         * @param type $side
         * @param type $not
         * @return DBCommandClass 
         */
        function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
        {
            $likeStatement = '' ;

            if( !is_array($field) ) {
                $field = array($field => $match) ;
            }

            foreach($field as $k => $v) {
                $prefix = (count($this->aLike) == 0) ? '' : $type;
                $v      = $this->escapeStr($v, true) ;

                switch ($side) {
                    case 'before':  $likeStatement = "$prefix $k $not LIKE '%$v'" ;
                    break;
                    case 'after':   $likeStatement = "$prefix $k $not LIKE '$v%'" ;
                    break;
                    default:        $likeStatement = "$prefix $k $not LIKE '%$v%'" ;
                    break;
                }

                $this->aLike[] = $likeStatement ;
            }

            return $this ;
        }

        /**
         *
         * @param type $by
         * @return DBCommandClass 
         */
        function groupBy($by)
        {
            if( is_string($by) ) {
                $by = explode(',', $by) ;
            }

            foreach($by as $val) {
                $val = trim($val) ;

                if( $val != '' ) {
                    $this->aGroupby[] = $val ;
                }
            }

            return $this ;
        }

        /**
         *
         * @param type $key
         * @param type $value
         * @return type 
         */
        function having($key, $value = '')
        {
            return $this->_having($key, $value, 'AND ');
        }

        /**
         *
         * @param type $key
         * @param type $value
         * @return type 
         */
        function orHaving($key, $value = '')
        {
            return $this->_having($key, $value, 'OR ');
        }

        /**
         *
         * @param type $key
         * @param type $value
         * @param type $type 
         */
        function _having($key, $value = '', $type = 'AND ')
        {
            if( !is_array($key) ) {
                $key = array($key => $value) ;
            }

            foreach($key as $k => $v) {
                $prefix = (count($this->aHaving) == 0) ? '' : $type;

                if( !$this->_hasOperator($k) ) {
                    $k .= ' = ' ;
                }
                
                $v = ' ' . $this->escapeStr($v) ;
                
                $this->aHaving[] = $prefix . $k . $v ;
            }
        }

        /**
         *
         * @param type $orderby
         * @param type $direction 
         */
        function orderBy($orderby, $direction = '')
        {
            if(strtolower($direction) == 'random') {
                $direction = ' RAND()' ;
            } elseif( trim($direction) != '' ) {
                $direction = (in_array(strtoupper(trim($direction)), array('ASC', 'DESC'))) ? ' ' . $direction : ' ASC' ;
            }

            $this->aOrderby[] = $orderby . $direction ;
            return $this ;
        }

        /**
         *
         * @param type $value
         * @param type $offset
         * @return DBCommandClass 
         */
        function limit($value, $offset = '')
        {
            $this->aLimit = $value ;

            if( $offset != '' ) {
                $this->aOffset = $offset ;
            }

            return $this ;
        }

        /**
         *
         * @param type $offset
         * @return DBCommandClass 
         */
        function offset($offset)
        {
            $this->aOffset = $offset ;
            return $this ;
        }

        /**
         *
         * @param type $table
         * @param type $set
         * @return type 
         */
        function insert($table = '', $set = null)
        {
            if( !is_null($set) ) {
                $this->set($set) ;
            }

            if( count($this->aSet) == 0 ) {
                return false ;
            }

            if( $table == '') {
                if( !isset($this->aFrom[0]) ) {
                    return false ;
                }

                $table = $this->aFrom[0] ;
            }
            
            $sql = $this->_insert($table, array_keys($this->aSet), array_values($this->aSet)) ;
            $this->_resetWrite() ;
            return $this->query($sql) ;
        }

        /**
         *
         * @param type $table
         * @param type $keys
         * @param type $values
         * @return type 
         */
        function _insert($table, $keys, $values)
        {
            return 'INSERT INTO ' . $table . ' (' . implode(', ', $keys). ') VALUES (' . implode(', ', $values) . ')';
        }

        /**
         *
         * @param type $table
         * @param type $set 
         */
        function replace($table = '', $set = null)
        {
            if( !is_null($set) ) {
                $this->set($set) ;
            }

            if( count($this->aSet) == 0 ) {
                return false ;
            }

            if( $table == '') {
                if( !isset($this->aFrom[0]) ) {
                    return false ;
                }

                $table = $this->aFrom[0] ;
            }

            $sql = $this->_replace($table, array_keys($this->aSet), array_values($this->aSet)) ;
            $this->_resetWrite() ;
            return $this->query($sql) ;
        }

        /**
         *
         * @param type $table
         * @param type $key
         * @param type $values
         * @return type 
         */
        function _replace($table, $key, $values)
        {
            return 'REPLACE INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')';
        }

        /**
         *
         * @param type $table
         * @param type $set
         * @param type $where
         * @return type 
         */
        function update($table = '', $set = null, $where = null)
        {
            if( !is_null($set) ) {
                $this->set($set) ;
            }

            if( count($this->aSet) == 0 ) {
                return false ;
            }

            if( $table == '') {
                if( !isset($this->aFrom[0]) ) {
                    return false ;
                }

                $table = $this->aFrom[0] ;
            }

            if( $where != null ) {
                $this->where($where) ;
            }

            $sql = $this->_update($table, $this->aSet, $this->aWhere) ;

            $this->_resetWrite() ;
            $result = $this->query($sql) ;

            if( $result == false ) {
                return false ;
            }

            return $this->affectedRows() ;
        }

        /**
         *
         * @param type $table
         * @param type $values
         * @param type $where 
         */
        function _update($table, $values, $where)
        {
            foreach($values as $k => $v) {
                $valstr[] = $k . ' = ' . $v ;
            }

            $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $valstr) ;

            $sql .= ($where != '' && count($where) > 0) ? " WHERE " . implode(" ", $where) : '' ;

            return $sql ;
        }

        /**
         *
         * @param type $table
         * @param type $where
         * @return type 
         */
        function delete($table = '', $where = '')
        {
            if( $table == '') {
                if( !isset($this->aFrom[0]) ) {
                    return false ;
                }

                $table = $this->aFrom[0] ;
            }

            if( $where != null ) {
                $this->where($where) ;
            }

            if( count($this->aWhere) == 0 && count($this->aWherein) == 0 && count($this->aLike) == 0 ) {
                return false ;
            }

            $sql = $this->_delete($table, $this->aWhere, $this->aLike) ;
            
            $this->_resetWrite() ;
            $result = $this->query($sql) ;

            if( $result == false ) {
                return false ;
            }

            return $this->affectedRows() ;
        }

        function _delete($table, $where, $like)
        {
            $conditions = '' ;

            if( count($where) > 0 || count($like) > 0 ) {
                $conditions  = "\nWHERE " ;
                $conditions .= implode("\n", $where) ;

                if( count($where) > 0 && count($like) > 0 ) {
                    $conditions .= ' AND ' ;
                }
                $conditions .= implode("\n", $like) ;
            }

            $sql = 'DELETE FROM ' . $table . $conditions ;
            return $sql;
        }

        /**
         *
         * @param type $table
         * @param type $limit
         * @param type $offset
         * @return type 
         */
        function get($table = '', $limit = null, $offset = null)
        {
            if($table != '') {
                $this->from($table) ;
            }

            if( !is_null($limit) ) {
                $this->limit($limit, $offset) ;
            }

            $sql = $this->_getSelect() ;

            $result = $this->query($sql) ;
            $this->_resetSelect() ;
            
            return $result ;
        }

        /**
         *
         * @param type $sql
         * @return DBRecordsetClass 
         */
        function query($sql)
        {
            if($sql == '') {
                return false ;
            }

            $this->queries[] = $sql ;
            $timeStart = list($sm, $ss) = explode(' ', microtime()) ;

            $this->resultId = $this->_execute($sql) ;

            $this->errorReport() ;
            if( false === $this->resultId ) {
                $this->log->addMessage($sql, 0, $this->errorLevel, $this->errorDesc) ;
                return false ;
            }

            $timeEnd = list($em, $es) = explode(' ', microtime()) ;
            $this->queryTimes[] = ($em + $es) - ($sm + $ss) ;

            $this->queryCount++ ;
            
            $this->log->addMessage($sql, ($em + $es) - ($sm + $ss), $this->errorLevel, $this->errorDesc) ;
            
            if( $this->isWriteType($sql) === true ) {
                return true ;
            }

            $rs           = new DBRecordsetClass() ;
            $rs->connId   = $this->connId ;
            $rs->resultId = $this->resultId ;
            $rs->numRows  = $rs->numRows() ;
            
            return $rs ;
        }

        /**
         *
         * @param type $sql
         * @return type 
         */
        function _execute($sql)
        {
            return $this->connId->query($sql) ;
        }

        /**
         *
         * @param type $key
         * @param type $value
         * @return DBCommandClass 
         */
        function set($key, $value = '')
        {
            if( !is_array($key) ) {
                $key = array($key => $value) ;
            }

            foreach($key as $k => $v) {
                $this->aSet[$k] = $this->escape($v) ;
            }

            return $this ;
        }

        /**
         *
         * @return string 
         */
        function _getSelect()
        {
            $sql = 'SELECT ' ;

            // "SELECT" portion of the query
            if( count($this->aSelect) == 0 ) {
                $sql .= '*' ;
            } else {
                $sql .= implode(', ', $this->aSelect) ;
            }

            // "FROM" portion of the query
            if( count($this->aFrom) > 0 ) {
                $sql .= "\nFROM " ;
                if( !is_array($this->aFrom) ) {
                    $this->a_from = array($this->aFrom) ;
                }
                $sql .= '(' . implode(', ', $this->aFrom) . ')' ;
            }

            // "JOIN" portion of the query
            if( count($this->aJoin) > 0 ) {
                $sql .= "\n" ;
                $sql .= implode("\n", $this->aJoin) ;
            }

            // "WHERE" portion of the query
            if( count($this->aWhere) > 0 || count($this->aLike) > 0 ) {
                $sql .= "\n" ;
                $sql .= "WHERE " ;
            }

            $sql .= implode("\n", $this->aWhere) ;

            // "LIKE" portion of the query
            if( count($this->aLike) > 0 ) {
                if( count($this->aWhere) > 0 ) {
                    $sql .= "\nAND" ;
                }

                $sql .= implode("\n", $this->aLike) ;
            }

            // "GROUP BY" portion of the query
            if( count($this->aGroupby) > 0 ) {
                $sql .= "\nGROUP BY " ;
                $sql .= implode(', ', $this->aGroupby) ;
            }

            // "HAVING" portion of the query
            if( count($this->aHaving) > 0 ) {
                $sql .= "\nHAVING " ;
                $sql .= implode(', ', $this->aHaving) ;
            }

            // "ORDER BY" portion of the query
            if( count($this->aOrderby) > 0 ) {
                $sql .= "\nORDER BY " ;
                $sql .= implode(', ', $this->aOrderby) ;

                if($this->aOrder !== false) {
                    $sql .= ($this->aOrder == 'desc') ? ' DESC' : ' ASC' ;
                }
            }

            // "LIMIT" portion of the query
            if( is_numeric($this->aLimit) ) {
                $sql .= "\n" ;
                $sql .= "LIMIT " . $this->aLimit ;

                if( $this->aOffset > 0 ) {
                    $sql .= " OFFSET " . $this->aOffset ;
                }
            }

            return $sql ;
        }

        /**
         *
         * @return type 
         */
        function affectedRows()
        {
            return $this->connId->affected_rows ;
        }

        /**
         *
         * @return type 
         */
        function lastQuery()
        {
            return end($this->queries) ;
        }

        /**
         *
         * @return type 
         */
        function insertedId()
        {
            return $this->connId->insert_id ;
        }

        /**
         *
         * @param type $str
         * @return type 
         */
        function _hasOperator($str)
        {
            $str = trim($str) ;

            if ( ! preg_match('/(\s|<|>|!|=|is null|is not null)/i', $str)) {
                return false;
            }

            return true;
        }

        /**
         *
         * @param type $sql
         * @return type 
         */
        function isWriteType($sql)
        {
            if ( ! preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql)) {
                return false;
            }

            return true;
	    }

        /**
         *
         * @param type $str
         * @return string 
         */
        function escape($str)
        {
            if( is_string($str) ) {
                $str = "'" . $this->escapeStr($str) . "'" ;
            } elseif ( is_bool($str) ) {
                $str = ($str === false) ? 0 : 1 ;
            } elseif( is_null($str) ) {
                $str = 'NULL' ;
            }

            return $str ;
        }

        /**
         *
         * @param type $str
         * @return type 
         */
        function escapeStr($str, $like = false)
        {
            if( is_object($this->connId) ) {
                $str = $this->connId->real_escape_string($str) ;
            } else {
                $str = addslashes($str) ;
            }

            if( $like ) {
                $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str) ;
            }
            
            return $str ;
        }

        /**
         * 
         */
        function _resetWrite()
        {
            $aReset = array('aSet'     => array(),
                            'aFrom'    => array(),
                            'aWhere'   => array(),
                            'aLike'    => array(),
                            'aOrderby' => array(),
                            'aLimit'   => false,
                            'aOrder'   => false ) ;

            $this->_resetRun($aReset) ;
        }

        /**
         * 
         */
        function _resetSelect()
        {
            $aReset = array('aSelect'  => array(),
                            'aFrom'    => array(),
                            'aJoin'    => array(),
                            'aWhere'   => array(),
                            'aLike'    => array(),
                            'aGroupby' => array(),
                            'aHaving'  => array(),
                            'aOrderby' => array(),
                            'aWherein' => array(),
                            'aLimit'   => false,
                            'aOffset'  => false,
                            'aOrder'   => false ) ;

            $this->_resetRun($aReset) ;
        }

        /**
         *
         * @param type $a_reset 
         */
        function _resetRun($aReset){
            foreach ($aReset as $item => $defaultValue) {
                $this->$item = $defaultValue;
            }
        }

        /**
         * Set last error code and descriptionfor the most recent mysqli function call
         * 
         * @access private
         * @since 2.3
         */
        function errorReport()
        {
            $this->errorLevel = $this->connId->errno ;
            $this->errorDesc  = $this->connId->error ;
        }

        /**
         * Returns the last error code for the most recent mysqli function call
         * 
         * @access public
         * @since 2.3
         * @return int 
         */
        function getErrorLevel()
        {
            return $this->errorLevel ;
        }

        /**
         * Returns a string description of the last error for the most recent MySQLi function call
         * 
         * @access public
         * @since 2.3
         * @return string 
         */
        function getErrorDesc()
        {
            return $this->errorDesc ;
        }

    }

    /* file end: ./oc-includes/osclass/classes/data/DBCommandClass.php */
?>