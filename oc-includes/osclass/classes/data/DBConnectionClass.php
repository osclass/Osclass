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
     * Database connection object
     * 
     * @package OSClass
     * @subpackage Database
     * @since 2.3
     */
    class DBConnectionClass
    {
        /**
         * DBConnectionClass should be instanced one, so it's DBConnectionClass object is set
         * 
         * @access private
         * @since 2.3
         * @var DBConnectionClass 
         */
        private static $instance ;

        /**
         * Host name or IP address where it is located the database
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        var $db_host ;
        /**
         * Database name where it's installed OSClass
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        var $db_name ;
        /**
         * Database user
         * 
         * @access private
         * @since 2.3
         * @var string
         */
        var $db_user ;
        /**
         * Database user password
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        var $db_password ;
        /**
         * Debug level:
         *  - 0: No debug
         *  - 1: Debug in html
         *  - 2: Debug with comments
         * 
         * @access private
         * @since 2.3
         * @var int 
         */
        var $db_debug_level ;

        /**
         * Database connection object to OSClass database
         * 
         * @access private
         * @since 2.3
         * @var mysqli 
         */
        var $db               = 0 ;
        /**
         * Database connection object to metadata database
         * 
         * @access private
         * @since 2.3
         * @var mysqli 
         */
        var $metadata_db      = 0 ;
        /**
         * Database error number
         * 
         * @access private
         * @since 2.3
         * @var int 
         */
        var $error_level      = 0 ;
        /**
         * Database error description
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        var $error_desc       = "" ;
        /**
         * Database connection error number 
         * 
         * @access private
         * @since 2.3
         * @var int 
         */
        var $conn_error_level = 0 ;
        /**
         * Database connection error description
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        var $conn_error_desc  = 0 ;

        /**
         * It creates a new DBConnection object class or if it has been created before, it 
         * returns the previous object
         * 
         * @access public
         * @return DBConnectionClass 
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
         * Connection destructor and print debug
         */
        public function __destruct()
        {
            $this->release_osclass_db() ;
            $this->release_metadata_db() ;
            $this->debug() ;
        }

		/**
         * Set error num error and error description
         * 
         * @access private
         * @since 2.3
         */
		function error_report()
		{
			$this->error_level = $this->db->errno ;
			$this->error_desc  = $this->db->error ;
		}

        /**
         * Set connection error num error and connection error description
         * 
         * @access private
         * @since 2.3
         */
        function error_connection()
        {
            $this->conn_error_level = $this->db->connect_errno ;
            $this->conn_error_desc  = $this->db->connect_error ;
        }

        /**
         * Connect to OSClass database
         * 
         * @access public
         * @since 2.3
         * @return boolean It returns true if the connection has been successful or false if not
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
         * Connect to metadata database
         * 
         * @access public
         * @since 2.3
         * @return boolean It returns true if the connection has been successful or false if not
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
         * Select OSClass database in $db var
         * 
         * @access private
         * @since 2.3
         * @return boolean It returns true if the database has been selected sucessfully or false if not
         */
        function select_osclass_db()
        {
            return $this->_select_db($this->db_name, $this->db) ;
        }

        /**
         * Select metadata database in $metadata_db var
         * 
         * @access private
         * @since 2.3
         * @return boolean It returns true if the database has been selected sucessfully or false if not
         */
        function select_metadata_db()
        {
            return $this->_select_db(DB_NAME, $this->metadata_db) ;
        }

        /**
         * It reconnects to OSClass database. First, it releases the database link connection and it connects again
         * 
         * @access private
         * @since 2.3
         */
        function reconnect_osclass_db()
        {
            $this->release_osclass_db() ;
			$this->connect_to_osclass_db() ;
        }

        /**
         * It reconnects to metadata database. First, it releases the database link connection and it connects again
         * 
         * @access private
         * @since 2.3
         */
        function reconnect_metadata_db()
        {
            $this->release_metadata_db() ;
			$this->connect_to_metadata_db() ;
        }

        /**
         * Release the OSClass database connection
         * 
         * @access private
         * @since 2.3
         * @return boolean 
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
         * Release the metadata database connection
         * 
         * @access private
         * @since 2.3
         * @return boolean 
         */
        function release_metadata_db()
        {
            return $this->_release_db($this->metadata_db) ;
        }

        /**
         * It returns the osclass database link connection
         * 
         * @access public
         * @since 2.3
         */
        function get_osclass_db()
        {
            return $this->_get_db($this->db) ;
        }

        /**
         * It returns the metadata database link connection
         * 
         * @access public
         * @since 2.3
         */
        function get_metadata_db()
        {
            return $this->_get_db($this->metadata_db) ;
        }

        /**
         * Connect to the database passed per parameter
         * 
         * @param string $host Database host
         * @param string $user Database user
         * @param string $password Database user password
         * @param mysqli $conn_id Database connector link
         * @return boolean It returns true if the connection 
         */
        function _connect_to_db($host, $user, $password, &$conn_id)
        {
            $conn_id = new mysqli($host, $user, $password) ;

            if ( $conn_id == false ) {
                return false ;
            }

            return true ;
        }

        /**
         * At the end of the execution it prints the database debug if it's necessary
         * 
         * @since 2.3
         * @access private
         */
        function debug()
        {
            switch ($this->db_debug_level) {
                case 1:     $log = LogDatabase::newInstance() ;
                            $log->print_messages() ;
                break ;
                case 2:     $log = LogDatabase::newInstance() ;
                            echo '<!--' ;
                            $this->print_messages() ;
                            echo '-->' ;
                break ;
            }
        }

        /**
         * It selects the database of a connector database link
         * 
         * @since 2.3
         * @access private
         * @param string $dbname Database name. If you leave blank this field, it will
         * select the database set in the init method
         * @return boolean It returns true if the database has been selected or false if not
         */
        function _select_db($dbname, &$conn_id)
        {
            if ( !$conn_id ) {
                return false ;
            }

            if ( !$conn_id->select_db($dbname) ) {
                return false ;
            }

            return true ;
        }

        /**
         * Set charset of the database passed per parameter
         * 
         * @since 2.3
         * @access private
         * @param string $charset The charset to be set
         * @param mysqli $conn_id Database link connector
         */
        function _set_charset($charset, &$conn_id)
        {
            $conn_id->set_charset($charset) ;
        }

        /**
         * Release the database connection passed per parameter
         * 
         * @since 2.3
         * @access private
         * @param mysqli Database connection to be released 
         * @return boolean It returns true if the database connection is released and false
         * if the database connection couldn't be closed
         */
        function _release_db(&$conn_id)
        {
            if( !$conn_id ) {
                return true ;
            }

            if( !$conn_id->close() ) {
                return false; 
            }

            return true ;
        }

        /**
         * It returns database link connection
         * 
         * @param type $conn_id Database connector link
         * @return mysqli|boolean mysqli link connector if it's correct, or false if the dabase connection
         * hasn't been done.
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