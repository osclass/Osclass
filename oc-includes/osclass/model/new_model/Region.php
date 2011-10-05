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
    class Region extends DAO
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
            $this->set_table_name('t_region') ;
            $this->set_primary_key('pk_i_id') ;
            $this->set_fields( array('pk_i_id', 'fk_c_country_code', 's_name', 'b_active') ) ;
        }

        /**
         *
         * @param type $country_id
         * @return array
         */
        public function getByCountry($country_id) {
            $this->dao->select('*') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('fk_c_country_code', addslashes($country_id)) ;
            $this->dao->order_by('s_name', 'ASC');
            $result = $this->dao->get() ;
            return $result->result();
        }

        /**
         *
         * @param type $name
         * @return array
         */
        public function findByName($name) {
            $this->dao->select('*') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_name', addslashes($name)) ;
            $this->dao->limit(1);
            $result = $this->dao->get() ;
            return $result->row();
        }

        /**
         *
         * @param type $name
         * @param type $code
         * @return array
         */
        public function findByNameAndCode($name, $code) {
            $this->dao->select('*') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_name', addslashes($name)) ;
            $this->dao->where('fk_c_country_code', addslashes($code)) ;
            $this->dao->limit(1);
            $result = $this->dao->get() ;
            return $result->row();
        }

        /**
         *
         * @param type $name
         * @param type $country
         * @return array
         */
        public function findByNameOnCountry($name, $country = null) {
            $this->dao->select('*') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_name', addslashes($name)) ;
            if($country!=null) {
                $this->dao->where('fk_c_country_code', addslashes($code)) ;
            }
            $this->dao->limit(1);
            $result = $this->dao->get() ;
            return $result->row();
        }
        
        /**
         *
         * @param type $query
         * @return array
         */
        public function ajax($query, $country = null) {
            $this->dao->select('pk_i_id, s_name, s_name') ;
            $this->dao->from($this->table_name) ;
            $this->dao->like('s_name', $query, 'after') ;
            if($country!=null) {
                $this->dao->where('fk_c_country_code', strtolower($country)) ;
            }
            $this->dao->limit(5);
            $result = $this->dao->get() ;
            if($result) {
                return $result->result();
            } else {
                return array();
            }
        }
        
        
    }

?>