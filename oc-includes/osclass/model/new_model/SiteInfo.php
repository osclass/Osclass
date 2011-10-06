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
    class SiteInfo extends DAO
    {
        /**
         *
         * @var type 
         */
        private static $instance ;
        /**
         *
         * @var type 
         */
        private $dao_metadata ;
        /**
         *
         * @var type 
         */
        private $site_info ;

        /**
         *
         * @return type 
         */
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
        public function __construct()
        {
            parent::__construct() ;

            $this->set_table_name('tbl_sites') ;
            $this->set_primary_key('s_site') ; 
            $this->set_fields( array('s_site', 'dt_date', 'fk_i_user_id', 's_db_name', 's_db_host', 's_db_user', 's_db_password') ) ;

            $conn = DBConnectionClass::newInstance() ;
            $this->dao_metadata = new DBCommandClass($conn->get_metadata_db()) ;

            $this->to_array() ;
        }

        /**
         * 
         */
        public function to_array()
        {
            $domain = $_SERVER['HTTP_HOST'] ;
            $this->site_info = $this->find_by_primary_key($domain) ;
        }

        /**
         *
         * @param type $key
         * @return type 
         */
        public function get($key)
        {
            if (!isset($this->site_info[$key])) {
                return '' ;
            }

            return ($this->site_info[$key]) ;
        }

        /**
         *
         * @param type $value
         * @return type 
         */
        public function find_by_primary_key($value)
        {
            $this->dao_metadata->select($this->get_fields()) ;
            $this->dao_metadata->from($this->get_table_name()) ;
            $this->dao_metadata->like('s_site', $value, 'both') ;
            $result = $this->dao_metadata->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->row() ;
        }

        /**
         * 
         * @param type $table
         * @return type 
         */
        public function set_table_name($table)
        {
            return $this->table_name = $table ;
        }

    }

    /* file end: ./oc-includes/osclass/model/new_model/SiteInfo.php */
?>