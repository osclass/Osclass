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

    class Country extends DAO {

        private static $instance ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName() { return DB_TABLE_PREFIX . 't_country'; }

        public function findByCode($code) {
            return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_c_code = '%s'", $this->getTableName(), $code);
        }

        public function findByName($name) {
            return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE s_name = '%s'", $this->getTableName(), $name);
        }

        public function listAll($language = "") {
            if($language=='') { $language = osc_current_user_locale(); } else { $language = "en_US"; };
            return $this->conn->osc_dbFetchResults('SELECT * FROM (SELECT *, FIELD(fk_c_locale_code, \'en_US\', \''.osc_current_user_locale().'\', \''.$language.'\') as sorter FROM %s WHERE s_name != \'\' ORDER BY sorter DESC) dummytable GROUP BY pk_c_code ORDER BY s_name ASC', $this->getTableName());
        }

        public function listAllAdmin($language = "") {
            if($language=='') { $language = osc_current_user_locale(); } else { $language = "en_US"; };
            $countries_temp = $this->conn->osc_dbFetchResults('SELECT * FROM (SELECT *, FIELD(fk_c_locale_code, \'en_US\', \''.osc_current_user_locale().'\', \''.$language.'\') as sorter FROM %s WHERE s_name != \'\' ORDER BY sorter DESC) dummytable GROUP BY pk_c_code ORDER BY s_name ASC', $this->getTableName());
            $countries = array();
            foreach($countries_temp as $country) {
                $locales = $this->conn->osc_dbFetchResults("SELECT * FROM %s WHERE pk_c_code = '%s'", $this->getTableName(), $country['pk_c_code']);
                foreach($locales as $locale) {
                    $country['locales'][$locale['fk_c_locale_code']] = $locale['s_name'];
                }
                $countries[] = $country;
            }
            return $countries;
        }
        
        public function updateLocale($code, $locale, $name) {
            $country = $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_c_code = '%s' AND fk_c_locale_code = '%s'", $this->getTableName(), $code, $locale);
            if($country) {
                return $this->update(array('s_name' => $name), array('pk_c_code' => $code, 'fk_c_locale_code' => $locale));
            } else {
                return $this->conn->osc_dbExec("INSERT INTO %s (pk_c_code, fk_c_locale_code, s_name) VALUES ('%s', '%s', '%s')", $this->getTableName(), $code, $locale, $name );
            }
        }
    }

?>