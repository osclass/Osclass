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


function count_items_subcategories($category = null) {
    $manager = CategoryStats::newInstance();
    $total = $manager->countItemsFromCategory($category['pk_i_id']);
    $categories = Category::newInstance()->isParentOf($category['pk_i_id']);
    if($categories!=null) {
        foreach($categories as $c) {
            $total += count_items_subcategories($c);
        }
    }
    return $total;
}

function update_cat_stats() {

    //$manager = CategoryStats::newInstance();

	$conn = getConnection() ;
	$sql_cats = "SELECT pk_i_id FROM ".DB_TABLE_PREFIX."t_category";
	$cats = $conn->osc_dbFetchResults($sql_cats);
	
	foreach($cats as $c) {
        $date = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
	    $sql = sprintf("SELECT COUNT(pk_i_id) as total, fk_i_category_id as category FROM `%st_item` WHERE `dt_pub_date` > '%s' AND fk_i_category_id = %d", DB_TABLE_PREFIX, $date, $c['pk_i_id']);
        $total = $conn->osc_dbFetchResult($sql);
        $total = $total['total'];
        
        /*$manager->update(
            array(
                'i_num_items' => $total
                ), array('fk_i_category_id' => $c['pk_i_id'])
            );*/
        $conn->osc_dbExec("INSERT INTO %st_category_stats (fk_i_category_id, i_num_items) VALUES (%d, %d) ON DUPLICATE KEY UPDATE i_num_items = %d", DB_TABLE_PREFIX, $c['pk_i_id'], $total, $total);
	}
	
	
	$categories = Category::newInstance()->findRootCategories();
	foreach($categories as $c) {
        /*$manager->update(
			array(
				'i_num_items' => count_items_subcategories($c)
			), array('fk_i_category_id' => $c['pk_i_id'])
		);*/
		$total = count_items_subcategories($c);
        $conn->osc_dbExec("INSERT INTO %st_category_stats (fk_i_category_id, i_num_items) VALUES (%d, %d) ON DUPLICATE KEY UPDATE i_num_items = %d", DB_TABLE_PREFIX, $c['pk_i_id'], $total, $total);

			
	}
	
}

update_cat_stats();
osc_runAlert('HOURLY');

?>