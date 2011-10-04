<?php

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

    /**
     * 
     */
    class Country extends DAO
    {
        /**
         *
         * @var type 
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
            $this->set_table_name('t_country') ;
        }

        /**
         *
         * @param type $code
         * @return array
         */
        public function findByCode($code) {
            $this->dao->select('*') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('pk_c_code', $code) ;
            $result = $this->dao->get() ;
            return $result->result_array();
        }

        /**
         *
         * @param type $code
         * @return array
         */
        public function findByName($name) {
            $this->dao->select('*') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_name', addslashes($code)) ;
            $result = $this->dao->get() ;
            return $result->result_array();
        }

        /**
         *
         * @param type $language
         * @return array
         */
        public function listAll($language) {
            if($language=='') { $language = osc_current_user_locale(); } else { $language = 'en_US'; }
            $this->dao->query(sprintf('SELECT * FROM (SELECT *, FIELD(fk_c_locale_code, \'en_US\', \'%s\', \'%s\') as sorter FROM %st_country WHERE s_name != \'\' ORDER BY sorter DESC) dummytable GROUP BY pk_c_code ORDER BY s_name ASC',osc_current_user_locale(), $language, DB_TABLE_PREFIX));
            $result = $this->dao->get() ;
            return $result->result_array();
        }

        /**
         *
         * @param type $language
         * @return array
         */
        public function listAllAdmin($language = "") {
            if($language=='') { $language = osc_current_user_locale(); } else { $language = 'en_US'; }
            $this->dao->query(sprintf('SELECT * FROM (SELECT *, FIELD(fk_c_locale_code, \'en_US\', \'%s\', \'%s\') as sorter FROM %st_country WHERE s_name != \'\' ORDER BY sorter DESC) dummytable GROUP BY pk_c_code ORDER BY s_name ASC',osc_current_user_locale(), $language, DB_TABLE_PREFIX));
            $result = $this->dao->get() ;
            $countries_temp = $result->result_array();
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

        /**
         *
         * @param type $language
         * @return array
         */
        /*public function updateLocale($code, $locale, $name) {
            $country = $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_c_code = '%s' AND fk_c_locale_code = '%s'", $this->getTableName(), addslashes($code), addslashes($locale));
            if($country) {
                return $this->update(array('s_name' => $name), array('pk_c_code' => $code, 'fk_c_locale_code' => $locale));
            } else {
                return $this->conn->osc_dbExec("INSERT INTO %s (pk_c_code, fk_c_locale_code, s_name) VALUES ('%s', '%s', '%s')", $this->getTableName(), addslashes($code), addslashes($locale), addslashes($name) );
            }
        }*/

        
        /**
         *
         * @param type $language
         * @return array
         */
        public function ajax($query) {
            $this->dao->select('pk_c_code as id, s_name as label, s_name as value') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_name', addslashes($query)."%%") ;
            $this->dao->limit(5);
            $result = $this->dao->get() ;
            return $result->result_array();
        }
        
        
    }

    /* file end: ./oc-includes/osclass/model/new_model/Preference.php */
?>