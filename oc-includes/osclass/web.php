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


/**
 * This function gets a input parameter by name and having a default value.
 * @param string parameter name
 * @param string default value
 */
function osc_param($method, $name, $default = null) {
	return isset($method[$name]) ? $method[$name] : $default;
}

/**
 * This function gets a GET parameter by name and having a default value.
 * @param string parameter name
 * @param string default value
 */
function osc_paramGet($name, $default = null) {
	return osc_param($_GET, $name, $default);
}

/**
 * This function gets a POST parameter by name and having a default value.
 * @param string parameter name
 * @param string default value
 */
function osc_paramPost($name, $default = null) {
	return osc_param($_POST, $name, $default);
}

function osc_paramSession($name, $default = null) {
	return osc_param($_SESSION, $name, $default);
}

/**
 * This function gets a REQUEST parameter by name and having a default value.
 * @param string parameter name
 * @param string default value
 */
function osc_paramRequest($name, $default = null) {
	return osc_param($_REQUEST, $name, $default);
}

/**
 * This functions return the web path to use resources available in oc-includes
 *
 * @param string $type Type of file: css, js, images
 * @param string $filename Name of the file to be included
 * @param bool $echo If you want to echo path. By default is false
 * @return string Web path of the resource
 */
function osc_globalResources($type, $filename, $echo = false) {
    $valid_types = array('css', 'js', 'images');
    if(!in_array($type, $valid_types)) {
        return '';
    }

    $path = ABS_WEB_URL . 'oc-includes/' . $type . '/' . $filename;

    if($echo) {
        echo $path;
    }

    return $path;
}