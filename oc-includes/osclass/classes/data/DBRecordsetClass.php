<?PHP
	class DBRecordsetClass {
		
		// Init variables required
		public $connobj      = "" ;
		public $sqlstring    = "" ;
		
		// Data related variables
		public $rs           = 0 ;
		public $row          = 0 ;
		public $recordcount  = 0 ;
		public $EOF          = true ;
	
		//constructor
		function __construct($connobj, $sqlcommand)
		{
			$this->connobj = $connobj ;
			$this->sqlstring = $sqlcommand ;
		}
	
	
		// Public interface
		function query()
		{
			$this->get_recordset() ;
			return $this->recordcount ;
		}
	
		function movenext()
		{
			$this->get_row() ;
			return $this->EOF ;
		}
		
		function field($campo)	
		{
			echo stripslashes($this->row[$campo]) ;
		}
	
		function frm_field($campo)	
		{
			echo htmlspecialchars(stripslashes($this->row[$campo])) ;
		}
	
		function value($campo)
		{
			return $this->row[$campo] ;
		}
		
		function clear_recordset()
		{
			$this->sqlstring = "" ;
			if ($this->rs) {
				mysql_free_result($this->rs) ;
			}
		}
		
		function move_to($rnumber)
		{
			if ($this->rs)
			{
				if (!mysql_data_seek($this->rs,$rnumber)) 
				{
					$this->connobj->error_report() ;
					$this->connobj->msg = date("d/m/Y - H:i:s") . " - OPERATION FAILED: Could not move to record " . $rnumber . " " . $this->connobj->error_level . " " . $this->connobj->error_desc . "\r\n";
				} else {
					$this->connobj->msg = date("d/m/Y - H:i:s") . " - OPERATION O.K.: Result pointer moved to " . $rnumber . "\r\n" ;
				}
			} else {
				$this->connobj->msg = date("d/m/Y - H:i:s") . " - OPERATION FAILED: No result open" . "\r\n" ;
			}
			$this->connobj->debug() ;
		}
		
		function movefirst()
		{
			$this->move_to(0) ;
		}
		
		function movelast()
		{
			$this->move_to($this->recordcount-1) ;
		}
	
	
		// Private methods	
		function get_recordset()
		{
			$this->connobj->msg = "" ;
            
            // Determine if there's an open database a valid sql command
			if ($this->connobj->db && $this->sqlstring != "") 
			{
				$this->rs = mysql_query($this->sqlstring, $this->connobj->db) ;	
                
                if(mysql_errno($this->connobj->db) == 2006) {
                    //mail("daniel@niumba.com","ERROR Reconexion DBRecordset!","Reconectamos DBRecordset en el server por fallo de timeout!!\n<br />" . $this->sqlstring) ;
                    $this->connobj->reconnect() ; 
                    //Repetimos la peticion de nuevo en el caso de wait_timeout
				    $this->rs = mysql_query($this->sqlstring, $this->connobj->db) ;	
                }
				$this->connobj->error_report() ;
                
				// Determine if there's a valid result
				if ($this->rs) 
				{
					// Valid recordset
					$this->EOF = false ;
					$this->recordcount = mysql_num_rows($this->rs) ;
					$this->connobj->msg = date("d/m/Y - H:i:s") . " - OPERATION O.K.: Executed " . $this->sqlstring ." got " . $this->recordcount . " rows" . "\r\n";
				} else {
				    // Not a valid recordset
					$this->recordcount = 0 ;
					$this->EOF = true ;
					$this->connobj->msg = date("d/m/Y - H:i:s") . " - OPERATION FAILED: Executed " . $this->sqlstring . " got " . $this->connobj->error_level . " " . $this->connobj->error_desc . "\r\n" ;
				}
			} else {
                // No db or no SQL command
				$this->recordcount = 0 ;
				$this->EOF = true ;
				$this->connobj->msg = date("d/m/Y - H:i:s") . " - OPERATION CANCELED: No database open OR no SQL command provided" . "\r\n" ;
			}
			$this->connobj->debug() ;
		}
		
		function get_row()
		{
			if ($this->rs && $this->connobj->db) 
			{
				$this->row = mysql_fetch_array($this->rs, MYSQL_ASSOC) ;
				$this->connobj->error_report() ;
				if ($this->row){
					$this->EOF = false ;
				} else {
					$this->EOF = true ;
				}
			} else {
	 			$this->connobj->msg = date("d/m/Y - H:i:s") . " - OPERATION FAILED: No database open" . "\r\n" ;
			}
		}

		function fetch_array() {
			return($this->row) ;
		}
	}
?>
