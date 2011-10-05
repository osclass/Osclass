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
        var $conn_id ;
        /**
         *
         * @var type 
         */
        var $result_id ;
        
        /**
         *
         * @var type 
         */
        var $queries ;
        /**
         *
         * @var type 
         */
        var $query_times ;
        /**
         *
         * @var type 
         */
        var $query_count ;

        /**
         *
         * @var type 
         */
        var $error_level ;
        /**
         *
         * @var type 
         */
        var $error_desc ;

        /**
         *
         * @var type 
         */
        var $a_select ;
        /* var $a_distinct ; */
        /**
         *
         * @var type 
         */
        var $a_from ;
        /**
         *
         * @var type 
         */
        var $a_join ;
        /**
         *
         * @var type 
         */
        var $a_where ;
        /**
         *
         * @var type 
         */
        var $a_like ;
        /**
         *
         * @var type 
         */
        var $a_groupby ;
        /**
         *
         * @var type 
         */
        var $a_having ;
        /* var $a_keys ; */
        /**
         *
         * @var type 
         */
        var $a_limit ;
        /**
         *
         * @var type 
         */
        var $a_offset ;
        /**
         *
         * @var type 
         */
        var $a_order ;
        /**
         *
         * @var type 
         */
        var $a_orderby ;
        /**
         *
         * @var type 
         */
        var $a_set ;
        /**
         *
         * @var type 
         */
        var $a_wherein ;
        /* var $a_aliased_tables ; */
        /* var $a_store_array ; */

        /**
         *
         * @var type 
         */
        var $log ;

        /**
         *
         * @param type $conn_id 
         */
        function __construct(&$conn_id)
        {
            $this->conn_id          = &$conn_id ;
            $this->result_id        = 0 ;

            $this->queries          = array() ;
            $this->query_times      = array() ;
            $this->query_count      = 0 ;

            $this->error_level      = 0 ;
            $this->error_desc       = "" ;

            $this->a_select         = array() ;
            $this->a_from           = array() ;
            $this->a_join           = array() ;
            $this->a_where          = array() ;
            $this->a_like           = array() ;
            $this->a_groupby        = array() ;
            $this->a_having         = array() ;
            $this->a_limit          = false ;
            $this->a_offset         = false ;
            $this->a_order          = false ;
            $this->a_orderby        = array() ;
            $this->a_wherein        = array() ;

            $this->log              = LogDatabase::newInstance() ;
        }

        /**
         * 
         */
        function __destruct()
        {
            unset($this->connobj) ;
            unset($this->result_id) ;
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
                    $this->a_select[] = $s ;
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
                    $from = explode(',', $val) ;
                } else {
                    $from = array($from) ;
                }
            }

            foreach($from as $f) {
                $this->a_from[] = $f ;
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

            $join = $type . 'JOIN' . $table . ' ON ' . $cond ;
            $this->a_join[] = $join ;

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
        function or_where($key, $value = null)
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
                $prefix = (count($this->a_where) > 0) ? $type : '' ;
                
                if( !$this->_has_operator($k) ) {
                    $k .= ' =' ;
                }

                $v = ' ' . $this->escape($v) ;

                $this->a_where[] = $prefix . $k . $v ;
            }

            return $this ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @return type 
         */
        function where_in($key = null, $values = null)
        {
            return $this->_where_in($key, $values, false, 'AND ') ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @return type 
         */
        function or_where_in($key = null, $values = null)
        {
            return $this->_where_in($key, $values, false, 'OR ') ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @return type 
         */
        function where_not_in($key = null, $values = null)
        {
            return $this->_where_in($key, $values, true, 'AND ') ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @return type 
         */
        function or_where_not_in($key = null, $values = null)
        {
            return $this->_where_in($key, $values, true, 'OR ') ;
        }

        /**
         *
         * @param type $key
         * @param type $values
         * @param type $not
         * @param type $type
         * @return DBCommandClass 
         */
        function _where_in($key = null, $values = null, $not = false, $type = 'AND ')
        {
            if( !is_array($values) ) {
                $values = array($values) ;
            }

            $not = ($not) ? ' NOT' : '' ;

            foreach($values as $value) {
                $this->a_wherein[] = $this->escape($value) ;
            }

            $prefix   = (count($this->a_where) > 0) ? $type : '' ;

            $where_in = $key . $not . ' IN (' . implode(', ', $this->a_wherein) . ') ' ;

            $this->a_where[]   = $where_in ;
            $this->a_wherein = array() ;
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
        function not_like($field, $match = '', $side = 'both')
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
        function or_like($field, $match = '', $side = 'both')
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
        function or_not_like($field, $match = '', $side = 'both')
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
            $like_statement = '' ;

            if( !is_array($field) ) {
                $field = array($field => $match) ;
            }

            foreach($field as $k => $v) {
                $prefix = (count($this->a_like) == 0) ? '' : $type;
                $v      = $this->escape_str($v, true) ;

                switch ($side) {
                    case 'before':  $like_statement = "$prefix $k $not LIKE '%$v'" ;
                    break;
                    case 'after':   $like_statement = "$prefix $k $not LIKE '$v%'" ;
                    break;
                    default:        $like_statement = "$prefix $k $not LIKE '%$v%'" ;
                    break;
                }

                $this->a_like[] = $like_statement ;
            }

            return $this ;
        }

        /**
         *
         * @param type $by
         * @return DBCommandClass 
         */
        function group_by($by)
        {
            if( is_string($by) ) {
                $by = explode(',', $by) ;
            }

            foreach($by as $val) {
                $val = trim($val) ;

                if( $val != '' ) {
                    $this->a_groupby[] = $val ;
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
        function or_having($key, $value = '')
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
                $prefix = (count($this->ar_having) == 0) ? '' : $type;

                if( !$this->_has_operator($k) ) {
                    $k .= ' = ' ;
                }
                
                $v = ' ' . $this->escape_str($v) ;
                
                $this->a_having[] = $prefix . $k . $v ;
            }
        }

        /**
         *
         * @param type $orderby
         * @param type $direction 
         */
        function order_by($orderby, $direction = '')
        {
            if(strtolower($direction) == 'random') {
                $direction = ' RAND()' ;
            } elseif( trim($direction) != '' ) {
                $direction = (in_array(strtoupper(trim($direction)), array('ASC', 'DESC'))) ? ' ' . $direction : ' ASC' ;
            }

            $this->a_orderby = $orderby . $direction ;
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
            $this->a_limit = $value ;

            if( $offset != '' ) {
                $this->a_offset = $offset ;
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
            $this->a_offset = $offset ;
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

            if( count($this->a_set) == 0 ) {
                return false ;
            }

            if( $table == '') {
                if( !isset($this->a_from[0]) ) {
                    return false ;
                }

                $table = $this->a_from[0] ;
            }
            
            $sql = $this->_insert($table, array_keys($this->a_set), array_values($this->a_set)) ;
            $this->_reset_write() ;
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

            if( count($this->a_set) == 0 ) {
                return false ;
            }

            if( $table == '') {
                if( !isset($this->a_from[0]) ) {
                    return false ;
                }

                $table = $this->a_from[0] ;
            }

            $sql = $this->_replace($table, array_keys($this->a_set), array_values($this->a_set)) ;
            $this->_reset_write() ;
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
        
        function update($table = '', $set = null, $where = null)
        {
            if( !is_null($set) ) {
                $this->set($set) ;
            }

            if( count($this->a_set) == 0 ) {
                return false ;
            }

            if( $table == '') {
                if( !isset($this->a_from[0]) ) {
                    return false ;
                }

                $table = $this->a_from[0] ;
            }

            if( $where != null ) {
                $this->where($where) ;
            }

            $sql = $this->_update($table, $this->a_set, $this->a_where) ;

            $this->_reset_write() ;
            return $this->query($sql) ;
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

        function delete($table = '', $where = '')
        {
            if( $table == '') {
                if( !isset($this->a_from[0]) ) {
                    return false ;
                }

                $table = $this->a_from[0] ;
            }

            if( $where != null ) {
                $this->where($where) ;
            }

            if( count($this->a_where) == 0 && count($this->a_wherein) == 0 && count($this->a_like) == 0 ) {
                return false ;
            }

            $sql = $this->_delete($table, $this->a_where, $this->a_like) ;
            
            $this->_reset_write() ;
            
            return $this->query($sql) ;
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

            $sql = $this->_get_select() ;

            $result = $this->query($sql) ;
            $this->_reset_select() ;
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
            $time_start = list($sm, $ss) = explode(' ', microtime()) ;

            $this->result_id = $this->_execute($sql) ;

            // error
            $this->error_report() ;
            if( false === $this->result_id ) {
                $this->log->add_message($sql, 0, $this->error_level, $this->error_desc) ;
                return false ;
            }

            $time_end = list($em, $es) = explode(' ', microtime()) ;
            $this->query_times[] = ($em + $es) - ($sm + $ss) ;

            $this->query_count++ ;
            
            $this->log->add_message($sql, ($em + $es) - ($sm + $ss), $this->error_level, $this->error_desc) ;
            
            if( $this->is_write_type($sql) === true ) {
                return true ;
            }

            $rs            = new DBRecordsetClass() ;
            $rs->conn_id   = $this->conn_id ;
            $rs->result_id = $this->result_id ;
            $rs->num_rows  = $rs->num_rows() ;

            return $rs ;
        }

        /**
         *
         * @param type $sql
         * @return type 
         */
        function _execute($sql)
        {
            return $this->conn_id->query($sql) ;
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
                $this->a_set[$k] = $this->escape($v) ;
            }

            return $this ;
        }

        /**
         *
         * @return string 
         */
        function _get_select()
        {
            $sql = 'SELECT ' ;

            // "SELECT" portion of the query
            if( count($this->a_select) == 0 ) {
                $sql .= '*' ;
            } else {
                $sql .= implode(', ', $this->a_select) ;
            }

            // "FROM" portion of the query
            if( count($this->a_from) > 0 ) {
                $sql .= "\nFROM " ;
                if( !is_array($this->a_from) ) {
                    $this->a_from = array($this->a_from) ;
                }
                $sql .= '(' . implode(', ', $this->a_from) . ')' ;
            }

            // "JOIN" portion of the query
            if( count($this->a_join) > 0 ) {
                $sql .= "\n" ;
                $sql .= implode("\n", $this->a_join) ;
            }

            // "WHERE" portion of the query
            if( count($this->a_where) > 0 || count($this->a_like) > 0 ) {
                $sql .= "\n" ;
                $sql .= "WHERE " ;
            }

            $sql .= implode("\n", $this->a_where) ;

            // "LIKE" portion of the query
            if( count($this->a_like) > 0 ) {
                if( count($this->a_where) > 0 ) {
                    $sql .= "\nAND" ;
                }

                $sql .= implode("\n", $this->a_like) ;
            }

            // "GROUP BY" portion of the query
            if( count($this->a_groupby) > 0 ) {
                $sql .= "\nGROUP BY " ;
                $sql .= implode(', ', $this->a_groupby) ;
            }

            // "HAVING" portion of the query
            if( count($this->a_having) > 0 ) {
                $sql .= "\nHAVING " ;
                $sql .= implode(', ', $this->a_having) ;
            }

            // "ORDER BY" portion of the query
            if( count($this->a_orderby) > 0 ) {
                $sql .= "\nORDER BY " ;
                if(is_array($this->a_orderby)) {
                    $sql .= implode(', ', $this->a_orderby) ;
                } else {
                    $sql .= $this->a_orderby;
                }

                if($this->a_order !== false) {
                    $sql .= ($this->a_order == 'desc') ? ' DESC' : ' ASC' ;
                }
            }

            // "LIMIT" portion of the query
            if( is_numeric($this->a_limit) ) {
                $sql .= "\n" ;
                $sql .= "LIMIT " . $this->a_limit ;

                if( $this->a_offset > 0 ) {
                    $sql .= " OFFSET " . $this->a_offset ;
                }
            }

            return $sql ;
        }

        /**
         *
         * @return type 
         */
        function affected_rows()
        {
            return $this->conn_id->affected_rows ;
        }

        /**
         *
         * @return type 
         */
        function last_query()
        {
            return end($this->queries) ;
        }

        /**
         *
         * @return type 
         */
        function inserted_id()
        {
            return $this->conn_id->insert_id ;
        }

        /**
         *
         * @param type $str
         * @return type 
         */
        function _has_operator($str)
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
        function is_write_type($sql)
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
                $str = "'" . $this->escape_str($str) . "'" ;
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
        function escape_str($str, $like = false)
        {
            if( is_object($this->conn_id) ) {
                $str = $this->conn_id->real_escape_string($str) ;
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
        function _reset_write()
        {
            $a_reset = array('a_set'     => array(),
                             'a_from'    => array(),
                             'a_where'   => array(),
                             'a_like'    => array(),
                             'a_orderby' => array(),
                             'a_limit'   => false,
                             'a_order'   => false ) ;

            $this->_reset_run($a_reset) ;
        }

        /**
         * 
         */
        function _reset_select()
        {
            $a_reset = array('a_select'  => array(),
                             'a_from'    => array(),
                             'a_join'    => array(),
                             'a_where'   => array(),
                             'a_like'    => array(),
                             'a_groupby' => array(),
                             'a_having'  => array(),
                             'a_orderby' => array(),
                             'a_wherein' => array(),
                             'a_limit'   => false,
                             'a_offset'  => false,
                             'a_order'   => false ) ;

            $this->_reset_run($a_reset) ;
        }

        /**
         *
         * @param type $a_reset 
         */
        function _reset_run($a_reset){
            foreach ($a_reset as $item => $default_value) {
                $this->$item = $default_value;
            }
        }

        /**
         * 
         */
        function error_report()
        {
            $this->error_level = $this->conn_id->errno ;
            $this->error_desc  = $this->conn_id->error ;
        }
    }

    /* file end: ./oc-includes/osclass/classes/data/DBCommandClass.php */
?>