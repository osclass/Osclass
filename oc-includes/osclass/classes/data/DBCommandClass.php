<?PHP
	class DBCommandClass 
	{
		// Init variables required
		public $connobj      = "" ;
		public $sqlstring    = "" ;
		
		// Data related variables
		public $rs ;
		public $recordcount  = 0 ;
		public $EOF          = true ;
	    public $lastid       = 0 ;
	    
	    // Constructor...
		function __construct(&$connobj, $sqlcommand)
		{
            $this->connobj    = &$connobj ;
			$this->sqlstring  = $sqlcommand ;
		}
        
        function __destruct() {
            unset($this->connobj) ;
            unset($this->sqlstring) ;
        }
	    
		// --------- Private methods ---------
		// Executing a command
		function exec_command()
		{
			$this->connobj->msg = "" ;
            
            // Determine if there is an open database and we have an SQL command to execute...
			if ($this->connobj->db && $this->sqlstring!="") {
				$this->rs = mysql_query($this->sqlstring, $this->connobj->db) ;	
				
                if(mysql_errno($this->connobj->db) == 2006) {
                    //mail("daniel@niumba.com","ERROR Reconexion DBCommand!","Reconectamos DBCommand en el server por fallo de timeout!!\n<br />" . $this->sqlstring) ;
                    $this->connobj->reconnect() ; 
                    //Repetimos la peticion de nuevo en el caso de wait_timeout
                    $this->rs = mysql_query($this->sqlstring, $this->connobj->db) ;    
                }
                
                $this->connobj->error_report() ;
                
				// Affectected rows...
				if ($this->rs) {
					$this->EOF = true ;
					$this->recordcount = mysql_affected_rows($this->connobj->db) ;
					$this->lastid = mysql_insert_id($this->connobj->db) ;
					$this->connobj->msg =  date("d/m/Y - H:i:s") . " - OPERATION O.K.: Executed " . $this->sqlstring ." affected " . $this->recordcount . " rows\r\n" ;
				} else {
					$this->recordcount = 0 ;
					$this->EOF = true ;
					$this->connobj->msg =  date("d/m/Y - H:i:s") . " - OPERATION FAILED: Executed " . $this->sqlstring . " got " . mysql_errno($this->connobj->db) . " " . mysql_error($this->connobj->db) . "\r\n";
				}
			} else {
	    		$this->recordcount = 0 ;
		 		$this->EOF = true ;
		 		$this->connobj->msg =  date("d/m/Y - H:i:s") . " - OPERATION FAILED: No database open OR no SQL command provided" . "\r\n" ;		
			}
			$this->connobj->debug() ;
		}
		// --------- Public interface ---------
		// Executing an sql command
		function execute()
		{
			$this->exec_command() ;
            return ($this->recordcount) ;
		}
	}
?>
