<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    /**
     * Model database for ItemResource table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class ItemResource extends DAO
    {
        /**
         * It references to self object: ItemResource.
         * It is used as a singleton
         *
         * @access private
         * @since unknown
         * @var ItemResource
         */
        private static $instance;

        /**
         * It creates a new ItemResource object class ir if it has been created
         * before, it return the previous object
         *
         * @access public
         * @since unknown
         * @return ItemResource
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_item_resource table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_item_resource');
            $this->setPrimaryKey('pk_i_id');
            $this->setFields( array('pk_i_id', 'fk_i_item_id', 's_name', 's_extension', 's_content_type', 's_path') );
        }

        /**
         * Get all resources
         *
         * @access public
         * @since unknown
         * @param int $itemId Item id
         * @return array of resources
         */
        function getAllResources()
        {
            $this->dao->select('r.*, c.dt_pub_date');
            $this->dao->from($this->getTableName() . ' r');
            $this->dao->join($this->getTableItemName() . ' c', 'c.pk_i_id = r.fk_i_item_id');

            $result = $this->dao->get();

            if( $result == false ) {
                return array();
            }

            return $result->result();
        }

        /**
         * Get all resources belong to an item given its id
         *
         * @access public
         * @since 2.3.7
         * @param int $itemId Item id
         * @return array of resources
         */
        function getAllResourcesFromItem($itemId) {
            $key    = md5(osc_base_url().'ItemResource:getAllResourcesFromItem:'.$itemId);
            $found  = null;
            $cache  = osc_cache_get($key, $found);
            if($cache===false) {
                $this->dao->select();
                $this->dao->from($this->getTableName());
                $this->dao->where('fk_i_item_id', (int)$itemId);

                $result = $this->dao->get();

                if( $result == false ) {
                    return array();
                }

                $return = $result->result();
                osc_cache_set($key, $return, OSC_CACHE_TTL);
                return $return;
            } else {
                return $cache;
            }
        }

        /**
         * Get first resource belong to an item given it id
         *
         * @access public
         * @since unknown
         * @param int $itemId Item id
         * @return array resource
         */
        function getResource($itemId)
        {
            $this->dao->select( $this->getFields() );
            $this->dao->from( $this->getTableName() );
            $this->dao->where('fk_i_item_id', $itemId);
            $this->dao->limit(1);

            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            if($result->numRows == 0) {
                return array();
            }

            return $result->row();
        }

        /**
         * Check if resource id and name exist
         *
         * @deprecated since 2.3
         * @param int $resourceId
         * @param string $code
         * @return bool
         */
        function getResourceSecure($resourceId, $code)
        {
            return $this->existResource($resourceId, $code);
        }

        /**
         * Check if resource id and name exist
         *
         * @access public
         * @since unknown
         * @param int $resourceId
         * @param string $code
         * @return bool
         */
        function existResource($resourceId, $code)
        {
            $this->dao->select('COUNT(*) AS numrows');
            $this->dao->from( $this->getTableName() );
            $this->dao->where('pk_i_id', $resourceId);
            $this->dao->where('s_name', $code);

            $result = $this->dao->get();

            if( $result == false ) {
                return 0;
            }

            if( $result->numRows() != 1 ) {
                return 0;
            }

            $row = $result->row();
            return $row['numrows'];
        }

        /**
         * Count resouces belong to item given its id
         *
         * @access public
         * @since unknown
         * @param int $itemId Item id
         * @return int
         */
        function countResources($itemId = null)
        {
            $this->dao->select('COUNT(*) AS numrows');
            $this->dao->from( $this->getTableName() );
            if( !is_null($itemId) && is_numeric($itemId)) {
                $this->dao->where('fk_i_item_id', $itemId);
            }

            $result = $this->dao->get();

            if( $result == false ) {
                return 0;
            }

            if( $result->numRows() != 1 ) {
                return 0;
            }

            $row = $result->row();
            return $row['numrows'];
        }

        /**
         * Get resources, if $itemId is set return resources belong to an item given its id,
         * can be filtered by $start/$end and ordered by column.
         *
         * @access public
         * @since unknown
         * @param int $itemId Item id
         * @param int $start beginig
         * @param int $length ending
         * @param string $order column order default='pk_i_id'
         * @param string $type order type [DESC|ASC]
         * @return array of resources
         */
        function getResources($itemId = NULL, $start = 0, $length = 10, $order = 'r.pk_i_id', $type = 'DESC')
        {
            if( !in_array($order, array(  0=> 'r.pk_i_id',
                    1=> 'r.pk_i_id',
                    2=> 'r.pk_i_id',
                    3=> 'r.fk_i_item_id',
                    4=> 'c.dt_pub_date')) ) {
                // order by is incorrect
                return array();
            }

            if( !in_array(strtoupper($type), array('DESC', 'ASC')) ) {
                // order type is incorrect
                return array();
            }

            $this->dao->select('r.*, c.dt_pub_date');
            $this->dao->from($this->getTableName() . ' r');
            $this->dao->join($this->getTableItemName() . ' c', 'c.pk_i_id = r.fk_i_item_id');
            if( !is_null($itemId) && is_numeric($itemId) ) {
                $this->dao->where('r.fk_i_item_id', $itemId);
            }
            $this->dao->orderBy($order, $type);
            $this->dao->limit($start);
            $this->dao->offset($length);
            $result = $this->dao->get();

            if( $result == false ) {
                return array();
            }

            return $result->result();
        }

        /**
         * Delete all resources where id is in $ids
         *
         * @param array $ids
         */
        public function deleteResourcesIds($ids)
        {
            $this->dao->whereIn('pk_i_id', $ids);
            return $this->dao->delete( $this->getTableName() );
        }

        /**
         * Return table item name
         *
         * @access public
         * @since unknown
         * @return string table name
         */
        function getTableItemName()
        {
            return $this->getTablePrefix() . 't_item';
        }

        /**
         * Return table description name
         *
         * @access public
         * @since unknown
         * @return string table description name
         */
        function getTableItemDescription()
        {
            return $this->getTablePrefix() . 't_item_description';
        }
    }

    /* file end: ./oc-includes/osclass/model/ItemResource.php */
?>