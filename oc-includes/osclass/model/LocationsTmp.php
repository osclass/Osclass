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
     * Model database for LocationsTmp table
     *
     * @package Osclass
     * @subpackage Model
     * @since 2.4
     */
    class LocationsTmp extends DAO
    {
        /**
         * It references to self object: LocationsTmp.
         * It is used as a singleton
         *
         * @access private
         * @since 2.4
         * @var CountryStats
         */
        private static $instance;

        /**
        * It creates a new LocationsTmp object class if it has been created
        * before, it return the previous object
        *
        * @access public
        * @since 2.4
        * @return LocationsTmp
        */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_locations_tmp table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_locations_tmp');
            $this->setFields( array('id_location', 'e_type') );
        }

        function getLocations($max)
        {
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $this->dao->limit($max);
            $rs = $this->dao->get();

            if($rs === false) {
                return array();
            }
            return $rs->result();
        }

        function delete($where)
        {
            return $this->dao->delete($this->getTableName(), $where );
        }

        function batchInsert($ids, $type) {
            if(!empty($ids)) {
                return $this->dao->query(sprintf("INSERT INTO %s (id_location, e_type) VALUES (%s, '%s')", $this->getTableName(), implode(",'".$type."'),(", $ids), $type));
            }
            return false;
        }

    }