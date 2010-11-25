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

define('OSCLASS_VERSION', '1.1 RC');

define('APP_PATH', dirname(__FILE__));

define('LIB_PATH', APP_PATH . '/oc-includes');
define('THEMES_PATH', APP_PATH . '/oc-content/themes/');
define('PLUGINS_PATH', APP_PATH . '/oc-content/plugins/');
define('TRANSLATIONS_PATH', APP_PATH . '/oc-includes/translations/');

define('INST_FOLDER_NAME', '/oc-installer');

/** Defines for error reporting */
define('LOG_NONE', 0);
define('LOG_WEB', 1);
define('LOG_COMMENT', 2);
define('DEBUG_LEVEL', LOG_NONE) ;

set_include_path(APP_PATH . DIRECTORY_SEPARATOR . '/oc-includes' . PATH_SEPARATOR . get_include_path());