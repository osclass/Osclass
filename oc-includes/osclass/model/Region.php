<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class Region extends DAO {

        private static $instance ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName() { return DB_TABLE_PREFIX . 't_region'; }

            public function getByCountry($country_id) {
                return $this->conn->osc_dbFetchResults("SELECT * FROM %s WHERE fk_c_country_code = '%s' ORDER BY s_name ASC", $this->getTableName(), $country_id);
            }


        public function findByNameAndCode($name, $code) {
            return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE s_name = '%s' AND fk_c_country_code = '%s' LIMIT 1", $this->getTableName(), $name, $code);
        }

        public function findByName($name) {
            return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE s_name = '%s' LIMIT 1", $this->getTableName(), $name);
        }

        public function ajax($query, $country = null) {
            $country_sql = ($country!=null)?' AND fk_c_country_code LIKE \''.strtolower($country).'\' ':'';            
            return $this->conn->osc_dbFetchResults("SELECT pk_i_id as id, s_name as label, s_name as value FROM %s WHERE `s_name` LIKE '%s%%' %s LIMIT 5", $this->getTableName(), $query, $country_sql);
        }

        public function findByNameOnCountry($query, $country) {
            $country_sql = ($country!=null)?' AND fk_c_country_code LIKE \''.strtolower($country).'\' ':'';
            return $this->conn->osc_dbFetchResult("SELECT pk_i_id, s_name, s_name FROM %s WHERE `s_name` LIKE '%s' %s LIMIT 1", $this->getTableName(), $query, $country_sql);
        }
    }

?>