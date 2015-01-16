<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class DB
    {
        private $db = null;
        private $db_errno = 0;
        private $db_error = 0;
        private $dbHost = null;
        private $dbUser = null;
        private $dbPassword = null;
        private $dbName = null;
        private $msg = "";

        function __construct($dbHost, $dbUser, $dbPassword, $dbName) {
            $this->dbHost = $dbHost;
            $this->dbUser = $dbUser;
            $this->dbPassword = $dbPassword;
            $this->dbName = $dbName;
            $this->db_errno = 0;

            $this->osc_dbConnect();
        }

        function __destruct() {
            $this->osc_dbClose();
        }

        function debug($msg, $ok = true)
        {
            if( OSC_DEBUG_DB ) {
                $this->msg .= date("d/m/Y - H:i:s") . " ";

                if( $ok ) {
                    $this->msg .= "<span style='background-color: #D0F5A9;' >[ OPERATION OK ] ";
                } else {
                    $this->msg .= "<span style='background-color: #F5A9A9;' >[ OPERATION FAILED ] ";
                }

                $this->msg .= str_replace("\n", " ", $msg);
                $this->msg .= '</span><br />';
                $this->msg .= "\n";
            }
        }

        function print_debug()
        {
            if( OSC_DEBUG_DB && !defined('IS_AJAX') ) {
                echo $this->msg;
            }
        }

        /**
         * Establish a connection to the MySQL database.
         *
         * @param string server ip or name
         * @param string database user
         * @param string database password
         * @param string datatabase name
         */
        function osc_dbConnect() {
            $this->db = @new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
            if ($this->db->connect_error) {
                if( !defined('OSC_INSTALLING') ) {
                    require_once LIB_PATH . 'osclass/helpers/hErrors.php';
                    $title   = 'Osclass &raquo; Error connecting to database';
                    $message = 'Cannot connect to database. Check your configuration in <code>config.php</code> file.';
                    osc_die($title, $message);
                }
                $this->debug('Error connecting to \'' . $this->dbName . '\' (' . $this->db->connect_errno . ': ' . $this->db->connect_error . ')', false);
            }

            $this->db_errno = $this->db->connect_errno;
            $this->debug('Connected to \'' . $this->dbName . '\': [DBHOST] = ' . $this->dbHost . ' | [DBUSER] = ' . $this->dbUser);
            $this->db->set_charset('utf8');
        }

        /**
         * Close the database connection.
         */
        function osc_dbClose() {
            if (!@$this->db->close()) {
                $this->debug('Error releasing the connection to \'' . $this->dbName . '\'', false);
            }

            $this->debug('Connection with \'' . $this->dbName . '\' released properly');
            $this->print_debug();
        }

        /**
         * Executes a SQL statement in the database.
         */
        function osc_dbExec()
        {
            $sql = null;
            $argv = func_get_args();
            switch(func_num_args()) {
                case 0: return; break;
                case 1: $sql = $argv[0]; break;
                default:
                    $format = array_shift($argv);
                    foreach($argv as &$arg)
                        $arg = $this->db->real_escape_string($arg);
                    unset($arg);

                    $sql = vsprintf($format, $argv);
                    break;
            }

            $result = $this->db->query($sql);
            if(!$result) {
                $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false);
            } else {
                $this->debug($sql);
            }

            $this->db_errno = $this->db->errno;
            return $result;
        }

        function osc_dbFetchValue() {
            $result = null;

            $sql = null;
            $argv = func_get_args();
            switch(func_num_args()) {
                case 0: return $results; break;
                case 1: $sql = $argv[0]; break;
                default:
                    $format = array_shift($argv);
                    $sql = vsprintf($format, $argv);
                    break;
            }

            if($qry = $this->db->query($sql)) {
                $this->debug($sql);
                $row = $qry->fetch_array();
                $result = $row[0];
                $qry->free();
            } else {
                $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false);
            }
            $this->db_errno = $this->db->errno;
            return $result;
        }

        /**
         * @return array with values resulting of execution of query passed by parameter.
         */
        function osc_dbFetchValues() {
            $results = array();

            $sql = null;
            $argv = func_get_args();
            switch(func_num_args()) {
                case 0: return $results; break;
                case 1: $sql = $argv[0]; break;
                default:
                    $format = array_shift($argv);
                    $sql = vsprintf($format, $argv);
                    break;
            }

            if($qry = $this->db->query($sql)) {
                $this->debug($sql);
                while($result = $qry->fetch_array())
                    $results[] = $result[0];
                $qry->free();
            } else {
                $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false);
            }
            $this->db_errno = $this->db->errno;
            return $results;
        }

        function osc_dbFetchResult() {
            $result = null;

            $sql = null;
            $argv = func_get_args();
            switch(func_num_args()) {
                case 0: return $results; break;
                case 1: $sql = $argv[0]; break;
                default:
                    $format = array_shift($argv);
                    $sql = vsprintf($format, $argv);
                    break;
            }

            $qry = $this->db->query($sql);
            if($qry) {
                $this->debug($sql);
                $result = $qry->fetch_assoc();
                $qry->free();
            } else {
                $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false);
            }
            $this->db_errno = $this->db->errno;
            return $result;
        }

        function osc_dbFetchResults() {
            $results = array();

            $sql = null;
            $argv = func_get_args();
            switch(func_num_args()) {
                case 0: return $results; break;
                case 1: $sql = $argv[0]; break;
                default:
                    $format = array_shift($argv);
                    $sql = vsprintf($format, $argv);
                    break;
            }

            if($qry = $this->db->query($sql)) {
                $this->debug($sql);
                while($result = $qry->fetch_assoc())
                    $results[] = $result;
                $qry->free();
            } else {
                $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false);
            }
            $this->db_errno = $this->db->errno;
            return $results;
        }

        /**
         * Import (executes) the SQL passed as parameter making some proper adaptations.
         */
        function osc_dbImportSQL($sql, $needle = '')
        {
            $sql = str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql);
            $sentences = explode( $needle . ';', $sql);
            // PREPARE THE QUERIES
            $var_l = count($sentences);
            $s_temp = '';
            for($var_k=0;$var_k<$var_l;$var_k++) {
                $s = $s_temp.$sentences[$var_k];
                if(!empty($s) && trim($s)!='') {
                    $s .= $needle;
                    $simple_comma = substr_count($s, "'");
                    $scaped_simple_comma = substr_count($s, "\'");
                    if(($simple_comma-$scaped_simple_comma)%2==0) {
                        $sentences[$var_k] = $s;
                        $s_temp = '';
                        //echo "[OK] ".$s." <br />";
                    } else {
                        unset($sentences[$var_k]);
                        $s_temp = $s.";";
                        //echo "[FAIL] ".$s." <br />";
                    }
                } else {
                    unset($sentences[$var_k]);
                }
            }

            foreach($sentences as $s) {
                $s = trim($s);
                if( !empty($s) ) {
                    $s = trim($s);// . $needle;
                    if( $this->db->query($s) ) {
                        $this->debug($s);
                    } else {
                        $this->debug($s . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false);
                    }
                }
            }
            $this->db_errno = $this->db->errno;

            if ($this->db_errno != 0) return false;
            return true;
        }

        function autocommit($b_value) {
            $this->db->autocommit($b_value);
        }

        function commit() {
            $this->db->commit();
        }

        function rollback() {
            $this->db->rollback();
        }

        function get_last_id() {
            return($this->db->insert_id);
        }

        function get_affected_rows() {
            return($this->db->affected_rows);
        }

        function get_errno() {
            return($this->db_errno);
        }

        /**
         * Given some queries, it will check against the installed database if the information is the same
         *
         * @param mixed array or string with the SQL queries.
         * @return BOOLEAN true on success, false on fail
         */
        function osc_updateDB($queries = '') {

            if(!is_array($queries)) {
                $queries = explode(";", $queries);
            }

            // Prepare and separate the queries
            $struct_queries = array();
            $data_queries = array();
            foreach($queries as $query) {
                if(preg_match('|CREATE DATABASE ([^ ]*)|', $query, $match)) {
                    array_unshift($struct_queries, $query);
                } else if(preg_match('|CREATE TABLE ([^ ]*)|', $query, $match)) {
                    $struct_queries[trim(strtolower($match[1]), '`')] = $query;
                } else if(preg_match('|INSERT INTO ([^ ]*)|', $query, $match)) {
                    $data_queries[] = $query;
                } else if(preg_match('|UPDATE ([^ ]*)|', $query, $match)) {
                    $data_queries[] = $query;
                }
            }

            // Get tables from DB (already installed)
            $tables = $this->osc_dbFetchResults('SHOW TABLES');
            foreach($tables as $v) {
                $table = current($v);
                if(array_key_exists(strtolower($table), $struct_queries)) {

                    // Get the fields from the query
                    if(preg_match('|\((.*)\)|ms', $struct_queries[strtolower($table)], $match)) {
                        $fields = explode("\n", trim($match[1]));

                        // Detect if it's a "normal field definition" or a index one
                        $normal_fields = $indexes = array();
                        foreach($fields as $field) {
                            if(preg_match('|([^ ]+)|', trim($field), $field_name)) {
                                switch (strtolower($field_name[1])) {
                                    case '':
                                    case 'on':
                                    case 'foreign':
                                    case 'primary':
                                    case 'index':
                                    case 'fulltext':
                                    case 'unique':
                                    case 'key':
                                        $indexes[] = trim($field, ", \n");
                                        break;
                                    default :

                                        $normal_fields[strtolower($field_name[1])] = trim($field, ", \n");
                                        break;
                                }
                            }
                        }

                        // Take fields from the DB (already installed)
                        $tbl_fields = $this->osc_dbFetchResults('DESCRIBE '.$table);
                        foreach($tbl_fields as $tbl_field) {
                            //Every field should we on the definition, so else SHOULD never happen, unless a very aggressive plugin modify our tables
                            if(array_key_exists(strtolower($tbl_field['Field']), $normal_fields)) {
                                // Take the type of the field
                                if(preg_match("|".$tbl_field['Field']." (ENUM\s*\(([^\)]*)\))|i", $normal_fields[strtolower($tbl_field['Field'])], $match) || preg_match("|".$tbl_field['Field']." ([^ ]*( unsigned)?)|i", $normal_fields[strtolower($tbl_field['Field'])], $match)) {
                                    $field_type = $match[1];
                                    // Are they the same?
                                    if(strtolower($field_type)!=strtolower($tbl_field['Type']) && str_replace(' ', '', strtolower($field_type))!=str_replace(' ', '', strtolower($tbl_field['Type']))) {
                                        $struct_queries[] = "ALTER TABLE ".$table." CHANGE COLUMN ".$tbl_field['Field']." ".$normal_fields[strtolower($tbl_field['Field'])];
                                    }
                                }
                                // Have we changed the default value?
                                if(preg_match("| DEFAULT '(.*)'|i", $normal_fields[strtolower($tbl_field['Field'])], $default_match)) {
                                    $struct_queries[] = "ALTER TABLE ".$table." ALTER COLUMN ".$tbl_field['Field']." SET DEFAULT ".$default_match[1];
                                }
                                // Remove it from the list, so it will not be added
                                unset($normal_fields[strtolower($tbl_field['Field'])]);
                            }
                        }
                        // For the rest of normal fields (they are not in the table) we add them.
                        foreach($normal_fields as $k => $v) {
                            $struct_queries[] = "ALTER TABLE ".$table." ADD COLUMN ".$v;
                        }

                        // Go for the index part
                        $tbl_indexes = $this->osc_dbFetchResults("SHOW INDEX FROM ".$table);
                        if($tbl_indexes) {
                            unset($indexes_array);
                            foreach($tbl_indexes as $tbl_index) {
                                $indexes_array[$tbl_index['Key_name']]['columns'][] = array('fieldname' => $tbl_index['Column_name'], 'subpart' => $tbl_index['Sub_part']);
                                $indexes_array[$tbl_index['Key_name']]['unique'] = ($tbl_index['Non_unique'] == 0)?true:false;
                            }
                            foreach($indexes_array as $k => $v) {
                                $string = '';
                                if ($k=='PRIMARY') {
                                    $string .= 'PRIMARY KEY ';
                                } else if($v['unique']) {
                                    $string .= 'UNIQUE KEY ';
                                } else {
                                    $string .= 'INDEX ';
                                }

                                $columns = '';
                                // For each column in the index
                                foreach ($v['columns'] as $column) {
                                    if ($columns != '') $columns .= ', ';
                                    // Add the field to the column list string
                                    $columns .= $column['fieldname'];
                                    if ($column['subpart'] != '') {
                                        $columns .= '('.$column['subpart'].')';
                                    }
                                }
                                // Add the column list to the index create string
                                $string .= '('.$columns.')';
                                $var_index = array_search($string, $indexes);
                                if (!($var_index===false)) {
                                    unset($indexes[$var_index]);
                                } else {
                                    $var_index = array_search(str_replace(', ', ',', $string), $indexes);
                                    if (!($var_index===false)) {
                                        unset($indexes[$var_index]);
                                    }
                                }
                            }
                        }
                        // For the rest of the indexes (they are in the new definition but not in the table installed
                        foreach($indexes as $index) {
                            if(strtolower(substr(trim($index),0,2))!='on') {// && strtolower(substr(trim($index),0,7))!='foreign') {
                                $struct_queries[] = "ALTER TABLE ".$table." ADD ".$index;
                            //} else {
                                //$struct_queries[] = "ALTER TABLE ".$table." ".$index;
                            }
                        }
                        // No need to create the table, so we delete it SQL
                        unset($struct_queries[strtolower($table)]);
                    }
                }
            }

            $queries = array_merge($struct_queries, $data_queries);
            $ok = true;
            $error_queries = array();
            foreach($queries as $query) {
                $res = $this->osc_dbExec($query);
                if(!$res) {
                    $ok = false;
                    $error_queries[] = $query;
                }
            }

            return array($ok, $queries, $error_queries);
        }

    }

    function getConnection($dbHost = null, $dbUser = null, $dbPassword = null, $dbName = null, $dbLogLevel = null)
    {
        static $instance;

        if(defined('DB_HOST') && $dbHost == null)                 $dbHost     = osc_db_host();
        if(defined('DB_USER') && $dbUser == null)                 $dbUser     = osc_db_user();
        if(defined('DB_PASSWORD') && $dbPassword == null)         $dbPassword = osc_db_password();
        if(defined('DB_NAME') && $dbName == null)                 $dbName     = osc_db_name();

        if(!isset($instance[$dbName . "_" . $dbHost])) {
            if(!isset($instance)) {
                $instance = array();
            }

            $instance[$dbName . "_" . $dbHost] = new DB($dbHost, $dbUser, $dbPassword, $dbName);
        }

        return ($instance[$dbName . "_" . $dbHost]);
    }

?>
