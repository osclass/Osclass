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
	class DBRecordsetClass
    {
        /**
         *
         * @var type 
         */
		public $conn_id ;
        /**
         *
         * @var type 
         */
        public $result_id ;
        /**
         *
         * @var type 
         */
        public $result_array ;
        /**
         *
         * @var type 
         */
        public $result_object ;
        /**
         *
         * @var type 
         */
        public $current_row ;
        /**
         *
         * @var type 
         */
        public $num_rows ;

        /**
         *
         * @param type $conn_id
         * @param type $result_id 
         */
        function __construct($conn_id = null, $result_id = null)
        {
            $this->conn_id       = $conn_id ;
            $this->result_id     = $result_id ;
            $this->result_array  = array() ;
            $this->result_object = array() ;
            $this->current_row   = 0 ;
            $this->num_rows      = 0 ;
        }

        /**
         *
         * @param type $type
         * @return type 
         */
        function result($type = 'array')
        {
            if($type == 'array') {
                return $this->result_array() ;
            }

            return $this->result_object() ;
        }

        /**
         *
         * @return type 
         */
        function result_array()
        {
            if( count($this->result_array) > 0 ) {
                return $this->result_array ;
            }

            $this->_data_seek(0) ;
            while($row= $this->_fetch_array()) {
                $this->result_array[] = $row ;
            }

            return $this->result_array ;
        }

        /**
         *
         * @return type 
         */
        function result_object()
        {
            if( count($this->result_object) > 0 ) {
                return $this->result_object ;
            }

            $this->_data_seek(0) ;
            while($row = $this->_fetch_object()) {
                $this->result_object[] = $row ;
            }

            return $this->result_object ;
        }

        /**
         *
         * @param type $offset
         * @return type 
         */
        function _data_seek($offset = 0)
        {
            return $this->result_id->data_seek($offset) ;
        }

        /**
         *
         * @return type 
         */
        function _fetch_object()
        {
            return $this->result_id->fetch_object() ;
        }

        /**
         *
         * @return type 
         */
        function _fetch_array()
        {
            return $this->result_id->fetch_assoc() ;
        }

        /**
         *
         * @param int $n
         * @param type $type
         * @return type 
         */
        function row($n = 0, $type = 'array')
        {
            if( !is_numeric($n) ) {
                $n = 0 ;
            }

            if($type == 'array') {
                return $this->row_array($n) ;
            }

            return $this->row_object($n) ;
        }

        /**
         *
         * @param type $n
         * @return type 
         */
        function row_object($n = 0)
        {
            $result = $this->result_object() ;

            if( count($result) == 0) {
                return $result ;
            }

            if($n != $this->current_row && isset($result[$n]) ) {
                $this->current_row = $n;
            }

            return $result[$this->current_row] ;
        }

        /**
         *
         * @param type $n
         * @return type 
         */
        function row_array($n = 0)
        {
            $result = $this->result_array() ;

            if( count($result) == 0) {
                return $result ;
            }

            if($n != $this->current_row && isset($result[$n]) ) {
                $this->current_row = $n;
            }

            return $result[$this->current_row] ;
        }

        /**
         *
         * @param type $type
         * @return type 
         */
        function first_row($type = 'array')
        {
            $result = $this->result($type) ;

            if( count($result) == 0 ) {
                return $result ;
            }

            return $result[0] ;
        }

        /**
         *
         * @param type $type
         * @return type 
         */
        function last_row($type = 'array')
        {
            $result = $this->result($type) ;

            if( count($result) == 0 ) {
                return $result ;
            }

            return $result[count($result) - 1] ;
        }

        /**
         *
         * @param type $type
         * @return type 
         */
        function next_row($type = 'array')
        {
            $result = $this->result($type) ;

            if( count($result) == 0 ) {
                return $result ;
            }

            if( isset($result[$this->current_row + 1]) ) {
                $this->current_row++ ;
            }

            return $result[$this->current_row] ;
        }

        /**
         *
         * @param type $type
         * @return type 
         */
        function previous_row($type = 'array')
        {
            $result = $this->result($type) ;

            if( count($result) == 0 ) {
                return $result ;
            }

            if( isset($result[$this->current_row - 1]) ) {
                $this->current_row-- ;
            }

            return $result[$this->current_row] ;
        }

        /**
         *
         * @return type 
         */
        function num_rows()
        {
            return $this->result_id->num_rows ;
        }

        /**
         *
         * @return type 
         */
        function num_fields()
        {
            return $this->result_id->field_count ;
        }

        /**
         *
         * @return type 
         */
        function list_fields()
        {
            $field_names = array() ;
            while( $field = $this->result_id->fetch_field() ) {
                $field_names[] = $field->name ;
            }

            return $field_names ;
        }
	}

    /* file end: ./oc-includes/osclass/classes/data/DBRecordsetClass.php */
?>