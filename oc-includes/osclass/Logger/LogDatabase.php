<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
        var $messages;
        /**
         *
         * @var type
         */
        var $explain_messages;

        /**
         *
         * @return type
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         *
         */
        public function _construct()
        {
            $this->messages         = array();
            $this->explain_messages = array();
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
            );
        }

        /**
         *
         * @param type $sql
         * @param type $time
         * @param type $errorLevel
         * @param type $errorDescription
         */
        public function addExplainMessage($sql, $results)
        {
            $this->explain_messages[] = array(
                'query'   => $sql,
                'explain' => $results
            );
        }

        /**
         *
         */
        public function printMessages()
        {
            echo '<fieldset style="border:1px solid black; padding:6px 10px 10px 10px; margin: 20px; width: 95%; background-color: #FFFFFF;" >' . PHP_EOL;
            echo '<legend style="font-size: 16px;">&nbsp;&nbsp;Database queries (Total queries: ' . $this->getTotalNumberQueries() .' - Total queries time: ' . $this->getTotalQueriesTime() . ')&nbsp;&nbsp;</legend>' . PHP_EOL;
            echo '<table style="border-collapse: separate; *border-collapse: collapse; width: 100%; font-size: 13px; padding: 15px; border-spacing: 0;">' . PHP_EOL;
            if( count($this->messages) == 0 ) {
                echo '<tr><td>No queries</td></tr>' . PHP_EOL;
            } else {
                foreach($this->messages as $msg) {
                    $row_style = '';
                    if( $msg['errno'] != 0 ) {
                        $row_style = 'style="background-color: #FFC2C2;"';
                    }
                    echo '<tr ' . $row_style . '>' . PHP_EOL;
                    echo '<td style="padding: 10px 10px 9px; text-align: left; vertical-align: top; border: 1px solid #ddd;">' . $msg['query_time'] . '</td>' . PHP_EOL;
                    echo '<td style="padding: 10px 10px 9px; text-align: left; vertical-align: top; border: 1px solid #ddd;">';
                    if( $msg['errno'] != 0 ) {
                        echo '<strong>Error number:</strong> ' . $msg['errno'] . '<br/>';
                        echo '<strong>Error description:</strong> ' . $msg['error'] . '<br/><br/>';
                    }
                    echo nl2br($msg['query']);
                    echo '</td>' . PHP_EOL;
                    echo '</tr>' . PHP_EOL;
                }
            }
            echo '</table>' . PHP_EOL;
            echo '</fieldset>' . PHP_EOL;
        }

        function writeMessages()
        {
            $filename = CONTENT_PATH . 'queries.log';

            if( !file_exists($filename) || !is_writable($filename) ) {
                return false;
            }

            $fp = fopen($filename, 'a');

            if( $fp == false ) {
                return false;
            }

            fwrite($fp, '==================================================' . PHP_EOL);
            if(MULTISITE) {
                fwrite($fp, '=' . str_pad('Date: ' . date('Y-m-d').' '.date('H:i:s'), 48, " ", STR_PAD_BOTH) . '=' . PHP_EOL);
            } else {
                fwrite($fp, '=' . str_pad('Date: ' . date(osc_date_format()!=''?osc_date_format():'Y-m-d').' '.date(osc_time_format()!=''?osc_date_format():'H:i:s'), 48, " ", STR_PAD_BOTH) . '=' . PHP_EOL);
            }
            fwrite($fp, '=' . str_pad('Total queries: ' . $this->getTotalNumberQueries(), 48, " ", STR_PAD_BOTH) . '=' . PHP_EOL);
            fwrite($fp, '=' . str_pad('Total queries time: ' . $this->getTotalQueriesTime(), 48, " ", STR_PAD_BOTH) . '='  . PHP_EOL);
            fwrite($fp, '==================================================' . PHP_EOL . PHP_EOL);

            foreach($this->messages as $msg) {
                fwrite($fp, 'QUERY TIME' . ' ' . $msg['query_time'] . PHP_EOL);
                if( $msg['errno'] != 0 ) {
                    fwrite($fp, 'Error number: ' . $msg['errno'] . PHP_EOL);
                    fwrite($fp, 'Error description: ' . $msg['error'] . PHP_EOL);
                }
                fwrite($fp, '**************************************************' . PHP_EOL);
                fwrite($fp, $msg['query'] . PHP_EOL);
                fwrite($fp, '--------------------------------------------------' . PHP_EOL);
            }

            fwrite($fp, PHP_EOL . PHP_EOL. PHP_EOL);
            fclose($fp);
            return true;
        }

        function writeExplainMessages()
        {
            $filename = CONTENT_PATH . 'explain_queries.log';

            if( !file_exists($filename) || !is_writable($filename) ) {
                return false;
            }

            $fp = fopen($filename, 'a');

            if( $fp == false ) {
                return false;
            }

            fwrite($fp, '==================================================' . PHP_EOL);
            if(MULTISITE) {
                fwrite($fp, '=' . str_pad('Date: ' . date('Y-m-d').' '.date('H:i:s'), 48, " ", STR_PAD_BOTH) . '=' . PHP_EOL);
            } else {
                fwrite($fp, '=' . str_pad('Date: ' . date(osc_date_format()?osc_date_format():'Y-m-d').' '.date(osc_time_format()?osc_time_format():'H:i:s'), 48, " ", STR_PAD_BOTH) . '=' . PHP_EOL);
            }
            fwrite($fp, '==================================================' . PHP_EOL . PHP_EOL);

            $title  = '|' . str_pad('id', 3, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('select_type', 20, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('table', 20, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('type', 8, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('possible_keys', 28, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('key', 18, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('key_len', 9, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('ref', 48, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('rows', 8, " ", STR_PAD_BOTH) . '|';
            $title .= str_pad('Extra', 38, " ", STR_PAD_BOTH) . '|';

            for($i = 0; $i < count($this->explain_messages); $i++) {
                fwrite($fp, $this->explain_messages[$i]['query'] . PHP_EOL);
                fwrite($fp, str_pad('', 211, "-", STR_PAD_BOTH) . PHP_EOL);
                fwrite($fp, $title . PHP_EOL);
                fwrite($fp, str_pad('', 211, "-", STR_PAD_BOTH) . PHP_EOL);
                foreach($this->explain_messages[$i]['explain'] as $explain) {
                    $row  = '|' . str_pad($explain['id'], 3, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['select_type'], 20, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['table'], 20, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['type'], 8, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['possible_keys'], 28, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['key'], 18, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['key_len'], 9, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['ref'], 48, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['rows'], 8, " ", STR_PAD_BOTH) . '|';
                    $row .= str_pad($explain['Extra'], 38, " ", STR_PAD_BOTH) . '|';
                    fwrite($fp, $row . PHP_EOL);
                    fwrite($fp, str_pad('', 211, "-", STR_PAD_BOTH) . PHP_EOL);
                }
                if( $i != ( count($this->explain_messages) - 1 ) ) {
                    fwrite($fp, PHP_EOL . PHP_EOL);
                }
            }

            fwrite($fp, PHP_EOL . PHP_EOL);
            fclose($fp);
            return true;
        }

        public function getTotalQueriesTime()
        {
            $time = 0;
            foreach($this->messages as $m) {
                $time = $time + $m['query_time'];
            }

            return $time;
        }

        public function getTotalNumberQueries()
        {
            return count($this->messages);
        }
    }

    /* file end: ./oc-includes/osclass/Logger/LogDatabase.php */
?>
