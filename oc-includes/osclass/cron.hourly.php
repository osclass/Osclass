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
	// THEY WILL RUN HOURLY

function update_cat_stats() {
    $date = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
	$sql = sprintf("SELECT COUNT(pk_i_id) as total, fk_i_category_id as category FROM `%st_item` WHERE `dt_pub_date` > '%s' GROUP BY fk_i_category_id", DB_TABLE_PREFIX, $date);
	$conn = getConnection() ;
    $items = $conn->osc_dbFetchResults($sql);

	foreach($items as $stats) {
		$category = $total = 0;
		foreach($stats as $k => $v) {
			if($k=="category") { $category = $v; }
			if($k=="total") { $total = $v; }
		}
		CategoryStats::newInstance()->update(
			array(
				'i_num_items' => $total
			), array('fk_i_category_id' => $category)
		);
	}
}

update_cat_stats();
osc_runAlert('HOURLY');

?>