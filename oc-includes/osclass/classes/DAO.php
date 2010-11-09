<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define('DB_FUNC_NOW', 'NOW()');

define('DB_CONST_TRUE', 'TRUE');
define('DB_CONST_FALSE', 'FALSE');

define('DB_CONST_NULL', 'NULL');

define('DB_CUSTOM_COND', 'DB_CUSTOM_COND');

/**
 * This is a simple DAO implementation just to use it
 * on the OSClass project.
 *
 * @author OSClass
 */
abstract class DAO {
    
    protected $conn ;
    

	/**
	 * Make a new instance of the DAO from its name.
	 */
	public static function load($entityName) {
		if(class_exists($entityName))
			return new $entityName;
		else
			return null;
	}

	public function __construct() {
	    //echo "ESTAMOS DENTRO..." . get_class($this) ;
	    //$this->getConnection(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DEBUG_LEVEL) ;
	   $this->conn = getConnection(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DEBUG_LEVEL) ;
	}
    
	function getConnection() {
        return($this->conn) ;
    } 
    
	/**
	 * Formats a value before being inserted in DB.
	 */
	public function formatValue($value) {
		if(is_null($value)) return DB_CONST_NULL;
        else $value = trim($value);
		//if($value == '') return DB_CONST_NULL;

		switch($value) {
			case DB_FUNC_NOW:
			case DB_CONST_TRUE:
			case DB_CONST_FALSE:
			case DB_CONST_NULL:
			break;
			default:
				$value = '\'' . $value . '\'' ;
			break;
		}

		return $value;
	}

	/**
	 * @return the number of rows mathing the conditions passed by parameter.
	 */
	public function exists($conditions) {
		$where = array();
		foreach($conditions as $key => $value) {
			if($key == DB_CUSTOM_COND)
				$where[] = $value;
			else
				$where[] = $key . ' = ' . $this->formatValue($value);
		}
		$where = implode(' AND ', $where);

		return $this->conn->osc_dbFetchValue("SELECT COUNT(*) FROM %s WHERE " . $where, $this->getTableName());
	}

	/**
	 * @return the number of rows mathing the conditions passed by parameter.
	 */
	public function findByConditions($conditions) {
		$where = array();
		foreach($conditions as $key => $value) {
			if($key == DB_CUSTOM_COND)
				$where[] = $value;
			else
				$where[] = $key . ' = ' . $this->formatValue($value);
		}
		$where = implode(' AND ', $where);

		return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE " . $where, $this->getTableName());
	}

	/**
	 * @return a row with the same id passed by parameter.
	 */
	public function findByPrimaryKey($pk) {
		return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE %s = '%s'",
			$this->getTableName(), $this->getPrimaryKey(), $pk
		);
	}

	/**
	 * Deletes a row with the id passed by parameter.
	 */
	public function deleteByID($id) {
		return $this->delete(array('pk_i_id' => $id));
	}

	/**
	 * Deletes the rows matching the conditions passed by parameter.
	 */
	public function delete($conditions) {
		$where = array();
		foreach($conditions as $key => $value) {
			if($key == DB_CUSTOM_COND)
				$where[] = $value;
			else
				$where[] = $key . ' = ' . $this->formatValue($value);
		}
		$where = implode(' AND ', $where);

		return $this->conn->osc_dbExec('DELETE FROM %s WHERE ' . $where, $this->getTableName());
	}

	/**
	 * @return the number of rows in the table represented by this object.
	 */
	public function count() {
		$result = $this->conn->osc_dbFetchResult('SELECT COUNT(*) AS count FROM %s', $this->getTableName());
		return $result['count'];
	}

	/**
	 * @return array with all the rows in this table.
	 */
	public function listAll() {
		return $this->conn->osc_dbFetchResults('SELECT * FROM %s', $this->getTableName());
	}

	/**
	 * Updates rows in the table when matching the conditions in the second parameter.
	 */
	public function update($fields, $conditions = null) {
		foreach($fields as $key => &$value)
			$value = $key . ' = ' . $this->formatValue($value);
		unset($value);
		$set = implode(', ', $fields);

		$where = '';
		if(!is_null($conditions)) {
			foreach($conditions as $key => &$value) {
				if($key != DB_CUSTOM_COND)
					$value = $key . ' = ' . $this->formatValue($value);
			}
			unset($value);
			$where = ' WHERE ' . implode(' AND ', $conditions);
		}
		$sql = 'UPDATE ' . $this->getTableName() . ' SET ' . $set . $where;

		if(defined('DEBUG')) {
			trigger_error($sql) ;
		}

		return $this->conn->osc_dbExec($sql) ;
	}

	public function insert($fields, $aFieldsDescription = null) {
            $columns = implode(', ', array_keys($fields));
            foreach($fields as &$value)
                    $value = $this->formatValue($value);
            unset($value);
            $values = implode(', ', $fields);
            $sql = 'INSERT INTO ' . $this->getTableName() . ' (' . $columns . ') VALUES (' . $values . ')';
            return $this->conn->osc_dbExec($sql);
	}
	
	public function listWhere() {
		$argv = func_get_args();
		$sql = null;
		switch(func_num_args()) {
			case 0: return array(); break;
			case 1: $sql = $argv[0]; break;
			default:
				$args = func_get_args();
				$format = array_shift($args);
				$sql = vsprintf($format, $args);
			break;
		}
		return $this->conn->osc_dbFetchResults('SELECT * FROM %s WHERE %s', $this->getTableName(), $sql);
	}

	public function listWhereCount() {
		$argv = func_get_args();
		$sql = null;
		switch(func_num_args()) {
			case 0: return array(); break;
			case 1: $sql = $argv[0]; break;
			default:
				$args = func_get_args();
				$format = array_shift($args);
				$sql = vsprintf($format, $args);
				break;
		}
		return $this->conn->osc_dbFetchResults('SELECT COUNT(*) as count FROM %s WHERE %s', $this->getTableName(), $sql);
	}

	/**
	 * @return string with the name of the primary key represented by this class.
	 */
	public function getPrimaryKey() {
		return 'pk_i_id';
	}

	/**
	 * @return string with the name of the table represented by this class.
	 */
	abstract public function getTableName();
}

