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
	class DAO
    {
        /**
         *
         * @var type 
         */
        var $dao ;
        /**
         * 
         * @var type 
         */
        var $tableName ;
        /**
         *
         * @var type 
         */
        var $primaryKey ;
        /**
         *
         * @var type 
         */
        var $fields ;

        /**
         * 
         */
        function __construct()
        {
            $conn = DBConnectionClass::newInstance() ;
            $this->dao = new DBCommandClass($conn->getOsclassDb()) ;
        }

        /**
         *
         * @param type $value
         * @return type 
         */
        function findByPrimaryKey($value)
        {
            $this->dao->select($this->fields) ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where($this->primaryKey, $value) ;
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
         *
         * @param type $table 
         */
        function setTableName($table)
        {
            $this->tableName = DB_TABLE_PREFIX . $table ;
        }

        /**
         *
         * @return type 
         */
        function getTableName()
        {
            return $this->tableName ;
        }

        /**
         *
         * @param type $key 
         */
        function setPrimaryKey($key)
        {
            $this->primaryKey = $key ;
        }

        /**
         *
         * @return type 
         */
        function getPrimaryKey()
        {
            return $this->primaryKey ;
        }

        /**
         *
         * @param type $fields 
         */
        function setFields($fields)
        {
            $this->fields = $fields ;
        }

        /**
         * 
         */
        function getFields()
        {
            return $this->fields ;
        }
	}

    /* file end: ./oc-includes/osclass/classes/data/DAO.php */
?>