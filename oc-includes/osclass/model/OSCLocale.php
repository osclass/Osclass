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

    class OSCLocale extends DAO {

        private static $instance ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName() { return DB_TABLE_PREFIX . 't_locale' ; }

        public function getPrimaryKey() { return 'pk_c_code' ; }

        public function listAllEnabled($is_bo = false, $indexed_by_pk = false) {
            if($is_bo) {
                $aResults = $this->listWhere('b_enabled_bo ORDER BY `s_name` ASC') ;
            } else {
                $aResults = $this->listWhere('b_enabled ORDER BY `s_name` ASC') ;
            }

            if ($indexed_by_pk) {
                $aTmp = array() ;
                for ($i = 0 ; $i < count($aResults) ; $i++) {
                    $aTmp[(string)$aResults[$i][$this->getPrimaryKey()]] = $aResults[$i] ;
                }
                $aResults = $aTmp ;
            }

            return($aResults) ;
        }

        public function deleteLocale($locale) {
            osc_run_hook('delete_locale', $locale);
            $this->conn->osc_dbExec("DELETE FROM %st_category_description WHERE fk_c_locale_code = '" . $locale . "'", DB_TABLE_PREFIX);
            $this->conn->osc_dbExec("DELETE FROM %st_locale WHERE pk_c_code = '" . $locale . "'", DB_TABLE_PREFIX);
        }

    }

?>