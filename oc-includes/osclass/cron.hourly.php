<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.') ;

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
        define('__FROM_CRON__', true) ;
    }

    function update_cat_stats() {
        $categoryTotal = array() ;
        $categoryTree  = array() ;
        $aCategories   = Category::newInstance()->listAll(false) ;

        // append root categories and get the number of items of each category
        foreach($aCategories as $category) {
            $total     = Item::newInstance()->numItems($category, true, true) ;
            $category += array('category' => array()) ;
            if( is_null($category['fk_i_parent_id']) ) {
                $categoryTree += array($category['pk_i_id'] => $category) ;
            }

            $categoryTotal += array($category['pk_i_id'] => $total) ;
        }
        
        // append childs to root categories
        foreach($aCategories as $category) {
            if( !is_null($category['fk_i_parent_id']) ) {
                $categoryTree[$category['fk_i_parent_id']]['category'][] = $category ;
            }
        }

        // sum the result of the subcategories and set in the parent category
        foreach($categoryTree as $category) {
            if( count( $category['category'] ) > 0 ) {
                foreach($category['category'] as $subcategory) {
                    $categoryTotal[$category['pk_i_id']] += $categoryTotal[$subcategory['pk_i_id']] ;
                }
            }
        }

        foreach($categoryTotal as $k => $v) {
            CategoryStats::newInstance()->setNumItems($k, $v) ;
        }
    }

    function purge_latest_searches_hourly() {
        $purge = osc_purge_latest_searches() ;
        if( $purge == 'hour' ) {
            LatestSearches::newInstance()->purgeDate( date('Y-m-d H:i:s', ( time() - 3600) ) ) ;
        } else if( !in_array($purge, array('forever', 'day', 'week')) ) {
            LatestSearches::newInstance()->purgeNumber($purge) ;
        }
    }

    osc_add_hook('cron_hourly', 'update_cat_stats') ;
    osc_add_hook('cron_hourly', 'purge_latest_searches_hourly') ;

    osc_runAlert('HOURLY') ;

    osc_run_hook('cron_hourly') ;

?>