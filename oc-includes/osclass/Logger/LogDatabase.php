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
    class LogDatabase
    {
        /**
         *
         * @var type 
         */
        private static $_instance;
        /**
         *
         * @var type 
         */
        var $messages ;

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
        public function _construct()
        {
            $this->messages = array() ;
        }

        /**
         *
         * @param type $sql
         * @param type $time
         * @param type $error_level
         * @param type $error_description 
         */
        public function add_message($sql, $time, $error_level, $error_description)
        {
            $this->messages[] = array(
                'query'      => $sql,
                'query_time' => $time,
                'errno'      => $error_level,
                'error'      => $error_description
            ) ;
        }

        /**
         * 
         */
        public function print_messages()
        {
            echo '<pre>' ;
            print_r($this->messages) ;
            echo '</pre>' ;
        }
    }

    /* file end: ./oc-includes/osclass/classes/Logger/LogDatabase.php */
?>