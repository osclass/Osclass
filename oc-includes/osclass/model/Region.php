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
     * Model database for Region table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class Region extends DAO
    {
        /**
         *
         * @var type
         */
        private static $instance;

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         *
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_region');
            $this->setPrimaryKey('pk_i_id');
            $this->setFields( array('pk_i_id', 'fk_c_country_code', 's_name', 'b_active', 's_slug') );
        }

        /**
         * Gets all regions from a country
         *
         * @access public
         * @since unknown
         * @deprecated since 2.3
         * @see Region::findByCountry
         * @param type $countryId
         * @return array
         */
        public function getByCountry($countryId)
        {
            return $this->findByCountry($countryId);
        }

        /**
         * Gets all regions from a country
         *
         * @access public
         * @since unknown
         * @param type $countryId
         * @return array
         */
        public function findByCountry($countryId)
        {
            $this->dao->select('*');
            $this->dao->from($this->getTableName());
            $this->dao->where('fk_c_country_code', addslashes($countryId));
            $this->dao->orderBy('s_name', 'ASC');
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            return $result->result();
        }

        /**
         * Find a region by its name and country
         *
         * @access public
         * @since unknown
         * @param string $name
         * @param string $country
         * @return array
         */
        public function findByName($name, $country = null)
        {
            $this->dao->select('*');
            $this->dao->from($this->getTableName());
            $this->dao->where('s_name', $name);
            if($country!=null) {
                $this->dao->where('fk_c_country_code', $country);
            }
            $this->dao->limit(1);
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            return $result->row();
        }

        /**
         * Function to deal with ajax queries
         *
         * @access public
         * @since unknown
         * @param type $query
         * @return array
         */
        public function ajax($query, $country = null)
        {
            $this->dao->select('pk_i_id as id, s_name as label, s_name as value');
            $this->dao->from($this->getTableName());
            $this->dao->like('s_name', $query, 'after');
            if($country != null) {
                $this->dao->where('fk_c_country_code', strtolower($country));
            }
            $this->dao->limit(5);
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            return $result->result();
        }


        /**
         *  Delete a region with its cities and city areas
         *
         *  @access public
         *  @since 3.1
         *  @param $pk
         *  @return int number of failed deletions or 0 in case of none
         */
        function deleteByPrimaryKey($pk) {
            $mCities = City::NewInstance();
            $aCities = $mCities->findByRegion($pk);
            $result = 0;
            foreach($aCities as $city) {
                $result += $mCities->deleteByPrimaryKey($city['pk_i_id']);
            }
            Item::newInstance()->deleteByRegion($pk);
            RegionStats::newInstance()->delete(array('fk_i_region_id' => $pk));
            User::newInstance()->update(array('fk_i_region_id' => null, 's_region' => ''), array('fk_i_region_id' => $pk));
            if(!$this->delete(array('pk_i_id' => $pk))) {
                $result++;
            }
            return $result;
        }

        /**
         * Find a location by its slug
         *
         * @access public
         * @since 3.2.1
         * @param type $slug
         * @return array
         */
        public function findBySlug($slug)
        {
            $this->dao->select('*');
            $this->dao->from($this->getTableName());
            $this->dao->where('s_slug', $slug);
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }
            return $result->row();
        }

        /**
         * Find a locations with no slug
         *
         * @access public
         * @since 3.2.1
         * @return array
         */
        public function listByEmptySlug()
        {
            $this->dao->select('*');
            $this->dao->from($this->getTableName());
            $this->dao->where('s_slug', '');
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }
            return $result->result();
        }


    }

    /* file end: ./oc-includes/osclass/model/Region.php */
?>