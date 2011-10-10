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
     * DAO base model
     * 
     * @package OSClass
     * @subpackage Model
     * @since 2.3
     */
	class DAO
    {
        /**
         * DBCommandClass object
         * 
         * @acces public
         * @since 2.3
         * @var DBCommandClass 
         */
        var $dao ;
        /**
         * Table name
         * 
         * @access private
         * @since unknown
         * @var string 
         */
        var $tableName ;
        /**
         * Primary key of the table
         *
         * @access private
         * @since 2.3
         * @var string 
         */
        var $primaryKey ;
        /**
         * Fields of the table
         * 
         * @access private
         * @since 2.3
         * @var array 
         */
        var $fields ;

        /**
         * Init connection of the database and create DBCommandClass object
         */
        function __construct()
        {
            $conn = DBConnectionClass::newInstance() ;
            $this->dao = new DBCommandClass($conn->getOsclassDb()) ;
        }

        /**
         * Get the result match of the primary key passed by paramete
         * 
         * @access public
         * @since unknown
         * @param string $key
         * @return boolean|array 
         */
        function findByPrimaryKey($key)
        {
            $this->dao->select($this->fields) ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where($this->getPrimaryKey(), $key) ;
            $result = $this->dao->get() ;

            if( $result === false ) {
                return false ;
            }

            if( $result->numRows() !== 1 ) {
                return false ;
            }

            return $result->row() ;
        }

        /**
         * Delete the result match from the primary key passed by parameter
         * 
         * @access public
         * @since unknown
         * @param string $value
         * @return boolean 
         */
        function deleteByPrimaryKey($key)
        {
            $this->dao->from($this->getTableName()) ;
            $this->dao->where($this->getPrimaryKey(), $key) ;
            return $this->dao->delete() ;
        }

        /**
         * Get all the rows from the table $tableName
         * 
         * @access public
         * @since unknown
         * @return array 
         */
        function listAll()
        {
            $this->dao->select($this->getFields()) ;
            $this->dao->from($this->getTableName()) ;
            $result = $this->dao->get() ;

            if($result == false) {
                return array() ;
            }

            return $result ;
        }

        /**
         * Set table name, adding the DB_TABLE_PREFIX at the beginning
         * 
         * @access private
         * @since unknown
         * @param string $table 
         */
        function setTableName($table)
        {
            $this->tableName = DB_TABLE_PREFIX . $table ;
        }

        /**
         * Get table name
         * 
         * @access public
         * @since unknown
         * @return string 
         */
        function getTableName()
        {
            return $this->tableName ;
        }

        /**
         * Set primary key string
         * 
         * @access private
         * @since unknown
         * @param string $key 
         */
        function setPrimaryKey($key)
        {
            $this->primaryKey = $key ;
        }

        /**
         * Get primary key string
         * 
         * @access public
         * @since unknown
         * @return string 
         */
        function getPrimaryKey()
        {
            return $this->primaryKey ;
        }

        /**
         * Set fields array
         * 
         * @access private
         * @since 2.3
         * @param array $fields 
         */
        function setFields($fields)
        {
            $this->fields = $fields ;
        }

        /**
         * Get fields array
         * 
         * @access public
         * @since 2.3
         * @return array 
         */
        function getFields()
        {
            return $this->fields ;
        }
	}

    /* file end: ./oc-includes/osclass/classes/data/DAO.php */
?>