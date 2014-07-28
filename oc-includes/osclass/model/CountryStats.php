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
     * Model database for CountryStats table
     *
     * @package Osclass
     * @subpackage Model
     * @since 2.4
     */
    class CountryStats extends DAO
    {
        /**
         * It references to self object: CountryStats.
         * It is used as a singleton
         *
         * @access private
         * @since 2.4
         * @var CountryStats
         */
        private static $instance;

        /**
        * It creates a new CountryStats object class if it has been created
        * before, it return the previous object
        *
        * @access public
        * @since 2.4
        * @return CountryStats
        */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_country_stats table
         *
         * @access public
         * @since 2.4
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_country_stats');
            $this->setPrimaryKey('fk_c_country_code');
            $this->setFields( array('fk_c_country_code', 'i_num_items') );
        }

        /**
         * Increase number of country items, given a country id
         *
         * @access public
         * @since 2.4
         * @param int $countryCode Country code
         * @return int number of affected rows, id error occurred return false
         */
        public function increaseNumItems($countryCode)
        {
            $lenght = strlen($countryCode);
            if($lenght > 2 || $lenght=='' ) {
                return false;
            }
            $sql = sprintf('INSERT INTO %s (fk_c_country_code, i_num_items) VALUES (\'%s\', 1) ON DUPLICATE KEY UPDATE i_num_items = i_num_items + 1', $this->getTableName(), $countryCode);
            return $this->dao->query($sql);
        }

        /**
         * Increase number of country items, given a Country code
         *
         * @access public
         * @since 2.4
         * @param int $countrycode Country code
         * @return int number of affected rows, id error occurred return false
         */
        public function decreaseNumItems($countryCode)
        {
            $lenght = strlen($countryCode);
            if($lenght > 2 || $lenght=='' ) {
                return false;
            }
            $this->dao->select( 'i_num_items' );
            $this->dao->from( $this->getTableName() );
            $this->dao->where( $this->getPrimaryKey(), $countryCode );
            $result       = $this->dao->get();
            $countryStat  = $result->row();
            $return       = 0;

            if( isset( $countryStat['i_num_items'] ) ) {
                $this->dao->from( $this->getTableName() );
                $this->dao->set( 'i_num_items', 'i_num_items - 1', false );
                $this->dao->where( 'i_num_items > 0' );
                $this->dao->where( 'fk_c_country_code', $countryCode );

                return $this->dao->update();
            }

            return false;
        }

        /**
         * Set i_num_items, given a country code
         *
         * @access public
         * @since 2.4
         * @param type $countryCode
         * @param type $numItems
         * @return type
         */
        public function setNumItems($countryCode, $numItems)
        {
            return $this->dao->query("INSERT INTO ".$this->getTableName()." (fk_c_country_code, i_num_items) VALUES ('$countryCode', $numItems) ON DUPLICATE KEY UPDATE i_num_items = ".$numItems);
        }

        /**
         * Find stats by country code
         *
         * @access public
         * @since 2.4
         * @param int $countryCode country id
         * @return array
         */
        public function findByCountryCode($countryCode)
        {
            return $this->findByPrimaryKey($countryCode);
        }


        /**
         * Return a list of countries and counter items.
         * Can be filtered by num_items,
         * and ordered by country_name or items counter.
         * $order = 'country_name ASC' OR $oder = 'items DESC'
         *
         * @access public
         * @since 2.4
         * @param string $zero
         * @param string $order
         * @return array
         */
        public function listCountries($zero = ">", $order = "country_name ASC")
        {
            $this->dao->select($this->getTableName().'.fk_c_country_code as country_code, '.$this->getTableName().'.i_num_items as items, '.DB_TABLE_PREFIX.'t_country.s_name as country_name, '.DB_TABLE_PREFIX.'t_country.s_slug as country_slug');
            $this->dao->from($this->getTableName() );
            $this->dao->join(DB_TABLE_PREFIX.'t_country', $this->getTableName().'.fk_c_country_code = '.DB_TABLE_PREFIX.'t_country.pk_c_code');
            $this->dao->where('i_num_items '.$zero.' 0' );
            $this->dao->orderBy($order);

            $rs = $this->dao->get();

            if($rs === false) {
                return array();
            }
            return $rs->result();
        }

        /**
         * Calculate the total items that belong to countryCode
         * @access public
         * @since 2.4
         * @param type $countryCode
         * @return int total items
         */
        function calculateNumItems($countryCode)
        {
            $sql  = 'SELECT count(*) as total FROM '.DB_TABLE_PREFIX.'t_item_location, '.DB_TABLE_PREFIX.'t_item, '.DB_TABLE_PREFIX.'t_category ';
            $sql .= 'WHERE '.DB_TABLE_PREFIX.'t_item_location.fk_c_country_code = \''.$countryCode.'\' AND ';
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

    /* file end: ./oc-includes/osclass/model/CountryStats.php */
?>