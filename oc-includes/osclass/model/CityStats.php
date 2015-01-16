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
     * Model database for CityStats table
     *
     * @package Osclass
     * @subpackage Model
     * @since 2.4
     */
    class CityStats extends DAO
    {
        /**
         * It references to self object: CityStats.
         * It is used as a singleton
         *
         * @access private
         * @since 2.4
         * @var CityStats
         */
        private static $instance;

        /**
        * It creates a new CityStats object class if it has been created
        * before, it return the previous object
        *
        * @access public
        * @since 2.4
        * @return CategoryStats
        */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_city_stats table
         *
         * @access public
         * @since 2.4
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_city_stats');
            $this->setPrimaryKey('fk_i_city_id');
            $this->setFields( array('fk_i_city_id', 'i_num_items') );
        }

        /**
         * Increase number of city items, given a city id
         *
         * @access public
         * @since 2.4
         * @param int $cityId City id
         * @return int number of affected rows, id error occurred return false
         */
        public function increaseNumItems($cityId)
        {
            if(!is_numeric($cityId)) {
                return false;
            }
            return $this->dao->query(sprintf('INSERT INTO %s (fk_i_city_id, i_num_items) VALUES (%d, 1) ON DUPLICATE KEY UPDATE i_num_items = i_num_items + 1', $this->getTableName(), $cityId));
        }

        /**
         * Increase number of city items, given a city id
         *
         * @access public
         * @since 2.4
         * @param int $cityId City id
         * @return int number of affected rows, id error occurred return false
         */
        public function decreaseNumItems($cityId)
        {
            if(!is_numeric($cityId)) {
                return false;
            }

            $this->dao->select( 'i_num_items' );
            $this->dao->from( $this->getTableName() );
            $this->dao->where( $this->getPrimaryKey(), $cityId );
            $result       = $this->dao->get();
            $cityStat = $result->row();

            if( isset( $cityStat['i_num_items'] ) ) {
                $this->dao->from( $this->getTableName() );
                $this->dao->set( 'i_num_items', 'i_num_items - 1', false );
                $this->dao->where( 'i_num_items > 0' );
                $this->dao->where( 'fk_i_city_id', $cityId );

                return $this->dao->update();
            }

            return false;
        }

        /**
         * Set i_num_items, given a city id
         *
         * @access public
         * @since 2.4
         * @param type $cityID
         * @param type $numItems
         * @return type
         */
        public function setNumItems($cityID, $numItems)
        {
            return $this->dao->query("INSERT INTO ".$this->getTableName()." (fk_i_city_id, i_num_items) VALUES ($cityID, $numItems) ON DUPLICATE KEY UPDATE i_num_items = ".$numItems);
        }

        /**
         * Find stats by city id
         *
         * @access public
         * @since 2.4
         * @param int $cityId city id
         * @return array
         */
        public function findByCityId($cityId)
        {
            return $this->findByPrimaryKey($cityId);
        }

        /**
         *
         * @param type $regionId
         * @return type
         */
        public function deleteByRegion($regionId)
        {
            return $this->dao->query('DELETE FROM '.DB_TABLE_PREFIX.'t_city_stats WHERE fk_i_city_id IN (SELECT pk_i_id FROM '.DB_TABLE_PREFIX.'t_city WHERE fk_i_region_id = '.$regionId.');');
        }

        /**
         * Return a list of cities and counter items.
         * Can be filtered by region and num_items,
         * and ordered by city_name or items counter
         * $order = 'city_name ASC' OR $oder = 'items DESC'
         *
         * @param int $region
         * @param string $zero
         * @param string $order
         * @return array
         */
        public function listCities($region = null, $zero = ">", $order = "city_name ASC")
        {
            $key    = md5(osc_base_url().(string)$region.(string)$zero.(string)$order);
            $found  = null;
            $cache  = osc_cache_get($key, $found);
            if($cache===false) {
                $this->dao->select($this->getTableName().'.fk_i_city_id as city_id, '.$this->getTableName().'.i_num_items as items, '.DB_TABLE_PREFIX.'t_city.s_name as city_name, '.DB_TABLE_PREFIX.'t_city.s_slug as city_slug');
                $this->dao->from( $this->getTableName() );
                $this->dao->join(DB_TABLE_PREFIX.'t_city', $this->getTableName().'.fk_i_city_id = '.DB_TABLE_PREFIX.'t_city.pk_i_id', 'LEFT');
                $this->dao->where('i_num_items '.$zero.' 0' );
                if( is_numeric($region) ) {
                    $this->dao->where(DB_TABLE_PREFIX.'t_city.fk_i_region_id = '.$region);
                }
                $this->dao->orderBy($order);

                $rs = $this->dao->get();

                if($rs === false) {
                    return array();
                }
                $return = $rs->result();
                osc_cache_set($key, $return, OSC_CACHE_TTL);
                return $return;
            } else {
                return $cache;
            }
        }

        /**
         * Calculate the total items that belong to city id
         *
         * @param type $cityId
         * @return int total items
         */
        function calculateNumItems($cityId)
        {
            $sql  = 'SELECT count(*) as total FROM '.DB_TABLE_PREFIX.'t_item_location, '.DB_TABLE_PREFIX.'t_item, '.DB_TABLE_PREFIX.'t_category ';
            $sql .= 'WHERE '.DB_TABLE_PREFIX.'t_item_location.fk_i_city_id = '.$cityId.' AND ';
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

    /* file end: ./oc-includes/osclass/model/CityStats.php */
?>