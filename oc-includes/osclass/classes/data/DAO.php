<?php

    define('LOG_NONE', 0) ;
    define('LOG_WEB', 1) ;
    define('LOG_COMMENT', 2) ;
    define('DEBUG_LEVEL', LOG_WEB) ;

	require_once LIB_PATH . "osclass/classes/data/DBConnectionClass.php" ;
	require_once LIB_PATH . "osclass/classes/data/DBCommandClass.php" ;
	require_once LIB_PATH . "osclass/classes/data/DBRecordsetClass.php" ;

	class DAO
    {
        //attributes
		public $conn ;
        
		/**
		* Get a connection object in a static variable
		*
		* @param mixed $connect
		* @return boolean 
		*/
		function getConnection($db_name, $db_server, $db_user, $db_pwd, $db_log_level, $who) {
            $this->conn = DBConnectionClass::newInstance() ;
            $this->conn->init($db_server, $db_name, $db_user, $db_pwd, $db_log_level, $who) ;
		}              
		
		function get_sql($sql) {
			$rs = new DBRecordsetClass(self::$conn, $sql) ;
			$rs->query() ;
			
			while(!$rs->movenext()) {
				$aDO[] = $rs->fetch_array() ;
			}
			return ($aDO) ;			
		}
        
        function select_db($db) {
            self::$conn->select_db($db) ;
        }

        function get_table_name($table) {
            return DB_TABLE_PREFIX . $table ;
        }
	}

?>
