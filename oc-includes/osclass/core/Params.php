<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
            if( !$xss_check ) {
                return $value;
            }

            self::$config = HTMLPurifier_Config::createDefault();
            self::$config->set('HTML.Allowed', '');
            self::$config->set('Cache.SerializerPath', osc_uploads_path());

            if( !isset(self::$purifier) ) {
                self::$purifier = new HTMLPurifier(self::$config);
            }

            if( is_array($value) ) {
                foreach($value as $k => &$v) {
                    $v = self::_purify($v, $xss_check); // recursive
                }
            } else {
                $value = self::$purifier->purify($value);
            }

            return $value;
        }
    }

?>
