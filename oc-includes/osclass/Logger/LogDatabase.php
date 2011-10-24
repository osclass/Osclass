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
            echo '<fieldset style="border:1px solid black; padding:6px 10px 10px 10px; margin: 20px;" >' . PHP_EOL ;
            echo '<legend style="font-size: 16px;">&nbsp;&nbsp;Database queries (Total queries: ' . $this->getTotalNumberQueries() .' - Total queries time: ' . $this->getTotalQueriesTime() . ')&nbsp;&nbsp;</legend>' . PHP_EOL ;
            echo '<table style="border-collapse: separate; *border-collapse: collapse; width: 100%; font-size: 13px; padding: 15px;">' . PHP_EOL ;
            if( count($this->messages) == 0 ) {
                echo '<tr><td>No queries</td></tr>' . PHP_EOL ;
            } else {
                foreach($this->messages as $msg) {
                    $row_style = '';
                    if( $msg['errno'] != 0 ) {
                        $row_style = 'style=" background-color: #FFC2C2;"' ;
                    }
                    echo '<tr ' . $row_style . '>' . PHP_EOL ;
                    echo '<td style="padding: 10px 10px 9px; text-align: left; vertical-align: top; border: 1px solid #ddd;">' . $msg['query_time'] . '</td>' . PHP_EOL ;
                    echo '<td style="padding: 10px 10px 9px; text-align: left; vertical-align: top; border: 1px solid #ddd;">' ;
                    if( $msg['errno'] == 0 ) {
                        echo $msg['query'] ;
                    } else {
                        echo '<strong>Error number:</strong> ' . $msg['errno'] . '<br/>' ;
                        echo '<strong>Error description:</strong> ' . $msg['error'] . '<br/><br/>' ;
                        echo $msg['query'] ;
                    }
                    echo '</td>' . PHP_EOL ;
                    echo '</tr>' . PHP_EOL ;
                }
            }
            echo '</table>' . PHP_EOL ;
            echo '</fieldset>' . PHP_EOL ;
        }

        public function getTotalQueriesTime()
        {
            $time = 0 ;
            foreach($this->messages as $m) {
                $time = $time + $m['query_time'] ;
            }

            return $time ;
        }

        public function getTotalNumberQueries()
        {
            return count($this->messages) ;
        }
    }

    /* file end: ./oc-includes/osclass/classes/Logger/LogDatabase.php */
?>