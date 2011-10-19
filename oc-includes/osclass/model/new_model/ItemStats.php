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
     * Model database for ItemStat table
     * 
     * @package OSClass
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
        private static $instance ;

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
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Set data related to t_item_stats table
         */
        public function __construct()
        {
            parent::__construct() ;
            $this->setTableName('t_item_stats') ;
            $this->setPrimaryKey('fk_i_item_id') ;
            $this->setFields( array('fk_i_item_id', 'i_num_views', 'i_num_spam', 'i_num_repeated', 'i_num_bad_classified', 
                                    'i_num_offensive', 'i_num_expired', 'i_num_premium_views', 'dt_date') ) ;
        }

        /**
         * Increase the stat column given column name and item id
         *
         * @param string $column
         * @param int $itemId
         * @return bool 
         * @todo OJO query('update ....') cambiar a ->update()
         */
        function increase($column, $itemId)
        {
            $increaseColumns = array('i_num_views', 'i_num_spam', 'i_num_repeated', 'i_num_bad_classified', 'i_num_offensive',
                                     'i_num_expired', 'i_num_expired', 'i_num_premium_views') ;

            if( !in_array($column, $increaseColumns) ) {
                return false ;
            }

            $result = $this->insert( array(
                'fk_i_item_id' => $itemId,
                'dt_date'      => '',
                $column        => 1
            ) ) ;

            // duplicated key
            if( $this->dao->getErrorLevel() == 1062 ) {
                $result = $this->dao->query('UPDATE '.$this->getTableName().' SET i_num_views = i_num_views + 1 WHERE fk_i_item_id = '.$itemId) ;
            }

            return $result ;
        }

        /**
         * Insert an empty row into table item stats
         *
         * @param int $itemId Item id
         * @return bool 
         */
        function emptyRow($itemId)
        {
            return $this->insert( array(
                'fk_i_item_id' => $itemId,
                'dt_date'      => date('Y-m-d H:i:s')
            ) ) ;
        }

    }

    /* file end: ./oc-includes/osclass/model/new_model/ItemStats.php */
?>