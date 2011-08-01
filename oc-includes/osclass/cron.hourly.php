<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');
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

    if( !defined('__FROM_CRON__') ) {
        define('__FROM_CRON__', true);
    }

    function count_items_subcategories($category = null) {
        $manager = CategoryStats::newInstance();
        $total = $manager->countItemsFromCategory($category['pk_i_id']);
        $categories = Category::newInstance()->isParentOf($category['pk_i_id']);
        if($categories!=null) {
            foreach($categories as $c) {
                if($c['b_enabled']==1) {
                    $total += count_items_subcategories($c);
                }
            }
        }
        $conn = getConnection();
        $conn->osc_dbExec("INSERT INTO %st_category_stats (fk_i_category_id, i_num_items) VALUES (%d, %d) ON DUPLICATE KEY UPDATE i_num_items = %d", DB_TABLE_PREFIX, $category['pk_i_id'], $total, $total);
        return $total;
    }

    function update_cat_stats() {
        $conn = getConnection() ;
        $sql_cats = "SELECT pk_i_id, i_expiration_days FROM ".DB_TABLE_PREFIX."t_category";
        $cats = $conn->osc_dbFetchResults($sql_cats);

        foreach($cats as $c) {
            if($c['i_expiration_days']==0) {
                $sql = sprintf("SELECT COUNT(pk_i_id) as total, fk_i_category_id as category FROM `%st_item` WHERE fk_i_category_id = %d AND b_enabled = 1 AND b_active = 1 GROUP BY fk_i_category_id", DB_TABLE_PREFIX, $c['pk_i_id']);
            } else {
                $sql = sprintf("SELECT COUNT(pk_i_id) as total, fk_i_category_id as category FROM `%st_item` WHERE fk_i_category_id = %d AND b_enabled = 1 AND b_active = 1 AND (b_premium = 1 || TIMESTAMPDIFF(DAY,dt_pub_date,NOW()) < %d) GROUP BY fk_i_category_id", DB_TABLE_PREFIX, $c['pk_i_id'], $c['i_expiration_days']);
            }

            $total = $conn->osc_dbFetchResult($sql);
            $total = $total['total'];

            $conn->osc_dbExec("INSERT INTO %st_category_stats (fk_i_category_id, i_num_items) VALUES (%d, %d) ON DUPLICATE KEY UPDATE i_num_items = %d", DB_TABLE_PREFIX, $c['pk_i_id'], $total, $total);
        }

        $categories = Category::newInstance()->findRootCategories();
        foreach($categories as $c) {
            $total = count_items_subcategories($c);
        }
    }


    function purge_latest_searches_hourly() {
        $purge = osc_purge_latest_searches();
        if($purge == 'day') {
            LatestSearches::newInstance()->purgeDate(date('Y-m-d H:i:s', (time()-3600)));
        } else if($purge!='forever' && $purge!='day' && $purge!='week') {
            LatestSearches::newInstance()->purgeNumber($purge);
        }
    }

    osc_add_hook('cron_hourly', 'update_cat_stats');
    osc_add_hook('cron_hourly', 'purge_latest_searches_hourly');
    osc_runAlert('HOURLY');

    osc_run_hook('cron_hourly');

?>