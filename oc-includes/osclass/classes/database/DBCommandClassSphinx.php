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
 * Database command object
 * 
 * @package OSClass
 * @subpackage Database
 * @since 2.3
 */
class DBCommandClassSphinx
{
	/**
	 * Database connection object to OSClass database
	 * 
	 * @access private
	 * @since 2.3
	 * @var mysqli 
	 */
	var $connId ;
	/**
	 * Database result object
	 * 
	 * @access public
	 * @since 2.3
	 * @var MySQLi_Result 
	 */
	var $resultId ;
	
	/**
	 *
	 * @var array 
	 */
	var $queries ;
	/**
	 *
	 * @var array 
	 */
	var $queryTimes ;
	/**
	 *
	 * @var int 
	 */
	var $queryCount ;

	/**
	 *
	 * @var int 
	 */
	var $errorLevel ;
	/**
	 *
	 * @var string 
	 */
	var $errorDesc ;

	/**
	 *
	 * @var array 
	 */
	var $aSelect ;
	/* var $aDistinct ; */
	/**
	 *
	 * @var array 
	 */
	var $aFrom ;

	/**
	 *
	 * @var array 
	 */
	var $aWhere ;

	/**
	 *
	 * @var array 
	 */
	var $aGroupby ;
	/**
	 *
	 * @var array 
	 */
	var $aOptions;
	/* var $aKeys ; */
	/**
	 *
	 * @var mixed 
	 */
	var $aLimit ;
	/**
	 *
	 * @var mixed 
	 */
	var $aOffset ;
	/**
	 *
	 * @var mixed 
	 */
	var $aOrder ;
	/**
	 *
	 * @var array 
	 */
	var $aOrderby ;
	/**
	 *
	 * @var array 
	 */
	var $aSet ;

	/* var $aAliasedTables ; */
	/* var $aStoreArray ; */

	/**
	 * 
	 * @var LogDatabase 
	 */
	var $log ;

	/**
	 * Initializate variables
	 * 
	 * @param mysqli $connId 
	 */
	function __construct(&$connId)
	{
		$this->connId     = &$connId ;
		$this->resultId   = 0 ;

		$this->queries    = array() ;
		$this->queryTimes = array() ;
		$this->queryCount = 0 ;

		$this->errorLevel = 0 ;
		$this->errorDesc  = "" ;

		$this->aSelect    = array() ;
		$this->aFrom      = array() ;
		$this->aWhere     = array() ;
		$this->aGroupby   = array() ;
		$this->aOptions   = array() ;
		$this->aLimit     = false ;
		$this->aOffset    = false ;
		$this->aOrder     = false ;
		$this->aOrderby   = array() ;

		$this->log        = LogDatabase::newInstance() ;
	}

	/**
	 * Unset connection and result objects
	 */
	function __destruct()
	{
		unset($this->connId) ;
		unset($this->resultId) ;
	}

	/**
	 * It creates a new DBCommandClass object or if it has been created before, it 
	 * returns the previous object
	 * 
	 * @access public
	 * @since 2.3
	 * @return DBCommandClass 
	 */
	public static function newInstance()
	{
		if(!self::$instance instanceof self) {
			self::$instance = new self ;
		}
		return self::$instance ;
	}

	/**
	 * Set SELECT clause
	 * 
	 * @access public
	 * @since 2.3
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
	 * Set FROM clause
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
	 * Set WHERE clause using OR operator
	 * 
	 * @access public
	 * @since 2.3
	 * @param mixed $key
	 * @param mixed $value 
	 * @return DBCommandClass 
	 */
	function where($key, $value = null)
	{
		return $this->_where($key, $value, 'AND ') ;
	}

	/**
	 * Set WHERE clause using OR operator
	 * 
	 * @access public
	 * @since 2.3
	 * @param mixed $key
	 * @param mixed $value 
	 * @return DBCommandClass 
	 */
	//function orWhere($key, $value = null)
	//{
	//	return $this->_where($key, $value, 'OR ') ;
	//}

	/**
	 * Set WHERE clause
	 * 
	 * @access private
	 * @since 2.3
	 * @param mixed $key
	 * @param mixed $value
	 * @param string $type
	 * @return DBCommandClass 
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
	 * Set WHERE IN clause using AND operator
	 * 
	 * @access public
	 * @since 2.3
	 * @param mixed $key
	 * @param mixed $values
	 * @return DBCommandClass 
	 */
	function whereIn($key = null, $values = null)
	{
		return $this->_whereIn($key, $values, false, 'AND ') ;
	}

	/**
	 * Set WHERE NOT IN clause using AND operator
	 * 
	 * @access public
	 * @since 2.3
	 * @param mixed $key
	 * @param mixed $values
	 * @return DBCommandClass 
	 */
	function whereNotIn($key = null, $values = null)
	{
		return $this->_whereIn($key, $values, true, 'AND ') ;
	}


	/**
	 * Set WHERE IN clause
	 * 
	 * @access private
	 * @since 2.3
	 * @param mixed $key
	 * @param mixed $values
	 * @param bool $not
	 * @param string $type
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

		$whereIn = $prefix . $key . $not . ' IN (' . implode(', ', $this->aWherein) . ') ' ;

		$this->aWhere[] = $whereIn ;
		$this->aWherein = array() ;
		return $this ;
	}

	/**
	 * Fields for GROUP BY clause
	 *
	 * @access public
	 * @since 2.3
	 * @param mixed $by
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
	 * Set ORDER BY clause
	 * 
	 * @access public
	 * @since 2.3
	 * @param string $orderby 
	 * @param string $direction Accepted directions: random, asc, desc
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
	 * Set LIMIT clause
	 * 
	 * @access public
	 * @since 2.3
	 * @param int $value
	 * @param int $offset
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
	 * Set the offset in the LIMIT clause
	 * 
	 * @access public
	 * @since 2.3
	 * @param int $offset
	 * @return DBCommandClass 
	 */
	function offset($offset)
	{
		$this->aOffset = $offset ;
		return $this ;
	}

	/**
	 * Compile the select sql string and perform the query. Quick method for 
	 * getting the rows of one table
	 *
	 * @access public
	 * @since 2.3
	 * @param mixed $table
	 * @param mixed $limit
	 * @param mixed $offset
	 * @return mixed 
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
	 * Performs a query on the database
	 *
	 * @access public
	 * @since 2.3
	 * @param string $sql
	 * @return mixed 
	 */
	function query($sql)
	{
		if($sql == '') {
			return false ;
		}

		if( OSC_DEBUG_DB_EXPLAIN && $this->isSelectType($sql) ) {
			$this->query_debug($sql) ;
		}

		$this->queries[] = $sql ;
		$timeStart = list($sm, $ss) = explode(' ', microtime()) ;

		$this->resultId = $this->_execute($sql) ;

		$this->errorReport() ;
		if( false === $this->resultId ) {
			if( OSC_DEBUG_DB ) {
				$this->log->addMessage($sql, 0, $this->errorLevel, $this->errorDesc) ;
			}
			return false ;
		}

		$timeEnd = list($em, $es) = explode(' ', microtime()) ;
		$this->queryTimes[] = ($em + $es) - ($sm + $ss) ;

		$this->queryCount++ ;

		if( OSC_DEBUG_DB ) {
			$this->log->addMessage($sql, ($em + $es) - ($sm + $ss), $this->errorLevel, $this->errorDesc) ;
		}
		
		$rs           = new DBRecordsetClass() ;
		$rs->connId   = $this->connId ;
		$rs->resultId = $this->resultId ;
		$rs->numRows  = $rs->numRows() ;
		
		return $rs ;
	}

	function query_debug($sql)
	{
		if($sql == '') {
			return false ;
		}

		$sql  = 'EXPLAIN ' . $sql ;
		$rsID = $this->_execute($sql) ;

		if( false === $rsID ) {
			return false ;
		}

		$rs           = new DBRecordsetClass() ;
		$rs->connId   = $this->connId ;
		$rs->resultId = $rsID ;
		$rs->numRows  = $rs->numRows() ;

		if( $rs->numRows() == 0 ) {
			return false ;
		}

		$this->log->addExplainMessage($sql, $rs->result()) ;

		return true ;
	}

	/**
	 * Performs a query on the database
	 *
	 * @access private
	 * @since 2.3
	 * @param string $sql
	 * @return mixed 
	 */
	function _execute($sql)
	{
		return $this->connId->query($sql) ;
	}

	/**
	 * Check if $table exist into array $struct_queries
	 * 
	 * @param string $table 
	 * @param array $struct_queries
	 */
	private function existTableIntoStruct($table, $struct_queries)
	{
		return array_key_exists(strtolower($table), $struct_queries) ;
	}
	
	/**
	 * Get fields from struct_queries (struct.sql)
	 * 
	 * @param string $table
	 * @param array $struct_queries
	 */
	private function getTableFieldsFromStruct($table, &$struct_queries)
	{
		if(preg_match('|\((.*)\)|ms', $struct_queries[strtolower($table)], $match)) {
			$fields = explode("\n", trim($match[1]));
		} else {
			$fields = false;
		}
		return $fields;
	}
	
	
	

	/**
	 * Set aSet array
	 * 
	 * @access public
	 * @since 2.3
	 * @param mixed $key
	 * @param mixed $value
	 * @return DBCommandClass 
	 */
	function set($key, $value = '', $escape = true)
	{
		if( !is_array($key) ) {
			$key = array($key => $value) ;
		}

		foreach($key as $k => $v) {
			if( $escape ) {
				$this->aSet[$k] = $this->escape($v) ;
			} else {
				$this->aSet[$k] = $v ;
			}
		}

		return $this ;
	}

	/**
	 * Create SELECT sql statement
	 * 
	 * @access private
	 * @since 2.3
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
			
			if(count($this->aFrom) == 1 )
			{
				$sql .= $this->aFrom[0];
			}
			else
			{
				$sql .= '(' . implode(', ', $this->aFrom) . ')' ;
			}
			
		}

		// "WHERE" portion of the query
		if( count($this->aWhere) > 0 || count($this->aLike) > 0 ) {
			$sql .= "\n" ;
			$sql .= "WHERE " ;
		}

		$sql .= implode("\n", $this->aWhere) ;

		

		// "GROUP BY" portion of the query
		if( count($this->aGroupby) > 0 ) {
			$sql .= "\nGROUP BY " ;
			$sql .= implode(', ', $this->aGroupby) ;
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
				$sql .= ", " . $this->aOffset ;
			}
		}

		return $sql ;
	}

	/**
	 * Gets the number of affected rows in a previous MySQL operation
	 * 
	 * @access public
	 * @since 2.3
	 * @return int 
	 */
	function affectedRows()
	{
		return $this->connId->affected_rows ;
	}

	/**
	 * Get last SQL query
	 * 
	 * @access public
	 * @since 2.3
	 * @return string 
	 */
	function lastQuery()
	{
		return end($this->queries) ;
	}

	/**
	 * Check if the string has an operator
	 * 
	 * @access private
	 * @since 2.3
	 * @param string $str
	 * @return bool 
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
	 * Check if the sql is a select
	 * 
	 * @access private
	 * @since 2.3
	 * @param string $sql
	 * @return bool 
	 */
	function isSelectType($sql)
	{
		if ( ! preg_match('/^\s*"?(SELECT)\s+/i', $sql)) {
			return false;
		}

		return true;
	}

	/**
	 * Add the apostrophe if it's an string; 0 or 1 if it's a number; NULL
	 * 
	 * @access private
	 * @since 2.3
	 * @param string $str
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
	 * Escape the string if it's necessary
	 * 
	 * @access private
	 * @since 2.3
	 * @param string $str
	 * @return string 
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
	 * Reset variables used in write sql: aSet, aFrom, aWhere, aLike, aOrderby, aLimit, aOrder
	 * 
	 * @access private
	 * @since 2.3
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
	 * Reset variables used in select sql: aSelect, aFrom, aJoin, aWhere, aLike, aGroupby, aHaving, 
	 * aOrderby, aWherein, aLimit, aOffset, aOrder
	 * 
	 * @access private
	 * @since 2.3
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
	 * Initializate $aReset variables
	 * 
	 * @access private
	 * @since 2.3
	 * @param array $aReset 
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

/* file end: ./oc-includes/osclass/classes/database/DBCommandClass.php */
?>