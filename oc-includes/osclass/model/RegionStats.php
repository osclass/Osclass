<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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
     * Model database for RegionStats table
     *
     * @package Osclass
     * @subpackage Model
     * @since 2.4
     */
    class RegionStats extends DAO
    {
        /**
         * It references to self object: RegionStats.
         * It is used as a singleton
         *
         * @access private
         * @since 2.4
         * @var RegionStats
         */
        private static $instance;

        /**
        * It creates a new RegionStats object class if it has been created
        * before, it return the previous object
        *
        * @access public
        * @since 2.4
        * @return RegionStats
        */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_region_stats table
         * @access public
         * @since 2.4
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_region_stats');
            $this->setPrimaryKey('fk_i_region_id');
            $this->setFields( array('fk_i_region_id', 'i_num_items') );
        }

        /**
         * Increase number of region items, given a region id
         *
         * @access public
         * @since 2.4
         * @param int $regionId Region id
         * @return int number of affected rows, id error occurred return false
         */
        public function increaseNumItems($regionId)
        {
            if(!is_numeric($regionId)) {
                return false;
            }
            $sql = sprintf('INSERT INTO %s (fk_i_region_id, i_num_items) VALUES (%d, 1) ON DUPLICATE KEY UPDATE i_num_items = i_num_items + 1', $this->getTableName(), $regionId);
            return $this->dao->query($sql);
        }

        /**
         * Decrease number of region items, given a region id
         *
         * @access public
         * @since 2.4
         * @param int $regionId Region id
         * @return int number of affected rows, id error occurred return false
         */
        public function decreaseNumItems($regionId)
        {
            if(!is_numeric($regionId)) {
                return false;
            }
            $this->dao->select( 'i_num_items' );
            $this->dao->from( $this->getTableName() );
            $this->dao->where( $this->getPrimaryKey(), $regionId );
            $result         = $this->dao->get();
            $regionStat     = $result->row();

            if( isset( $regionStat['i_num_items'] ) ) {
                $this->dao->from( $this->getTableName() );
                $this->dao->set( 'i_num_items', 'i_num_items - 1', false );
                $this->dao->where( 'i_num_items > 0' );
                $this->dao->where( 'fk_i_region_id', $regionId );

                return $this->dao->update();
            }

            return false;
        }

        /**
         * Set i_num_items, given a region id
         *
         * @access public
         * @since 2.4
         * @param type $regionID
         * @param type $numItems
         * @return type
         */
        public function setNumItems($regionID, $numItems)
        {
            $sql = "INSERT INTO ".$this->getTableName()." (fk_i_region_id, i_num_items) VALUES ($regionID, $numItems) ON DUPLICATE KEY UPDATE i_num_items = ".$numItems;
            return $this->dao->query($sql);
        }

        /**
         * Find stats by region id
         *
         * @access public
         * @since 2.4
         * @param int $regionId region id
         * @return array
         */

        public function findByRegionId($regionId)
        {
            return $this->findByPrimaryKey($regionId);
        }

        /**
         * Return a list of regions and counter items.
         * Can be filtered by country and num_items,
         * and ordered by region_name or items counter.
         * $order = 'region_name ASC' OR $oder = 'items DESC'
         *
         * @access public
         * @since 2.4
         * @param string $country
         * @param string $zero
         * @param string $order
         * @return array
         */
        public function listRegions($country = '%%%%', $zero = ">", $order = "region_name ASC")
        {
            $order_split = explode(' ', $order);

            $this->dao->from( DB_TABLE_PREFIX.'t_region , '.$this->getTableName() );
            $this->dao->where( $this->getTableName().'.fk_i_region_id = '.DB_TABLE_PREFIX.'t_region.pk_i_id' );

            if( $order_split[0] == 'region_name' ) {
                $this->dao->select('STRAIGHT_JOIN '.$this->getTableName().'.fk_i_region_id as region_id, '.$this->getTableName().'.i_num_items as items, '.DB_TABLE_PREFIX.'t_region.s_name as region_name');
            } else if( $order_split[0] == 'items') {
                $this->dao->select($this->getTableName().'.fk_i_region_id as region_id, '.$this->getTableName().'.i_num_items as items, '.DB_TABLE_PREFIX.'t_region.s_name as region_name');
            }

            $this->dao->where('i_num_items '.$zero.' 0' );
            if( $country != '%%%%') {
                $this->dao->where(DB_TABLE_PREFIX.'t_region.fk_c_country_code = \''.$this->dao->connId->real_escape_string($country).'\' ');
            }
            $this->dao->orderBy($order);

            $rs = $this->dao->get();

            if($rs === false) {
                return array();
            }
            return $rs->result();
        }

        /**
         * Calculate the total items that belong to region
         *
         * @param type $regionId
         * @return int total items
         */
        function calculateNumItems($regionId)
        {
            $sql  = 'SELECT count(*) as total FROM '.DB_TABLE_PREFIX.'t_item_location, '.DB_TABLE_PREFIX.'t_item, '.DB_TABLE_PREFIX.'t_category ';
            $sql .= 'WHERE '.DB_TABLE_PREFIX.'t_item_location.fk_i_region_id = '.$regionId.' AND ';
            $sql .= DB_TABLE_PREFIX.'t_item.pk_i_id = '.DB_TABLE_PREFIX.'t_item_location.fk_i_item_id AND ';
            $sql .= DB_TABLE_PREFIX.'t_category.pk_i_id = '.DB_TABLE_PREFIX.'t_item.fk_i_category_id AND ';
            $sql .= DB_TABLE_PREFIX.'t_item.b_active = 1 AND '.DB_TABLE_PREFIX.'t_item.b_enabled = 1 AND '.DB_TABLE_PREFIX.'t_item.b_spam = 0 AND ';
            $sql .= '('.DB_TABLE_PREFIX.'t_item.b_premium = 1 || '.DB_TABLE_PREFIX.'t_item.dt_expiration >= \''.date('Y-m-d H:i:s').'\' ) AND ';
            $sql .= DB_TABLE_PREFIX.'t_category.b_enabled = 1 ';

            $return = $this->dao->query($sql);
            if($return === false) {
                return 0;
            }

            if($return->numRows() > 0) {
                $aux = $return->result();
                return $aux[0]['total'];
            }

            return 0;
        }

    }

    /* file end: ./oc-includes/osclass/model/RegionStats.php */
?>