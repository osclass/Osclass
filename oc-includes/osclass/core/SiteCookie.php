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

	class SiteCookie 
	{
		public $name ;
		public $val ;
		public $expires ;
		public $dir ;  		// all dirs
		public $site ;
		
		function init() 
		{
			$this->name = "" ;
			$this->val = array() ;
			$this->dir = '/' ;
			$this->site = TrovitConf::$DOMAIN_COOKIE;
		}
		
		function __construct($cname, $cexpires = "", $cdir = "")
		{
			$this->init() ;
		
			$this->name = $cname ;
			if($cexpires){
				$this->expires = $cexpires ;
			} else {
				$this->expires = time() + 1200 ; // expiración de 20 mins por defecto...
			}
			if($cdir) $this->dir = $cdir ;
            if (isset($_COOKIE[$cname])) 
            {
			    list($vars, $vals) = explode("&", $_COOKIE[$cname]);
			    $vars = explode(".....", $vars);
			    $vals = explode(".....", $vals);
			    while(list($key, $var) = each($vars))
			    {
                    /*if(eregi(':',$var)){
                        list($var,$exp) = explode(':',$var);
                        if($exp=='-1') break;
                        elseif(mktime() <= $exp+60) break;
                    }*/
				    $this->val["$var"] = $vals[$key] ;
				    $_COOKIE["$var"] = $vals[$key] ;
			    }
            }
            //print_r($_COOKIE);
		}
		
		function push($var, $value)
		{
			$this->val["$var"] = $value ;
			$_COOKIE["$var"] = $value ;
		}
		
		function pop($var)
		{
            unset($this->val[$var]) ;
			unset($_COOKIE[$var]) ;
		}
			
		function clear()
		{
			$this->val = array();
		}
			
		function set()
		{
			$cookie_val = "" ;
            if(is_array($this->val) && count($this->val) > 0)
			{
				$cookie_val = '' ;
				$vars = $vals = array() ;
				
				foreach ($this->val as $key => $curr)
				{
					if($curr !== "")
					{
						$vars[] = $key ;
						$vals[] = $curr ;
					}
				}
				if(count($vars) > 0 && count($vals) > 0) {
					$cookie_val = implode(".....", $vars) . "&" . implode(".....", $vals) ;
				}
			}
			//echo "COOKIE:".$cookie_val."nombre:".$this->name;
			//$cookie_val = urldecode($cookie_val);
            setcookie($this->name, $cookie_val, $this->expires, $this->dir, $this->site) ;
		}
        
        function num_vals() {
            return(count($this->val)) ;
        }
        
        function get_value($str) {
            if (isset($this->val[$str])) return($this->val[$str]) ;
            return("") ;
        }

        function set_expires($tm) {
        	//$tm: time in seconds
			$this->expires = time() + $tm ;
		}
	}
?>
