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
            $this->setTableName('t_country');
            $this->setPrimaryKey('pk_c_code');
            $this->setFields( array('pk_c_code', 's_name', 's_slug') );
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
            $this->dao->select('*');
            $this->dao->from($this->getTableName());
            $this->dao->where('pk_c_code', $code);
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }
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
            $this->dao->select('*');
            $this->dao->from($this->getTableName());
            $this->dao->where('s_name', $name);
            $result = $this->dao->get();
            if($result == false) {
                return array();
            }
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
            if($result == false) {
                return array();
            }
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
            $this->dao->select('pk_c_code as id, s_name as label, s_name as value');
            $this->dao->from($this->getTableName());
            $this->dao->like('s_name', $query, 'after');
            $this->dao->limit(5);
            $result = $this->dao->get();
            if($result == false) {
                return array();
            }
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

    /* file end: ./oc-includes/osclass/model/Country.php */
?>