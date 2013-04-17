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