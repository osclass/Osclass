<?php
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

/**
 * Check if json_encode function is loaded. In case it is not loaded, we implement it.
 */
if ( !function_exists('json_encode') ) {
    function json_encode( $string ) {
        global $osc_json;

        if ( !is_a($osc_json, 'Services_JSON') ) {
            require_once LIB_PATH . 'json/JSON.php';
            $osc_json = new Services_JSON();
        }

        return $osc_json->encode( $string );
    }
}

/**
 * Check if json_decode function is loaded. In case it is not loaded, we implement it.
 */
if ( !function_exists('json_decode') ) {
    function json_decode( $string, $assoc_array = false ) {
        global $osc_json;

        if ( !is_a($osc_json, 'Services_JSON') ) {
            require_once LIB_PATH . '/json/JSON.php';
            $osc_json = new Services_JSON();
        }

        $res = $osc_json->decode( $string );
        if ( $assoc_array ) $res = _json_decode_object_helper( $res );

        return $res;
    }

    function _json_decode_object_helper($data) {
        if ( is_object($data) )
            $data = get_object_vars($data);

        return is_array($data) ? array_map(__FUNCTION__, $data) : $data;
    }
}

/**
 * Check if mb_substr function is loaded. In case it is not loaded, we implement it.
 */
if ( !function_exists('mb_substr') ) {
    function mb_substr( $str, $start, $length = null, $encoding = null ) {
        preg_match_all( '/./us', $str, $match );
        $chars = is_null( $length ) ? array_slice( $match[0], $start ) : array_slice( $match[0], $start, $length );
        return implode('', $chars );
    }
}

if ( !function_exists('mb_strlen') ) {
    function mb_strlen($str, $encoding = null ) {
        return strlen($str);
    }
}

?>