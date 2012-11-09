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
* Helper SphinxSE Database Info
* @package OSClass
* @subpackage Helpers
* @author OSClass
*/

/**
 * Gets SphinxSE database name
 *
 * @return string
 */
function osc_sphinx_db_name() {
	return getSiteInfo('s_sphinx_db_name', SPHINX_DB_NAME) ;
}

/**
 * Gets SphinxSE database host
 *
 * @return string
 */
function osc_sphinx_db_host() {
	return getSiteInfo('s_sphinx_db_host', SPHINX_DB_HOST) ;
}

/**
 * Gets SphinxSE database user
 *
 * @return string
 */
function osc_sphinx_db_user() {
	return getSiteInfo('s_sphinx_db_user', SPHINX_DB_USER) ;
}

/**
 * Gets SphinxSE database password
 *
 * @return string
 */
function osc_sphinx_db_password() {
	return getSiteInfo('s_sphinx_db_password', SPHINX_DB_PASSWORD) ;
}
/**
 * Gets OsClass Index (Table) name of the Items
 *
 * @return string
 */	
function osc_sphinx_item_index() {
	return getSiteInfo('s_sphinx_item_index', SPHINX_ITEM_INDEX) ;
}

?>
