<?php
/**
 * OSClass – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2011 OSCLASS
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

class Params
{
    function __construct() {}

    static function getParam($param, $htmlencode = false)
    {
        if ($param == "") return '' ;
        if (!isset($_REQUEST[$param])) return '' ;

        $value = $_REQUEST[$param];
        if (!is_array($value)) {
            if ($htmlencode) {
                return htmlspecialchars(stripslashes($value), ENT_QUOTES);
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
    static function getParamsAsArray($what = "")
    {
        switch ($what) {
            case("get"):    return(strip_slashes_extended($_GET)) ;
            break;
            case("post"):   return(strip_slashes_extended($_POST)) ;
            break;
            case("cookie"): return($_COOKIE) ;
            break;
            default:        return(strip_slashes_extended($_REQUEST)) ;
            break;
        }
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
