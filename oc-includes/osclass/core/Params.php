<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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


    class Params
    {
        
        private static $purifier;
        
        function __construct() { }

        static function getParam($param, $htmlencode = false, $xss_check = true)
        {
            if ($param == "") return '' ;
            if (!isset($_REQUEST[$param])) return '' ;

            if($xss_check==true && !isset(self::$purifier)) {
                require_once LIB_PATH . 'htmlpurifier/HTMLPurifier.auto.php';
                $config = HTMLPurifier_Config::createDefault();
                $config->set('HTML.Allowed', 'b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style]');
                $config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
                $config->set('Cache.SerializerPath', ABS_PATH . 'oc-content/uploads');
                self::$purifier = new HTMLPurifier($config);
            }
            
            $value = $_REQUEST[$param];

            if (!is_array($value)) {
                if($xss_check) {
                    $value = self::$purifier->purify($_REQUEST[$param]);
                }
                if ($htmlencode) {
                    return htmlspecialchars(stripslashes($value), ENT_QUOTES);
                }
            } else {
                if($xss_check) {
                    foreach($value as $k => $v) {
                        $value[$k] = self::$purifier->purify($v);
                    }
                }
            }

            if(get_magic_quotes_gpc()) {
                $value = strip_slashes_extended($value);
            }

            return ($value);
        }

        static function existParam($param) {
            if ($param == "") return false ;
            if (!isset($_REQUEST[$param])) return false ;
            return true;
        }

        static function getFiles($param)
        {
            if (isset($_FILES[$param])) {
                return ($_FILES[$param]);
            } else {
                return "";
            }
        }

        //$what = "post, get, cookie"
        static function getParamsAsArray($what = "", $xss_check = true)
        {
            switch ($what) {
                case("get"):    
                    $value = $_GET;
                break;
                case("post"):   
                    $value = $_POST;
                break;
                case("cookie"):
                    return $_COOKIE;
                break;
                default:        
                    $value = $_REQUEST;
                break;
            }
            
            if($xss_check==true) {
                if(!isset(self::$purifier)) {
                    require_once LIB_PATH . 'htmlpurifier/HTMLPurifier.auto.php';
                    $config = HTMLPurifier_Config::createDefault();
                    $config->set('HTML.Allowed', 'b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style]');
                    $config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
                    $config->set('Cache.SerializerPath', ABS_PATH . 'oc-content/uploads');
                    self::$purifier = new HTMLPurifier($config);
                }
                foreach($value as $k => $v) {
                    $value[$k] = self::$purifier->purify($v);
                }
            }
            if(get_magic_quotes_gpc()) {
                return strip_slashes_extended($value);
            }
            return $value;
        }

        static function setParam($key, $value)
        {
            $_REQUEST[$key] = $value;
            $_GET[$key] = $value;
            $_POST[$key] = $value;
        }

        static function _view() {
            print_r(self::getParamsAsArray()) ;
        }
    }

?>