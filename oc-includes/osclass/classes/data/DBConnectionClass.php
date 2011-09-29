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
        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * 
         */
        public function __construct() { }

		/**
         * Initializate database connection
         * 
         * @param string $server Host name where it's located the mysql server
         * @param string $database Default database to be used when performing queries
         * @param string $user MySQL user name
         * @param string $password MySQL password
         * @param int $debug_level Debug level
         */
		public function init($server, $user, $password, $database, $debug_level)
		{
            $this->db_host        = $server ;
			$this->db_name        = $database ;
			$this->db_user        = $user ;
			$this->db_password    = $password ;
			$this->db_debug_level = $debug_level ;
            
			$this->connect_to_db() ;
		}

		/**
         * Connection destructor
         */
		public function destroy()
        {
			$this->release_db() ;
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
		function connect_to_db()
		{
			// Try to connect to database server...
            $this->db = new mysqli($this->db_host, $this->db_user, $this->db_password) ;
			$this->error_report() ;

			if ( !$this->db ) {
                $this->error_connection() ;
                $this->release_db() ;
				return false ;
			}

            $this->db->set_charset('utf8') ;

            // Try to select a database...
            if ( !$this->select_db() ) {
                // Select error
                $this->error_report() ;
                $this->release_db() ;
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
		function select_db($dbname = '')
		{
            if( $dbname == '') {
                $dbname = $this->db_name ;
            }

			if ( !$this->db ) {
				$this->release_db() ;
                return false ;
			} 

            if ( !$this->db->select_db($dbname) ) {
                // Failed to select the database... abort connection process
                $this->error_report() ;
                $this->release_db() ;
                return false ;
            }

            // Database selected
            return true ;
		}

        /**
         * 
         */
        function reconnect() {
            $this->release_db() ;
			$this->connect_to_db() ;
        }

        /**
         *
         * @return type 
         */
		function release_db()
		{
			if( !$this->db ) {
                return true ;
            }

            // Close database
            if( !$this->db->close() ) {
                // error closing database
                $this->error_report() ;
                return false; 
            }

            // Connection to database closed successfully
            return true ;
		}

        function get_db() {
            if( $this->db ) {
                return $this->db ;
            }

            return false ;
        }
	}

    /* file end: ./oc-includes/osclass/classes/data/DBConnectionClass.php */
?>