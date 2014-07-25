<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
     * Model database for CityArea table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class CityArea extends DAO
    {
        /**
         * It references to self object: CityArea.
         * It is used as a singleton
         *
         * @access private
         * @since unknown
         * @var CityArea
         */
        private static $instance;

        /**
         * It creates a new CityArea object class ir if it has been created
         * before, it return the previous object
         *
         * @access public
         * @since unknown
         * @return CityArea
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_city_area table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_city_area');
            $this->setPrimaryKey('pk_i_id');
            $this->setFields( array('pk_i_id', 'fk_i_city_id', 's_name') );
        }

        /**
         * Get the cityArea by its name and city
         *
         * @access public
         * @since unknown
         * @param string $query
         * @param int $cityId
         * @return array
         */
        function findByName($cityAreaName, $cityId = null)
        {
            $this->dao->select($this->getFields());
            $this->dao->from($this->getTableName());
            $this->dao->where('s_name', $cityAreaName);
            $this->dao->limit(1);
            if( $cityId != null ) {
                $this->dao->where('fk_i_city_id', $cityId);
            }

            $result = $this->dao->get();

            if( $result == false ) {
                return array();
            }

            return $result->row();
        }

        /**
         * Return city areas of a given city ID
         *
         * @access public
         * @since 2.4
         * @param $cityId
         * @return array
         */
        function findByCity($cityId) {
            $this->dao->select($this->getFields());
            $this->dao->from($this->getTableName());
            $this->dao->where('fk_i_city_id', $cityId);

            $result = $this->dao->get();

            if( $result == false ) {
                return array();
            }

            return $result->result();
        }

        /**
         *  Delete a city area
         *
         *  @access public
         *  @since 3.1
         *  @param $pk
         *  @return int number of failed deletions or 0 in case of none
         */
        function deleteByPrimaryKey($pk) {
            Item::newInstance()->deleteByCityArea($pk);
            User::newInstance()->update(array('fk_i_city_area_id' => null, 's_city_area' => ''), array('fk_i_city_area_id' => $pk));
            if(!$this->delete(array('pk_i_id' => $pk))) {
                return 1;
            }
            return 0;
        }


    }

    /* file end: ./oc-includes/osclass/model/CityArea.php */
?>