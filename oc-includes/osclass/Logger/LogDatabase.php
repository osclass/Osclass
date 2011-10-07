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
        private static $instance;
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
         * @param type $errorLevel
         * @param type $errorDescription 
         */
        public function addMessage($sql, $time, $errorLevel, $errorDescription)
        {
            $this->messages[] = array(
                'query'      => $sql,
                'query_time' => $time,
                'errno'      => $errorLevel,
                'error'      => $errorDescription
            ) ;
        }

        /**
         * 
         */
        public function printMessages()
        {
            print_r($this->messages) ;
        }

        public function totalTime()
        {
            $time = 0 ;
            foreach($this->messages as $m) {
                $time = $time + $m['query_time'] ;
            }

            return $time ;
        }
    }

    /* file end: ./oc-includes/osclass/classes/Logger/LogDatabase.php */
?>