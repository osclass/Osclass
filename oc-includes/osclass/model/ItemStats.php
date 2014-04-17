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
     * Model database for ItemStat table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class ItemStats extends DAO
    {
        /**
         * It references to self object: ItemStats.
         * It is used as a singleton
         *
         * @access private
         * @since unknown
         * @var ItemStats
         */
        private static $instance;

        /**
         * It creates a new ItemStats object class ir if it has been created
         * before, it return the previous object
         *
         * @access public
         * @since unknown
         * @return ItemStats
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_item_stats table
         */
        public function __construct()
        {
            parent::__construct();
            $this->setTableName('t_item_stats');
            $this->setPrimaryKey('fk_i_item_id');
            $this->setFields( array('fk_i_item_id', 'i_num_views', 'i_num_spam', 'i_num_repeated', 'i_num_bad_classified',
                                    'i_num_offensive', 'i_num_expired', 'i_num_premium_views', 'dt_date') );
        }

        /**
         * Increase the stat column given column name and item id
         *
         * @access public
         * @since unknown
         * @param string $column
         * @param int $itemId
         * @return bool
         * @todo OJO query('update ....') cambiar a ->update()
         */
        function increase($column, $itemId)
        {

            //('INSERT INTO %s (fk_i_item_id, dt_date, %3$s) VALUES (%d, \'%4$s\',1) ON DUPLICATE KEY UPDATE %3$s = %3$s + 1', $this->getTableName(), $id, $column, date('Y-m-d H:i:s'));
            $increaseColumns = array('i_num_views', 'i_num_spam', 'i_num_repeated', 'i_num_bad_classified', 'i_num_offensive',
                                     'i_num_expired', 'i_num_expired', 'i_num_premium_views');

            if( !in_array($column, $increaseColumns) ) {
                return false;
            }

            if (!is_numeric($itemId)) {
                return false;
            }

            $sql = 'INSERT INTO '.$this->getTableName().' (fk_i_item_id, dt_date, '.$column.') VALUES ('.$itemId.', \''.date('Y-m-d H:i:s').'\',1) ON DUPLICATE KEY UPDATE  '.$column.' = '.$column.' + 1 ';
            return $this->dao->query($sql);

        }

        /**
         * Insert an empty row into table item stats
         *
         * @access public
         * @since unknown
         * @param int $itemId Item id
         * @return bool
         */
        function emptyRow($itemId)
        {
            return $this->insert( array(
                'fk_i_item_id' => $itemId,
                'dt_date'      => date('Y-m-d H:i:s')
            ) );
        }

        /**
         * Return number of views of an item
         *
         * @access public
         * @since 2.3.3
         * @param int $itemId Item id
         * @return int
         */
        function getViews($itemId)
        {
            $this->dao->select('SUM(i_num_views) AS i_num_views');
            $this->dao->from($this->getTableName());
            $this->dao->where('fk_i_item_id', $itemId);
            $result = $this->dao->get();
            if(!$result) {
                return 0;
            } else {
                $res = $result->result();
                return $res[0]['i_num_views'];
            }
        }

        /**
         * Return number of views of an item
         *
         * @access public
         * @since 2.3.3
         * @param int $itemId Item id
         * @return int
         */
        function getAllViews()
        {
            $this->dao->select('SUM(i_num_views) AS i_num_views');
            $this->dao->from($this->getTableName());
            $result = $this->dao->get();
            if(!$result) {
                return 0;
            } else {
                $res = $result->result();
                return $res[0]['i_num_views'];
            }
        }
    }

    /* file end: ./oc-includes/osclass/model/ItemStats.php */
?>