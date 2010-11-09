<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class DB
{
    private $db = null ;
    private $dbHost = null ;
    private $dbUser = null ;
    private $dbPassword = null ;
    private $dbName = null ;
    private $dbLogLevel = null ;
    private $msg = "" ;
    
    
    function __construct($dbHost, $dbUser, $dbPassword, $dbName, $dbLogLevel) {
        $this->dbHost = $dbHost ;
        $this->dbUser = $dbUser ;
        $this->dbPassword = $dbPassword ;
        $this->dbName = $dbName ;
        $this->dbLogLevel = $dbLogLevel ;
        
        $this->osc_dbConnect() ;
    }
    
    function __destruct() {
        $this->osc_dbClose() ;
    }
    
    //logging
    function debug($msg, $ok = true)
    {
        if($this->dbLogLevel != LOG_NONE) {
            $this->msg .= date("d/m/Y - H:i:s") . " " ;
            if ($ok) $this->msg .= "<span style='background-color: #D0F5A9;' >[ OPERATION OK ] " ;
            else $this->msg .= "<span style='background-color: #F5A9A9;' >[ OPERATION FAILED ] " ;
            
            $this->msg .= str_replace("\n", " ", $msg) ;
            
            if($this->dbLogLevel == LOG_WEB) $this->msg .= '</span><br />' ;
            $this->msg .= "\n" ;
        }
    }
    
    function print_debug() {
        switch($this->dbLogLevel) {
            case(LOG_WEB):      echo $this->msg ; 
            break;
            case(LOG_COMMENT):  echo '<!-- ' . $this->msg . ' -->' ;
            break;
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
            $this->debug('Error connecting to \'' . $this->dbName . '\' (' . $this->db->connect_errno . ': ' . $this->db->connect_error . ')', false) ;
        }
        
        $this->debug('Connected to \'' . $this->dbName . '\': [DBHOST] = ' . $this->dbHost . ' | [DBUSER] = ' . $this->dbUser . ' | [DBPWD] = ' . $this->dbPassword) ;
    	$this->db->set_charset('UTF8');
    }
    
    /**
     * Close the database connection.
     */
    function osc_dbClose() {
        if (!$this->db->close()) {
            $this->debug('Error releasing the connection to \'' . $this->dbName . '\'', false) ;
        }
        
        $this->debug('Connection with \'' . $this->dbName . '\' released properly') ;
        $this->print_debug() ;
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
    	    $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false) ;
    	} else {
    	    $this->debug($sql) ;
    	}
    
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
    	    $this->debug($sql) ;
    		$row = $qry->fetch_array();
    		$result = $row[0];
    		$qry->free();
    	} else {
    	    $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false) ;
    	}
    	
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
    	    $this->debug($sql) ;
    		while($result = $qry->fetch_array())
    			$results[] = $result[0];
    		$qry->free();
    	} else {
    	    $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false) ;
    	}
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
    	    $this->debug($sql) ;
    		$result = $qry->fetch_assoc();
    		$qry->free();
    	} else {
    	    $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false) ;
    	}
    	
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
    	    $this->debug($sql) ;
    		while($result = $qry->fetch_assoc())
    			$results[] = $result;
    		$qry->free();
    	} else {
    	    $this->debug($sql . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false) ;
    	}
    	
    	return $results;
    }
    
    /**
     * Import (executes) the SQL passed as parameter making some proper adaptations.
     */
    function osc_dbImportSQL($sql) 
    {
    	$sql = str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql);
    	$sentences = explode(';', $sql);
    	foreach($sentences as $s) {
    		$s = trim($s);
    		if(!empty($s)) {
                if($this->db->query($s)) {
                    $this->debug($s) ;
                } else {
                    $this->debug($s . ' | ' . $this->db->error . ' (' . $this->db->errno . ')', false) ;
                }
            }
    	}
    }
    
    function autocommit($b_value) {
        $this->db->autocommit($b_value) ;
    }
    
    function commit() {
        $this->db->commit() ;
    }
    
    function rollback() {
        $this->db->rollback() ;
    }
    
    function get_last_id() {
        return($this->db->insert_id) ;
    }
    
    function get_affected_rows() {
        return($this->db->affected_rows) ;
    }
}

function getConnection($dbHost = null, $dbUser = null, $dbPassword = null, $dbName = null, $dbLogLevel = null) 
{
    static $instance ;
    
    //DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DEBUG_LEVEL
    if(defined('DB_HOST') && $dbHost == null)                 $dbHost = DB_HOST ;
    if(defined('DB_USER') && $dbUser == null)                 $dbUser = DB_USER ;
    if(defined('DB_PASSWORD') && $dbPassword == null)         $dbPassword = DB_PASSWORD ;
    if(defined('DB_NAME') && $dbName == null)                 $dbName = DB_NAME ;
    if(defined('DEBUG_LEVEL') && $dbLogLevel == null)         $dbLogLevel = DEBUG_LEVEL ;
    
    if(!isset($instance[$dbName . "_" . $dbHost])) {
        if(!isset($instance)) {
            $instance = array() ;
        }
        
        $instance[$dbName . "_" . $dbHost] = new DB($dbHost, $dbUser, $dbPassword, $dbName, $dbLogLevel) ;
    }

    return ($instance[$dbName . "_" . $dbHost]) ;
}

