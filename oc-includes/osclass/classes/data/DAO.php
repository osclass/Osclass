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
        var $table_name ;
        /**
         *
         * @var type 
         */
        var $primary_key ;
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
            $conn = new DBConnectionClass() ;
            $conn->init(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, 0) ;
            $this->dao = new DBCommandClass($conn->get_db()) ;
        }

        /**
         *
         * @param type $value
         * @return type 
         */
        function find_by_primary_key($value)
        {
            $this->dao->select($this->fields) ;
            $this->dao->from($this->table_name) ;
            $this->dao->where($this->primary_key, $value) ;
            $result = $this->dao->get() ;

            if( $result === false ) {
                return false ;
            }

            if( $result->num_rows() !== 1 ) {
                return false ;
            }

            return $result->row() ;
        }

        /**
         *
         * @param type $table 
         */
        function set_table_name($table)
        {
            $this->table_name = DB_TABLE_PREFIX . $table ;
        }

        /**
         *
         * @param type $key 
         */
        function set_primary_key($key)
        {
            $this->primary_key = $key ;
        }

        /**
         *
         * @param type $fields 
         */
        function set_fields($fields)
        {
            $this->fields = $fields ;
        }
	}

    /* file end: ./oc-includes/osclass/classes/data/DAO.php */
?>