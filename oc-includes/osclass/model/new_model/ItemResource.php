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

    /**
     * 
     */
    class ItemResource extends DAO
    {
        /**
         *
         * @var type 
         */
        private static $instance ;

        /**
         *
         * @return type 
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * 
         */
        function __construct()
        {
            parent::__construct() ;
            $this->setTableName('t_item_resource') ;
            $this->setPrimaryKey('pk_i_id') ;
            $this->setFields( array('pk_i_id', 'fk_i_item_id', 's_name', 's_extension', 's_content_type', 's_path') ) ;
        }

        /**
         *
         * @param type $itemId
         * @return type 
         */
        function getAllResources($itemId = null)
        {
            $this->dao->select('r.*, c.dt_pub_date') ;
            $this->dao->from($this->getTableName() . ' r') ;
            $this->dao->join($this->getTableItemName() . ' c', 'c.pk_i_id = r.fk_i_item_id') ;
            if( !is_null($itemId) ) {
                $this->dao->where('r.fk_i_item_id', $itemId) ;
            }

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->result() ;
        }

        /**
         *
         * @param type $itemId
         * @return type 
         */
        function getResource($itemId)
        {
            $this->dao->select( $this->getFields() ) ;
            $this->dao->from( $this->getTableName() ) ;
            $this->dao->where('fk_i_item_id', $itemId) ;
            $this->dao->limit(1) ;

            $result = $this->dao->get() ;

            if($result == false) {
                return array() ;
            }

            if($result->numRows == 0) {
                return array() ;
            }

            return $result->row() ;
        }

        /**
         *
         * @param type $resourceId
         * @param type $code
         * @return type 
         */
        function getResourceSecure($resourceId, $code)
        {
            $this->dao->select('COUNT(*) AS numrows') ;
            $this->dao->from( $this->getTableName() ) ;
            $this->dao->where('pk_i_id', $resourceId) ;
            $this->dao->where('s_name', $code) ;

            $result = $this->dao->get() ;

            if( $result == false ) {
                return 0 ;
            }

            if( $result->numRows() != 1 ) {
                return 0 ;
            }

            $row = $result->row() ;
            return $row['numrows'] ;
        }

        /**
         *
         * @param type $itemId
         * @return type 
         */
        function countResources($itemId = null)
        {
            $this->dao->select('COUNT(*) AS numrows') ;
            $this->dao->from( $this->getTableName() ) ;
            if( !is_null($itemId) ) {
                $this->dao->where('fk_i_item_id', $itemId) ;
            }

            $result = $this->dao->get() ;

            if( $result == false ) {
                return 0 ;
            }

            if( $result->numRows() != 1 ) {
                return 0 ;
            }

            $row = $result->row() ;
            return $row['numrows'] ;
        }

        /**
         *
         * @param type $itemId
         * @param type $start
         * @param type $length
         * @param type $order
         * @param type $type
         * @return type 
         */
        function getResources($itemId = NULL, $start = 0, $length = 10, $order = 'pk_i_id', $type = 'DESC')
        {
            if( !in_array($order, $this->getFields()) ) {
                // order by is incorrect
                return array() ;
            }

            if( !in_array(strtoupper($type), array('DESC', 'ASC')) ) {
                // order type is incorrect
                return array() ;
            }

            $this->dao->select('r.*, c.dt_pub_date') ;
            $this->dao->from($this->getTableName() . ' r') ;
            $this->dao->join($this->getTableItemName() . ' c', 'c.pk_i_id = r.fk_i_item_id') ;
            if( !is_null($itemId) ) {
                $this->dao->where('r.fk_i_item_id', $itemId) ;
            }
            $this->dao->orderBy($order, $type) ;
            $this->dao->limit($start) ;
            $this->dao->offset($length) ;

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->result() ;
        }

        /**
         *
         * @return type 
         */
        function getTableItemName()
        {
            return $this->getTablePrefix() . 't_item' ;
        }

        /**
         *
         * @return type 
         */
        function getTableItemDescription()
        {
            return $this->getTablePrefix() . 't_item_description' ;
        }
    }

    /* file end: ./oc-includes/osclass/model/new_model/ItemResource.php */
?>