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
        private $dbHost ;
        /**
         * Database name where it's installed OSClass
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        private $dbName ;
        /**
         * Database user
         * 
         * @access private
         * @since 2.3
         * @var string
         */
        private $dbUser ;
        /**
         * Database user password
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        private $dbPassword ;
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
        private $dbDebugLevel ;

        /**
         * Database connection object to OSClass database
         * 
         * @access private
         * @since 2.3
         * @var mysqli 
         */
        private $db             = 0 ;
        /**
         * Database connection object to metadata database
         * 
         * @access private
         * @since 2.3
         * @var mysqli 
         */
        private $metadataDb     = 0 ;
        /**
         * Database error number
         * 
         * @access private
         * @since 2.3
         * @var int 
         */
        private $errorLevel     = 0 ;
        /**
         * Database error description
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        private $errorDesc      = "" ;
        /**
         * Database connection error number 
         * 
         * @access private
         * @since 2.3
         * @var int 
         */
        private $connErrorLevel = 0 ;
        /**
         * Database connection error description
         * 
         * @access private
         * @since 2.3
         * @var string 
         */
        private $connErrorDesc  = 0 ;

        /**
         * It creates a new DBConnection object class or if it has been created before, it 
         * returns the previous object
         * 
         * @access public
         * @since 2.3
         * @param string $server Host name where it's located the mysql server
         * @param string $database Default database to be used when performing queries
         * @param string $user MySQL user name
         * @param string $password MySQL password
         * @param int $debugLevel Debug level
         * @return DBConnectionClass 
         */
        public static function newInstance($server = '', $user = '', $password = '', $database = '', $debugLevel = '')
        {
            $server      = ($server == '') ? osc_db_host() : $server ;
            $user        = ($user == '') ? osc_db_user() : $user ;
            $password    = ($password == '') ? osc_db_password() : $password ;
            $database    = ($database == '') ? osc_db_name() : $database ;
            $debugLevel = ($debugLevel == '') ? DEBUG_LEVEL : $debugLevel ;

            if(!self::$instance instanceof self) {
                self::$instance = new self ($server, $user, $password, $database, $debugLevel);
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
         * @param int $debugLevel Debug level
         */
        public function __construct($server, $user, $password, $database, $debugLevel)
        {
            $this->dbHost       = $server ;
            $this->dbName       = $database ;
            $this->dbUser       = $user ;
            $this->dbPassword   = $password ;
            $this->dbDebugLevel = $debugLevel ;

            $this->connectToOsclassDb() ;
        }

		/**
         * Connection destructor and print debug
         */
        public function __destruct()
        {
            $this->releaseOsclassDb() ;
            $this->releaseMetadataDb() ;
            $this->debug() ;
        }

		/**
         * Set error num error and error description
         * 
         * @access private
         * @since 2.3
         */
		function errorReport()
		{
			$this->errorLevel = $this->db->errno ;
			$this->errorDesc  = $this->db->error ;
		}

        /**
         * Set connection error num error and connection error description
         * 
         * @access private
         * @since 2.3
         */
        function errorConnection()
        {
            $this->connErrorLevel = $this->db->connect_errno ;
            $this->connErrorDesc  = $this->db->connect_error ;
        }

        /**
         * Connect to OSClass database
         * 
         * @access public
         * @since 2.3
         * @return boolean It returns true if the connection has been successful or false if not
         */
        function connectToOsclassDb()
        {
            $conn = $this->_connectToDb($this->dbHost, $this->dbUser, $this->dbPassword, $this->db) ;

            if ( $conn == false ) {
                $this->errorConnection() ;
                $this->releaseOsclassDb() ;
                return false ;
            }

            $this->_setCharset('utf8', $this->db) ;

            $selectDb = $this->selectOsclassDb() ;
            if ( $selectDb == false) {
                $this->errorReport() ;
                $this->releaseOsclassDb() ;
                return false ;
            }

            return true ;
		}

        /**
         * Connect to metadata database
         * 
         * @access public
         * @since 2.3
         * @return boolean It returns true if the connection has been successful or false if not
         */
        function connectToMetadataDb()
        {
            $conn = $this->_connectToDb(DB_HOST, DB_USER, DB_PASSWORD, $this->metadataDb) ;

			if ( $conn == false ) {
                $this->releaseMetadataDb() ;
				return false ;
			}

            $this->_setCharset('utf8', $this->metadataDb) ;

            $selectDb = $this->selectMetadataDb() ;
            if ( $selectDb == false ) {
                $this->releaseMetadataDb() ;
                return false ;
            }

            return true ;
        }

        /**
         * Select OSClass database in $db var
         * 
         * @access private
         * @since 2.3
         * @return boolean It returns true if the database has been selected sucessfully or false if not
         */
        function selectOsclassDb()
        {
            return $this->_selectDb($this->dbName, $this->db) ;
        }

        /**
         * Select metadata database in $metadata_db var
         * 
         * @access private
         * @since 2.3
         * @return boolean It returns true if the database has been selected sucessfully or false if not
         */
        function selectMetadataDb()
        {
            return $this->_selectDb(DB_NAME, $this->metadataDb) ;
        }

        /**
         * It reconnects to OSClass database. First, it releases the database link connection and it connects again
         * 
         * @access private
         * @since 2.3
         */
        function reconnectOsclassDb()
        {
            $this->releaseOsclassDb() ;
			$this->connectToOsclassDb() ;
        }

        /**
         * It reconnects to metadata database. First, it releases the database link connection and it connects again
         * 
         * @access private
         * @since 2.3
         */
        function reconnectMetadataDb()
        {
            $this->releaseMetadataDb() ;
			$this->connectToMetadataDb() ;
        }

        /**
         * Release the OSClass database connection
         * 
         * @access private
         * @since 2.3
         * @return boolean 
         */
        function releaseOsclassDb()
        {
            $release = $this->_releaseDb($this->db) ;

            if( !$release ) {
                $this->errorReport() ;
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
        function releaseMetadataDb()
        {
            return $this->_releaseDb($this->metadataDb) ;
        }

        /**
         * It returns the osclass database link connection
         * 
         * @access public
         * @since 2.3
         */
        function getOsclassDb()
        {
            return $this->_getDb($this->db) ;
        }

        /**
         * It returns the metadata database link connection
         * 
         * @access public
         * @since 2.3
         */
        function getMetadataDb()
        {
            return $this->_getDb($this->metadataDb) ;
        }

        /**
         * Connect to the database passed per parameter
         * 
         * @param string $host Database host
         * @param string $user Database user
         * @param string $password Database user password
         * @param mysqli $connId Database connector link
         * @return boolean It returns true if the connection 
         */
        function _connectToDb($host, $user, $password, &$connId)
        {
            $connId = new mysqli($host, $user, $password) ;

            if ( $connId == false ) {
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
            switch ($this->dbDebugLevel) {
                case 1:     $log = LogDatabase::newInstance() ;
                            $log->printMessages() ;
                break ;
                case 2:     $log = LogDatabase::newInstance() ;
                            echo '<!--' ;
                            $log->printMessages() ;
                            echo '-->' ;
                break ;
            }
        }

        /**
         * It selects the database of a connector database link
         * 
         * @since 2.3
         * @access private
         * @param string $dbName Database name. If you leave blank this field, it will
         * select the database set in the init method
         * @param mysqli $connId Database connector link
         * @return boolean It returns true if the database has been selected or false if not
         */
        function _selectDb($dbName, &$connId)
        {
            if ( !$connId ) {
                return false ;
            }

            if ( !$connId->select_db($dbName) ) {
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
         * @param mysqli $connId Database link connector
         */
        function _setCharset($charset, &$connId)
        {
            $connId->set_charset($charset) ;
        }

        /**
         * Release the database connection passed per parameter
         * 
         * @since 2.3
         * @access private
         * @param mysqli $connId Database connection to be released 
         * @return boolean It returns true if the database connection is released and false
         * if the database connection couldn't be closed
         */
        function _releaseDb(&$connId)
        {
            if( !$connId ) {
                return true ;
            }

            if( !$connId->close() ) {
                return false; 
            }

            return true ;
        }

        /**
         * It returns database link connection
         * 
         * @param mysqli $connId Database connector link
         * @return mixed mysqli link connector if it's correct, or false if the dabase connection
         * hasn't been done.
         */
        function _getDb(&$connId)
        {
            if( $connId != false ) {
                return $connId ;
            }

            return false ;
        }
	}

    /* file end: ./oc-includes/osclass/classes/database/DBConnectionClass.php */
?>