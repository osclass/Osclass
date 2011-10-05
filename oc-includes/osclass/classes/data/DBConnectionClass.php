<?php

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * 
     */
    class DBConnectionClass
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
        public $db_host ;
        /**
         *
         * @var type 
         */
        public $db_name ;
        /**
         *
         * @var type 
         */
        public $db_user ;
        /**
         *
         * @var type 
         */
        public $db_password ;
        /**
         *
         * @var type 
         */
        public $db_debug_level ;

        // Data related variables
        /**
         *
         * @var type 
         */
        public $db               = 0 ;
        /**
         *
         * @var type 
         */
        public $metadata_db      = 0 ;
        /**
         *
         * @var type 
         */
        public $error_level      = 0 ;
        /**
         *
         * @var type 
         */
        public $error_desc       = "" ;
        /**
         *
         * @var type 
         */
        public $conn_error_level = 0 ;
        /**
         *
         * @var type 
         */
        public $conn_error_desc  = 0 ;

        /**
         *
         * @return type 
         */
        public static function newInstance($server = '', $user = '', $password = '', $database = '', $debug_level = '')
        {
            $server      = ($server == '') ? osc_db_host() : $server ;
            $user        = ($user == '') ? osc_db_user() : $user ;
            $password    = ($password == '') ? osc_db_password() : $password ;
            $database    = ($database == '') ? osc_db_name() : $database ;
            $debug_level = ($debug_level == '') ? DEBUG_LEVEL : $debug_level ;

            if(!self::$instance instanceof self) {
                self::$instance = new self ($server, $user, $password, $database, $debug_level);
            }
            return self::$instance ;
        }

        /**
         * Initializate database connection
         * 
         * @param string $server Host name where it's located the mysql server
         * @param string $database Default database to be used when performing queries
         * @param string $user MySQL user name
         * @param string $password MySQL password
         * @param int $debug_level Debug level
         */
        public function __construct($server, $user, $password, $database, $debug_level)
        {
            $this->db_host        = $server ;
            $this->db_name        = $database ;
            $this->db_user        = $user ;
            $this->db_password    = $password ;
            $this->db_debug_level = $debug_level ;

            $this->connect_to_osclass_db() ;
        }

		/**
         * Connection destructor
         */
        public function destroy()
        {
            $this->release_osclass_db() ;
            $this->release_metadata_db() ;
        }

		/**
         * Set error num error and error description
         */
		function error_report()
		{
			$this->error_level = $this->db->errno ;
			$this->error_desc  = $this->db->error ;
		}

        /**
         * 
         */
        function error_connection()
        {
            $this->conn_error_level = $this->db->connect_errno ;
            $this->conn_error_desc  = $this->db->connect_error ;
        }

        /**
         *
         * @return type 
         */
        function connect_to_osclass_db()
        {
            $conn = $this->_connect_to_db($this->db_host, $this->db_user, $this->db_password, $this->db) ;

            if ( $conn == false ) {
                $this->error_connection() ;
                $this->release_osclass_db() ;
                return false ;
            }

            $this->_set_charset('utf8', $this->db) ;

            // Try to select a database...
            $select_db = $this->select_osclass_db() ;
            if ( $select_db == false) {
                // Select error
                $this->error_report() ;
                $this->release_osclass_db() ;
                return false ;
            }

            // succesfull connection
            return true ;
		}

        /**
         *
         * @return type 
         */
        function connect_to_metadata_db()
        {
            $conn = $this->_connect_to_db(DB_HOST, DB_USER, DB_PASSWORD, $this->metadata_db) ;

			if ( $conn == false ) {
                $this->release_metadata_db() ;
				return false ;
			}

            $this->_set_charset('utf8', $this->metadata_db) ;

            // Try to select a database...
            $select_db = $this->select_metadata_db() ;
            if ( $select_db == false) {
                // Select error
                $this->release_metadata_db() ;
                return false ;
            }

            // succesfull connection
            return true ;
        }

        /**
         *
         * @return type 
         */
        function select_osclass_db()
        {
            return $this->_select_db($this->db_name, $this->db) ;
        }

        /**
         *
         * @return type 
         */
        function select_metadata_db()
        {
            return $this->_select_db(DB_NAME, $this->metadata_db) ;
        }

        /**
         * 
         */
        function reconnect_osclass_db()
        {
            $this->release_osclass_db() ;
			$this->connect_to_osclass_db() ;
        }

        /**
         * 
         */
        function reconnect_metadata_db()
        {
            $this->release_metadata_db() ;
			$this->connect_to_metadata_db() ;
        }

        /**
         *
         * @return type 
         */
        function release_osclass_db()
        {
            $release = $this->_release_db($this->db) ;

            if( !$release ) {
                $this->error_report() ;
            }

            return $release ;
        }

        /**
         *
         * @return type 
         */
        function release_metadata_db()
        {
            return $this->_release_db($this->metadata_db) ;
        }

        /**
         * 
         */
        function get_osclass_db()
        {
            return $this->_get_db($this->db) ;
        }

        /**
         * 
         */
        function get_metadata_db()
        {
            return $this->_get_db($this->metadata_db) ;
        }

        /**
         *
         * @param type $host
         * @param type $user
         * @param type $password
         * @param mysqli $conn_id
         * @return type 
         */
        function _connect_to_db($host, $user, $password, &$conn_id)
        {
            // Try to connect to database server...
            $conn_id = new mysqli($host, $user, $password) ;

            if ( $conn_id == false ) {
                return false ;
            }

            // succesfull connection
            return true ;
        }

        /**
         *
         * @param string $dbname Database name. If you leave blank this field, it will
         * select the database set in the init method.
         * @return type 
         */
        function _select_db($dbname, &$conn_id)
        {
            if ( !$conn_id ) {
                // No database connection...
                return false ;
            }

            if ( !$conn_id->select_db($dbname) ) {
                // Failed to select the database... abort connection process
                return false ;
            }

            // Database selected!
            return true ;
        }

        /**
         *
         * @param type $charset
         * @param type $conn_id 
         */
        function _set_charset($charset, &$conn_id)
        {
            $conn_id->set_charset($charset) ;
        }

        /**
         *
         * @return type 
         */
        function _release_db(&$conn_id)
        {
            if( !$conn_id ) {
                return true ;
            }

            // close database
            if( !$conn_id->close() ) {
                // error closing database
                return false; 
            }

            // connection to database closed successfully
            return true ;
        }

        /**
         *
         * @param type $conn_id
         * @return type 
         */
        function _get_db(&$conn_id)
        {
            if( $conn_id != false ) {
                return $conn_id ;
            }

            return false ;
        }
	}

    /* file end: ./oc-includes/osclass/classes/data/DBConnectionClass.php */
?>