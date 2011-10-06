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
		public $connId ;
        /**
         *
         * @var type 
         */
        public $resultId ;
        /**
         *
         * @var type 
         */
        public $resultArray ;
        /**
         *
         * @var type 
         */
        public $resultObject ;
        /**
         *
         * @var type 
         */
        public $currentRow ;
        /**
         *
         * @var type 
         */
        public $numRows ;

        /**
         *
         * @param type $conn_id
         * @param type $result_id 
         */
        function __construct($connId = null, $resultId = null)
        {
            $this->connId       = $connId ;
            $this->resultId     = $resultId ;
            $this->resultArray  = array() ;
            $this->resultObject = array() ;
            $this->currentRow   = 0 ;
            $this->numRows      = 0 ;
        }

        /**
         *
         * @param type $type
         * @return type 
         */
        function result($type = 'array')
        {
            if($type == 'array') {
                return $this->resultArray() ;
            }

            return $this->resultObject() ;
        }

        /**
         *
         * @return type 
         */
        function resultArray()
        {
            if( count($this->resultArray) > 0 ) {
                return $this->resultArray ;
            }

            $this->_dataSeek(0) ;
            while($row= $this->_fetchArray()) {
                $this->resultArray[] = $row ;
            }

            return $this->resultArray ;
        }

        /**
         *
         * @return type 
         */
        function resultObject()
        {
            if( count($this->resultObject) > 0 ) {
                return $this->resultObject ;
            }

            $this->_dataSeek(0) ;
            while($row = $this->_fetchObject()) {
                $this->resultObject[] = $row ;
            }

            return $this->resultObject ;
        }

        /**
         *
         * @param type $offset
         * @return type 
         */
        function _dataSeek($offset = 0)
        {
            return $this->resultId->data_seek($offset) ;
        }

        /**
         *
         * @return type 
         */
        function _fetchObject()
        {
            return $this->resultId->fetch_object() ;
        }

        /**
         *
         * @return type 
         */
        function _fetchArray()
        {
            return $this->resultId->fetch_assoc() ;
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
                return $this->rowArray($n) ;
            }

            return $this->rowObject($n) ;
        }

        /**
         *
         * @param type $n
         * @return type 
         */
        function rowObject($n = 0)
        {
            $result = $this->resultObject() ;

            if( count($result) == 0) {
                return $result ;
            }

            if($n != $this->currentRow && isset($result[$n]) ) {
                $this->currentRow = $n;
            }

            return $result[$this->currentRow] ;
        }

        /**
         *
         * @param type $n
         * @return type 
         */
        function rowArray($n = 0)
        {
            $result = $this->resultArray() ;

            if( count($result) == 0) {
                return $result ;
            }

            if($n != $this->currentRow && isset($result[$n]) ) {
                $this->currentRow = $n;
            }

            return $result[$this->currentRow] ;
        }

        /**
         *
         * @param type $type
         * @return type 
         */
        function firstRow($type = 'array')
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
        function lastRow($type = 'array')
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
        function nextRow($type = 'array')
        {
            $result = $this->result($type) ;

            if( count($result) == 0 ) {
                return $result ;
            }

            if( isset($result[$this->currentRow + 1]) ) {
                $this->currentRow++ ;
            }

            return $result[$this->currentRow] ;
        }

        /**
         *
         * @param type $type
         * @return type 
         */
        function previousRow($type = 'array')
        {
            $result = $this->result($type) ;

            if( count($result) == 0 ) {
                return $result ;
            }

            if( isset($result[$this->currentRow - 1]) ) {
                $this->currentRow-- ;
            }

            return $result[$this->currentRow] ;
        }

        /**
         *
         * @return type 
         */
        function numRows()
        {
            return $this->resultId->num_rows ;
        }

        /**
         *
         * @return type 
         */
        function numFields()
        {
            return $this->resultId->field_count ;
        }

        /**
         *
         * @return type 
         */
        function listFields()
        {
            $fieldNames = array() ;
            while( $field = $this->resultId->fetch_field() ) {
                $fieldNames[] = $field->name ;
            }

            return $fieldNames ;
        }
	}

    /* file end: ./oc-includes/osclass/classes/data/DBRecordsetClass.php */
?>