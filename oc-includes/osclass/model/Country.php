<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.') ;

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
     * Model database for Country table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class Country extends DAO
    {
        /**
         *
         * @var Country
         */
        private static $instance ;

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
            parent::__construct();
            $this->setTableName('t_country') ;
            $this->setPrimaryKey('pk_c_code') ;
            $this->setFields( array('pk_c_code', 's_name') ) ;
        }

        /**
         * Find a country by its ISO code
         *
         * @access public
         * @since unknown
         * @param type $code
         * @return array
         */
        public function findByCode($code)
        {
            $this->dao->select('*') ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('pk_c_code', $code) ;
            $result = $this->dao->get() ;

            return $result->row();
        }

        /**
         * Find a country by its name
         *
         * @access public
         * @since unknown
         * @param type $name
         * @return array
         */
        public function findByName($name)
        {
            $this->dao->select('*') ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('s_name', $name) ;
            $result = $this->dao->get() ;
            return $result->row();
        }

        /**
         * List all the countries
         *
         * @access public
         * @since unknown
         * @param type $language
         * @return array
         */
        public function listAll() {
            $result = $this->dao->query(sprintf('SELECT * FROM %s ORDER BY s_name ASC', $this->getTableName()));
            return $result->result();
        }

        /**
         * Function that work with the ajax file
         *
         * @access public
         * @since unknown
         * @param type $query
         * @return array
         */
        public function ajax($query)
        {
            $this->dao->select('pk_c_code as id, s_name as label, s_name as value') ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->like('s_name', $query, 'after') ;
            $this->dao->limit(5);
            $result = $this->dao->get() ;
            return $result->result();
        }


        /**
         *  Delete a country with its regions, cities,..
         *
         *  @access public
         *  @since 2.4
         *  @param $pk
         *  @return int number of failed deletions or 0 in case of none
         */
        function deleteByPrimaryKey($pk) {
            $mRegions = Region::NewInstance();
            $aRegions = $mRegions->findByCountry($pk);
            $result = 0;
            foreach($aRegions as $region) {
                $result += $mRegions->deleteByPrimaryKey($region['pk_i_id']);
            }
            Item::newInstance()->deleteByCountry($pk);
            CountryStats::newInstance()->delete(array('fk_c_country_code' => $pk));
            User::newInstance()->update(array('fk_c_country_code' => null, 's_country' => ''), array('fk_c_country_code' => $pk));
            if(!$this->delete(array('pk_c_code' => $pk))) {
                $result++;
            }
            return $result;
        }
/*

                $aCities = $mCities->findByRegion($region['pk_i_id']);
                foreach($aCities as $city) {
                    $aCityAreas = $mCityAreas->findByCity($city['pk_i_id']);
                    foreach($aCityAreas as $cityArea) {
                        $userMgr->update(array('fk_i_city_area_id' => null, 's_city_area' => ''), array('fk_i_city_id' => $cityArea['pk_i_id']));
                        if(!$mCityAreas->delete(array('pk_i_id' => $cityArea['pk_i_id']))) {
                            $result = false;
                        };
                    }
                    $userMgr->update(array('fk_i_city_id' => null, 's_city' => ''), array('fk_i_city_id' => $city['pk_i_id']));
                    if(!$mCities->delete(array('pk_i_id' => $city['pk_i_id']))) {
                        $result = false;
                    };
                }
                $userMgr->update(array('fk_i_region_id' => null, 's_region' => ''), array('fk_i_region_id' => $region['pk_i_id']));
                if(!$mRegions->delete(array('pk_i_id' => $region['pk_i_id']))) {
                    $result = false;
                };
            }
            return $result;
        }

        Item::newInstance()->deleteByCountry($countryId);
        $mRegions = new Region();
        $mCities = new City();

        $aCountries = $mCountries->findByCode($countryId);
        if($aCountries) {
            $aRegions = $mRegions->findByCountry($aCountries['pk_c_code']);
            foreach($aRegions as $region) {
                // remove city_stats
                CityStats::newInstance()->deleteByRegion($region['pk_i_id']);
                // remove region_stats
                RegionStats::newInstance()->delete( array('fk_i_region_id' => $region['pk_i_id']) );
            }
            //remove country stats
            CountryStats::newInstance()->delete( array('fk_c_country_code' => $aCountries['pk_c_code'] ) );


       */


    }

    /* file end: ./oc-includes/osclass/model/Country.php */
?>