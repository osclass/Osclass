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

// Load lots of fancy stuff
// DO NOT TOUCH
if(!defined('__FROM_CRON__')) {
	define('__FROM_CRON__', true);
}
if(!defined('__OSC_LOADED__')) {
	require_once '../../oc-load.php';
}

	// INSERT HERE YOUR FUNCTIONS, DO NOT FORGET TO CALL THEM AT THE END
	// THEY WILL RUN DAILY
	osc_runAlert('DAILY');


?>
