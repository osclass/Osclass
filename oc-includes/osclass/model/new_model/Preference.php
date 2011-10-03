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
    class Preference extends DAO
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
            $this->set_table_name('t_preference') ;
            /* $this->set_primary_key($key) ; // no primary key in preference table */
            $this->set_fields( array('s_section', 's_name', 's_value', 'e_type') ) ;
        }

        /**
         *
         * @param type $name
         * @return type 
         */
        function findValueByName($name)
        {
            $this->dao->select('s_value') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_name', $name) ;
            $result = $this->dao->get() ;

            if( $result->num_rows == 0 ) {
                return false ;
            } else {
                $row = $result->row() ;
                return $row['s_value'] ;
            }
        }

    }

    /* file end: ./oc-includes/osclass/model/new_model/Preference.php */
?>