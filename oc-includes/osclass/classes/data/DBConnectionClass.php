<?php
	class DBConnectionClass 
	{
        private static $instance ;
        
		// Init variables required
		public $server ;
		public $database ;
		public $user ;
		public $pwd ;
		public $debuglv ;
        public $who ;
		public $logfile ;
		
		// Data related variables
		public $db           = 0 ;
		public $error_level  = 0 ;
		public $error_desc   = "No errors" ;
		public $filehdl      = 0 ;
		public $msg          = "" ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        //empty constructor (it is not used)
		function __construct() { }
		
		// --------- Public interface ---------
		// Initialization
		function init($server, $database, $user, $pwd, $debulv, $who)
		{
            $this->server		=   $server ;
			$this->database		=   $database ;
			$this->user			=   $user ;
			$this->pwd			=   $pwd ;
			$this->debuglv		=   $debulv ;
            $this->who          =   $who ;
            
            
            $this->logfile_init() ;
			$this->connect_to_db() ;
		}
	
	
		// connection destructor
		function destroy() {
			$this->release_db() ;
		}
		
		
		// --------- Private methods ---------
		// Error reporting auxiliary method
		function error_report()
		{
			$this->error_level = mysql_errno($this->db) ;
			$this->error_desc = mysql_error($this->db) ;
		}
	
		// Opening the database connection
		function connect_to_db()
		{
			// Try to connect to database server...
            $this->db = mysql_connect($this->server, $this->user, $this->pwd, true) ;
            
			$this->error_report() ;
			if (!$this->db) {
				if($this->error_level == 0) $this->error_level = 666;
				// Failed to connect... abort connection process
				$this->msg = date("d/m/Y - H:i:s") . " - ERROR - 1 " .  $_SERVER["PHP_SELF"] . " ~~ ". $this->server . " - " .  $this->database . $this->error_level . ": " . $this->error_desc . "\r\n" ;
				$this->debug() ;
				$this->release_db() ;
			} else {
	            mysql_query("SET NAMES 'utf8'", $this->db) ;
				// Try to select a database...
				if (mysql_select_db($this->database, $this->db)) {
					// Everything is ready to work with the database
					$this->msg = date("d/m/Y - H:i:s") . " - OPERATION O.K.: Connected to database " . $this->database .  "\r\n" ;
				} else {
					// Failed to select the database... abort connection process
					$this->error_report() ;
					$this->msg = date("d/m/Y - H:i:s") . " - ERROR - 2 " . $_SERVER["PHP_SELF"] . " ~~ " . $this->database . $this->error_level . ": " . $this->error_desc . "\r\n" ;
					$this->debug() ;
					$this->release_db() ;
				}
			}
		}

		// Opening the database connection
		function select_db( $dbname )
		{
			if (!$this->db) {
				// Failed to connect... abort connection process
				$this->msg = date("d/m/Y - H:i:s") . " - ERROR - 3 " .  $_SERVER["PHP_SELF"] . " ~~ " . $this->error_level . ": " . $this->error_desc . "\r\n" ;
				$this->debug() ;
				$this->release_db() ;
			} else {
				// Try to select a database...
				$this->database = $dbname;
				if (mysql_select_db($this->database, $this->db)) {
					// Everything is ready to work with the database
					$this->msg = date("d/m/Y - H:i:s") . " - OPERATION O.K.: Selected database " . $this->database .  "\r\n" ;
				} else {
					// Failed to select the database... abort connection process
					$this->error_report() ;
					$this->msg = date("d/m/Y - H:i:s") . " - ERROR - 4 " .  $_SERVER["PHP_SELF"] . " ~~ " . $this->error_level . ": " . $this->error_desc . "\r\n" ;
					$this->debug() ;
					$this->release_db() ;
				}
			}
		}
		
        function reconnect() {
            $this->release_db() ;
            $this->logfile_init() ;
			$this->connect_to_db() ;
        }
	
		// Releasing database connection
		function release_db()
		{
			// Do we have a database open?
			if ($this->db) {
				// Must close the connection... not too important as php does by itself...
				if (mysql_close($this->db)) {
					$this->msg = date("d/m/Y - H:i:s") . " - OPERATION O.K.: Database " . $this->database . " released" . "\r\n";
				} else {
					// Failed to liberate the database...
					$this->error_report() ;
					$this->msg = date("d/m/Y - H:i:s") . " - ERROR " . $this->error_level . ": " . $this->error_desc . "\r\n" ;
				}
			} else {
				// No database open
				$this->msg = date("d/m/Y - H:i:s") . " - OPERATION CANCELLED: No database open" . "\r\n";
			}
			// LOG the operation and close logging operations
			$this->debug() ;
		}
	
		// Log operations initialization
		function logfile_init()
		{
			$fechagm = gmmktime()+3600 ;
			$fecha = getdate($fechagm) ;
			$this->msg = date("d/m/Y - H:i:s") . " ===== SESSION STARTED BY " . $this->who . " =====" .  "\r\n" ;
			switch ($this->debuglv) 
			{
				case 0: // NO LOG OPERATIONS
				break ;
				case 1: // SCREEN OUTPUT
				break ;
				case 2: // SILENT OUTPUT (<!-- -->)
				break ;
			}
			$this->debug() ;
		}

		// Debugging operations
		function debug()
		{
			switch ($this->debuglv) 
			{
				case 0: // NO LOG OPERATIONS
				break ;
				case 1: // SCREEN OUTPUT
				        echo '<br>DEBUG: ' . $this->msg . '<BR>' ;
				break ;
				case 2: // SILENT OUTPUT (<!-- -->)
				        echo "\n<!-- DEBUG: " . $this->msg . "-->\n" ;
				break ;
			}
		}
	}
?>
