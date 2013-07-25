<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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

    require_once LIB_PATH . 'htmlpurifier/HTMLPurifier.auto.php';

    class Params
    {
        private static $purifier;
        private static $config;

        function __construct() { }

        static function getParam($param, $htmlencode = false, $xss_check = true, $quotes_encode = true)
        {
            if ($param == "") return '';
            if (!isset($_REQUEST[$param])) return '';

            $value = self::_purify($_REQUEST[$param], $xss_check);

            if ($htmlencode) {
                if($quotes_encode) {
                    return htmlspecialchars(stripslashes($value), ENT_QUOTES);
                } else {
                    return htmlspecialchars(stripslashes($value), ENT_NOQUOTES);
                }
            }

            if(get_magic_quotes_gpc()) {
                $value = strip_slashes_extended($value);
            }

            return ($value);
        }

        static function existParam($param)
        {
            if ($param == "") return false;
            if (!isset($_REQUEST[$param])) return false;
            return true;
        }

        static function getFiles($param)
        {
            if (isset($_FILES[$param])) {
                return ($_FILES[$param]);
            }

            return "";
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

            $value = self::_purify($value, $xss_check);

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

        static function _view()
        {
            print_r(self::getParamsAsArray());
        }

        static private function _purify($value, $xss_check)
        {
            self::$config = HTMLPurifier_Config::createDefault();
            self::$config->set('HTML.Allowed', '');
            self::$config->set('Cache.SerializerPath', osc_uploads_path());

            if( !$xss_check ) {
                return $value;
            }

            if( !isset(self::$purifier) ) {
                self::$purifier = new HTMLPurifier(self::$config);
            }

            if( is_array($value) ) {
                foreach($value as $k => &$v) {
                    $v = self::_purify($v, $xss_check);
                }
            } else {
                $value = self::$purifier->purify($value);
            }

            return $value;
        }
    }

?>